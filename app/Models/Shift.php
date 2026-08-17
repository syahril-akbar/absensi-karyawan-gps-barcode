<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'days',
    ];

    protected $casts = [
        'days' => 'array',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Apakah shift berlaku pada hari tertentu (1=Senin s.d. 7=Minggu).
     * Kosong berarti berlaku setiap hari.
     */
    public function appliesOn(int $dayOfWeekIso): bool
    {
        return empty($this->days) || in_array($dayOfWeekIso, $this->days);
    }
}
