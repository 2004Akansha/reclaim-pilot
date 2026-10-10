<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CaseScreenshot extends Model
{
    protected $fillable = [
        'recovery_case_id',
        'file_path',
        'original_name',
    ];

    public function recoveryCase(): BelongsTo
    {
        return $this->belongsTo(RecoveryCase::class);
    }

    public function analysis(): HasOne
    {
        return $this->hasOne(
            ScreenshotAnalysis::class
        );
    }
}