<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evidence extends Model
{
    protected $table = 'evidence';

    protected $fillable = [
        'recovery_case_id',
        'title',
        'description',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function recoveryCase(): BelongsTo
    {
        return $this->belongsTo(RecoveryCase::class);
    }
}