<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Models\Scopes\ActiveAgentScope;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'active'];

    /**
     * Apply global scopes when the model boots.
     */
    protected static function booted(): void
    {
        // Class-based global scope: always get only active agents
        static::addGlobalScope(new ActiveAgentScope);

        // Anonymous global scope: only get agents created in the last 30 days
        static::addGlobalScope('recent', function (Builder $query) {
            $query->where('created_at', '>=', now()->subDays(30));
        });
    }

    /**
     * Local scope: only return agents with type 'Bot'.
     */
    #[Scope]
    protected function bot(Builder $query): void
    {
        $query->where('type', 'Bot');
    }

    /**
     * Dynamic local scope: filter agents by a given type.
     */
    #[Scope]
    protected function ofType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    /**
     * Local scope with default attributes: create agents with active = false.
     */
    #[Scope]
    protected function draft(Builder $query): void
    {
        $query->withAttributes([
            'active' => false,
        ]);
    }
}

    /** @use HasFactory<\Database\Factories\AgentFactory> */
    use HasFactory;
    // protected $primaryKey = 'agent_uuid;
    //public $incrementing = false;
    //protected $keyType = 'string;

    //to disable timestamps 
    //public $timestamp = false;

    //point to another connection 
    //protected $connection = 'sqlite;


    protected $attributes = [
        'active' => true,
    ];

