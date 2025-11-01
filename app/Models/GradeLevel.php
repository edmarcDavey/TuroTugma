<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\SectionNamingService;

class GradeLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name','year','section_naming','section_naming_options','school_stage'];

    protected $casts = [
        'section_naming_options' => 'array',
    ];

    /**
     * Convenience wrapper to generate section names using the naming service.
     *
     * @param int $count
     * @param array $options
     * @return array
     */
    public function generateSectionNames(int $count, array $options = []): array
    {
        $service = app(SectionNamingService::class);
        return $service->generate($this, $count, $options);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_gradelevel');
    }

    public function sections()
    {
        return $this->hasMany(\App\Models\Section::class)->orderBy('ordinal');
    }
}
