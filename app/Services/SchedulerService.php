<?php

namespace App\Services;

/**
 * SchedulerService stub — Run Scheduler feature has been removed.
 * This minimal class remains to avoid class-not-found errors in code that
 * may still reference the service. Methods return safe defaults.
 */
class SchedulerService
{
    /**
     * Legacy: return an empty demo result indicating feature removed.
     */
    public function runQuick(): array
    {
        return ['mode' => 'removed', 'schedules' => [], 'conflicts' => []];
    }

    /**
     * Legacy: validateSchedules now returns the payload unchanged with a note.
     */
    public function validateSchedules(array $payload): array
    {
        return ['note' => 'Scheduler feature removed', 'schedules' => $payload['schedules'] ?? [], 'conflicts' => []];
    }
}
