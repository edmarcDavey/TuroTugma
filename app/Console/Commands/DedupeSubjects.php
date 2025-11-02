<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subject;
use App\Models\GradeLevel;
use Illuminate\Support\Str;

class DedupeSubjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subjects:dedupe {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deduplicate Subject records by case-insensitive name, merge grade assignments, and remove duplicates.';

    public function handle()
    {
        $this->info('Scanning subjects for duplicates...');

        // Normalize/remove legacy classification tags if present
        $legacyTypes = ['junior_core','senior_core','shs_core'];
        $legacyCount = Subject::whereIn('type', $legacyTypes)->count();
        if($legacyCount){
            $this->line("Found {$legacyCount} subjects with legacy types: " . implode(', ', $legacyTypes));
            if(!$this->option('dry-run')){
                Subject::whereIn('type', $legacyTypes)->update(['type' => null]);
                $this->line('Cleared legacy type values.');
            }
        }

        $groups = Subject::all()->groupBy(function($s){ return Str::lower(trim($s->name)); });
        $totalMerged = 0;
        foreach($groups as $nameKey => $group){
            if($group->count() <= 1) continue;

            $this->line("Found duplicates for: {$nameKey} ({$group->count()})");
            // choose the earliest created (smallest id) as keeper
            $keeper = $group->sortBy('id')->first();
            $others = $group->where('id', '!=', $keeper->id);

            // collect all grade level ids
            $allGradeIds = $group->flatMap(function($s){ return $s->gradeLevels->pluck('id'); })->unique()->values()->toArray();

            $this->line(" - Keeper: {$keeper->id} ({$keeper->name}) will receive " . count($allGradeIds) . " grade assignments");

            if(!$this->option('dry-run')){
                // sync keeper
                $keeper->gradeLevels()->sync($allGradeIds);
                // delete others
                foreach($others as $o){
                    $this->line("   - Deleting duplicate id {$o->id}");
                    $o->gradeLevels()->detach();
                    $o->delete();
                    $totalMerged++;
                }
            }
        }

        $this->info('Done. Duplicates merged: ' . $totalMerged . ($this->option('dry-run') ? ' (dry-run)' : ''));
        return 0;
    }
}
