<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Section;
use App\Models\GradeLevel;

class ClearSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sections:clear {--all : Delete sections for all grade levels} {--grades= : Comma-separated grade_level ids to delete} {--dry-run : Show what would be deleted without removing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete saved sections from the database. Use --all to remove all sections or --grades to target specific grade_level ids. Use --dry-run to preview.';

    public function handle()
    {
        $all = $this->option('all');
        $gradesOpt = $this->option('grades');
        $dry = $this->option('dry-run');

        if (!$all && !$gradesOpt) {
            $this->error('Specify either --all or --grades=<ids>');
            return 1;
        }

        $gradeIds = [];
        if ($gradesOpt) {
            $gradeIds = array_filter(array_map('trim', explode(',', $gradesOpt)));
        }

        if ($all) {
            $count = Section::count();
            $this->line("Found {$count} sections in total.");
            if ($dry) {
                $this->info('Dry run: no sections will be deleted.');
                return 0;
            }
            if (!$this->confirm('Delete ALL sections? This cannot be undone.')) return 1;
            Section::truncate();
            $this->info('All sections deleted.');
            return 0;
        }

        // delete by grade ids
        $toDelete = Section::whereIn('grade_level_id', $gradeIds)->get();
        $this->line('Sections matching grade_level ids: ' . implode(',', $gradeIds));
        $this->line('Count: ' . $toDelete->count());
        if ($dry) {
            $this->info('Dry run: no sections will be deleted.');
            return 0;
        }
        if (!$this->confirm('Delete these sections? This cannot be undone.')) return 1;
        $ids = $toDelete->pluck('id')->toArray();
        Section::whereIn('id', $ids)->delete();
        $this->info('Deleted ' . count($ids) . ' sections.');
        return 0;
    }
}
