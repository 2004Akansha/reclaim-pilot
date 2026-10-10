<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreenshotAnalysis extends Model
{
    protected $fillable = [
        'case_screenshot_id',
        'detected_issue',
        'visible_message',
        'possible_reason',
        'confidence',
        'raw_analysis',
    ];

    protected $casts = [
        'raw_analysis' => 'array',
    ];

    public function screenshot(): BelongsTo
    {
        return $this->belongsTo(
            CaseScreenshot::class,
            'case_screenshot_id'
        );
    }
}