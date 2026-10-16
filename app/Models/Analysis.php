<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analysis extends Model
{
    protected $fillable = [
        'recovery_case_id',
        'issue_detected',
        'possible_reason',
        'confidence',
        'summary',
        'recommended_action',
        'guidance',
        'guidance_source',
    ];

    protected $casts = [
        'confidence' => 'float',
        'guidance' => 'array',
    ];

    public function recoveryCase(): BelongsTo
    {
        return $this->belongsTo(RecoveryCase::class);
    }
}