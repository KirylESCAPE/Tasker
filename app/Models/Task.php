<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'completed_at',
        'guest_id',
        'user_id'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Scope]
    protected function forUser(Builder $query, $user, ?string $guestId): void
    {
        $query->where(function($q) use ($user, $guestId) {
            if ($user) {
                $q->where('user_id', $user->id);
            } elseif ($guestId) {
                $q->where('guest_id', $guestId);
            }
        });
    }

    #[Scope]
    protected function search(Builder $query, ?string $search): void
    {
        $query->when($search, function (Builder $q, string $search) {
            $q->where(function (Builder $subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });
    }

    #[Scope]
    protected function status(Builder $query, string $status): void
    {
        match($status) {
            'active' => $query->where('is_completed', false),
            'completed' => $query->where('is_completed', true),
            default => null,
        };
    }

    private function complete(): self
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now()
        ]);

        return $this;
    }

    private function uncomplete(): self
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null
        ]);

        return $this;
    }

    public function toggleCompletion(): self
    {
        return $this->is_completed ? $this->uncomplete() : $this->complete();
    }
}
