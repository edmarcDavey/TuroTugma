<?php

namespace App\Services;

use App\Models\GradeLevel;

class SectionNamingService
{
    protected $themes;
    protected $overflow;

    public function __construct()
    {
        $cfg = config('section_themes', []);
        $this->themes = $cfg['themes'] ?? [];
        $this->overflow = $cfg['overflow_strategy'] ?? 'suffix';
    }

    /**
     * Generate section names for a grade level according to its theme.
     *
     * @param GradeLevel $gradeLevel
     * @param int $count
     * @param array $options
     * @return array
     */
    public function generate(GradeLevel $gradeLevel, int $count, array $options = []): array
    {
        $themeKeyRaw = $gradeLevel->section_naming ?? ($options['theme'] ?? 'letters');

        // If user pasted a comma/newline-separated explicit list, detect it and use directly.
        if (is_string($themeKeyRaw) && preg_match('/[,\n;|\\/]/', $themeKeyRaw)) {
            // split on common separators and treat as explicit pool
            $parts = preg_split('/[,;|\\n\\r\\/]+/', $themeKeyRaw);
            $pool = array_values(array_filter(array_map('trim', $parts), function ($v) { return $v !== ''; }));
            $names = $this->themedSequence($pool, $count);

            $out = [];
            for ($i = 0; $i < count($names); $i++) {
                $name = $names[$i];
                $ordinal = $i + 1;
                $label = trim(($gradeLevel->name ?? '') . ' - ' . $name);
                $out[] = ['ordinal' => $ordinal, 'name' => $name, 'label' => $label];
            }

            return $out;
        }

        $themeKey = $themeKeyRaw;

        // Allow theme to be provided as either the config key or the human label.
        $theme = $this->themes[$themeKey] ?? null;

        // If direct key lookup failed, try some lightweight heuristics based on keywords
        if (!$theme && is_string($themeKeyRaw)) {
            $k = strtolower($themeKeyRaw);
            $heuristics = [
                'president' => 'philippine_presidents',
                'philipp' => 'philippine_presidents',
                'presidents' => 'philippine_presidents',
                'national hero' => 'filipino_national_heroes',
                'hero' => 'filipino_national_heroes',
                'color' => 'colors',
                'colour' => 'colors',
                'animal' => 'animals',
                'virtue' => 'virtues',
                'fruit' => 'endemic_fruits',
                'flower' => 'endemic_flowers',
                'tree' => 'endemic_trees',
                'gem' => 'gemstones',
                'planet' => 'planets',
                'constellation' => 'constellations',
                'alphabet' => 'alphabets',
                'number' => 'numbers_list',
                'sport' => 'sports',
                'profession' => 'professions',
                'motto' => 'school_mottos',
            ];

            foreach ($heuristics as $pattern => $mapped) {
                if (str_contains($k, $pattern) && isset($this->themes[$mapped])) {
                    $themeKey = $mapped;
                    $theme = $this->themes[$mapped];
                    break;
                }
            }
        }

        // If still no theme found and user left theme blank or asked for 'random'/'auto', pick a random suitable theme.
        if (!$theme) {
            $trimmed = is_string($themeKeyRaw) ? trim($themeKeyRaw) : '';
            $lower = strtolower($trimmed);
            if ($trimmed === '' || in_array($lower, ['random', 'auto'])) {
                $candidates = [];
                foreach ($this->themes as $k => $t) {
                    $pool = $t['list'] ?? [];
                    if (empty($pool)) continue;
                    // skip purely numeric or single-letter pools
                    $allNumeric = true;
                    $allSingleLetter = true;
                    foreach ($pool as $p) {
                        $s = (string)$p;
                        if (!ctype_digit($s)) $allNumeric = false;
                        if (!preg_match('/^[A-Za-z]$/', $s)) $allSingleLetter = false;
                    }
                    if ($allNumeric || $allSingleLetter) continue;
                    $candidates[] = $k;
                }
                if (!empty($candidates)) {
                    $picked = $candidates[array_rand($candidates)];
                    $themeKey = $picked;
                    $theme = $this->themes[$picked];
                }
            }
        }
        if (!$theme) {
            // try to find by label (case-insensitive)
            foreach ($this->themes as $k => $t) {
                $label = $t['label'] ?? null;
                if ($label && strcasecmp(trim($label), trim($themeKey)) === 0) {
                    $themeKey = $k;
                    $theme = $t;
                    break;
                }
            }
        }

        if (!$theme) {
            // default to letters
            $themeKey = 'letters';
            $theme = $this->themes['letters'] ?? ['list' => []];
        }

        $list = $theme['list'] ?? [];

        if ($themeKey === 'numbers') {
            $names = $this->numbersSequence($count, $options);
        } elseif (!empty($list)) {
            $names = $this->themedSequence($list, $count);
        } else {
            $names = $this->lettersSequence($count, $options);
        }

        $out = [];
        for ($i = 0; $i < count($names); $i++) {
            $name = $names[$i];
            $ordinal = $i + 1;
            $label = trim(($gradeLevel->name ?? '') . ' - ' . $name);
            $out[] = ['ordinal' => $ordinal, 'name' => $name, 'label' => $label];
        }

        // If caller requested metadata, return names plus the resolved theme key
        if (!empty($options['return_meta'])) {
            $themeLabel = $theme['label'] ?? $themeKey;
            return ['names' => $out, 'theme_key' => $themeKey, 'theme_label' => $themeLabel];
        }

        return $out;
    }

    protected function lettersSequence(int $count, array $options = []): array
    {
        $out = [];
        $i = 0;
        while (count($out) < $count) {
            $out[] = $this->numberToLetters($i);
            $i++;
        }
        return $out;
    }

    protected function numbersSequence(int $count, array $options = []): array
    {
        $start = intval($options['start'] ?? 1);
        $out = [];
        for ($i = 0; $i < $count; $i++) {
            $out[] = (string)($start + $i);
        }
        return $out;
    }

    protected function themedSequence(array $pool, int $count, bool $preserveOrder = false): array
    {
        $out = [];
        $len = count($pool);
        if ($len === 0) return $out;
        // Detect if pool is purely numeric or single-letter alphabets; those should preserve order
        $allNumeric = true;
        $allSingleLetter = true;
        foreach ($pool as $item) {
            $s = (string)$item;
            if (!ctype_digit($s)) $allNumeric = false;
            if (!preg_match('/^[A-Za-z]$/', $s)) $allSingleLetter = false;
        }

        $forcePreserve = $preserveOrder || $allNumeric || $allSingleLetter;

        $working = $pool;
        if (!$forcePreserve) {
            // randomize the order for thematic lists (but not for pure numbers/letters)
            shuffle($working);
        }

        for ($i = 0; $i < $count; $i++) {
            if ($i < $len) {
                $out[] = $working[$i];
            } else {
                // overflow behavior: append suffix
                if ($this->overflow === 'suffix') {
                    $base = $working[$i % $len];
                    $suffix = intdiv($i, $len) + 1;
                    $out[] = $base . '-' . $suffix;
                } else {
                    // repeat
                    $out[] = $working[$i % $len];
                }
            }
        }
        return $out;
    }

    protected function numberToLetters(int $n): string
    {
        // 0 -> A, 25 -> Z, 26 -> AA
        $result = '';
        $n++; // make it 1-indexed
        while ($n > 0) {
            $n--; // adjust
            $result = chr(65 + ($n % 26)) . $result;
            $n = intval($n / 26);
        }
        return $result;
    }
}
