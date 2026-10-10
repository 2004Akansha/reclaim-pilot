<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class RecoveryCase extends Model
{
    protected $fillable = [
        'user_id',
        'issue_type',
        'description',
        'username',
        'email',
        'account_type',
        'status',
        'possible_reason',
        'analysis_progress',
        'analysis_step',
    ];

    protected function casts(): array
    {
        return [
            'analysis_progress' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function screenshots(): HasMany
    {
        return $this->hasMany(CaseScreenshot::class);
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(Analysis::class);
    }

    public function appeals(): HasMany
    {
        return $this->hasMany(Appeal::class);
    }

     public function evidence(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }


}