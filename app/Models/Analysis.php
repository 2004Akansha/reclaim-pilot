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
    ];

    protected $casts = [
        'confidence' => 'float',
    ];

    public function recoveryCase(): BelongsTo
    {
        return $this->belongsTo(RecoveryCase::class);
    }
}