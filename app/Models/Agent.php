<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\MassPrunable;
use App\Models\Scopes\ActiveAgentScope;

// Introduction
// Generating Model Classes, Eloquent conventions, table & key setup, etc.

class Agent extends Model
{
    use HasFactory;
    // use SoftDeletes;    // Uncomment to enable soft deletes
    // use Prunable;       // Uncomment to enable pruning
    // use MassPrunable;   // Uncomment for mass pruning

    // protected $primaryKey = 'agent_uuid';
    // public $incrementing = false;
    // protected $keyType = 'string';
    // public $timestamps = false;
    // protected $connection = 'sqlite';

    protected $fillable = ['name', 'type', 'email', 'active'];
    protected $attributes = [
        'active' => true,
    ];

    protected static function booted(): void
    {
        // Global Scopes
        static::addGlobalScope(new ActiveAgentScope);
        static::addGlobalScope('recent', function (Builder $query) {
            $query->where('created_at', '>=', now()->subDays(30));
        });

        // Using Closures for Events (example)
        static::created(function (Agent $agent) {
            // ...
        });
    }

    // Local Scopes
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function bot(Builder $query): void
    {
        $query->where('type', 'Bot');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function ofType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function draft(Builder $query): void
    {
        $query->withAttributes(['active' => false]);
    }
}

// Retrieving Models
use App\Models\Agent;

$allAgents = Agent::all();

$activeAgents = Agent::where('active', true)
    ->orderBy('name')
    ->limit(5)
    ->get();

// Refreshing Models
$agent = Agent::find(1);
$freshAgent = $agent->fresh();

// Collections
$agents = Agent::all();
$activeOnly = $agents->reject(fn($a) => !$a->active);

// Chunking Results
Agent::chunk(100, function ($chunk) {
    foreach ($chunk as $agent) {
        echo $agent->name;
    }
});

// chunkById
Agent::where('active', true)
    ->chunkById(100, fn($chunk) => $chunk->each->update(['active' => false]));

// Lazy Collections
foreach (Agent::lazy() as $agent) {
    echo $agent->name;
}

// Cursors
foreach (Agent::cursor() as $agent) {
    echo $agent->name;
}

// Advanced Subqueries
use App\Models\AgentLog;
$agentsWithLastLogin = Agent::addSelect([
    'last_login' => AgentLog::select('logged_in_at')
        ->whereColumn('agent_id', 'agents.id')
        ->orderByDesc('logged_in_at')
        ->limit(1)
])->get();

// Inserting and Updating Models
// Inserts
$newAgent = new Agent;
$newAgent->name = 'Hussein';
$newAgent->email = 'hussein@example.com';
$newAgent->save();

$agent = Agent::create([
    'name'  => 'Hussein',
    'email' => 'hussein@example.com',
]);

// Updates
$agent = Agent::find(1);
$agent->email = 'updated@example.com';
$agent->save();

// Checking Changes
$agent = Agent::find(1);
$agent->name = 'New Name';
$dirty = $agent->isDirty();
$original = $agent->getOriginal('name');
$agent->save();
$wasChanged = $agent->wasChanged('name');

// Upserts
Agent::upsert([
    ['email' => 'a1@example.com', 'name' => 'Agent A'],
    ['email' => 'b1@example.com', 'name' => 'Agent B'],
], uniqueBy: ['email'], update: ['name']);

// Mass Assignment Protection
// In Agent model: protected $fillable = ['name','email',...];
// Agent::create(['name'=>'X','is_admin'=>true]); // is_admin ignored

// Quiet Saves (skip events)
$agent = Agent::find(1);
$agent->name = 'Silent';
$agent->saveQuietly();

// Deleting Models
// Single delete
Agent::find(1)->delete();
// Destroy by PK
Agent::destroy(1, 2, 3);
// Query delete
Agent::where('active', false)->delete();

// Soft Deletes (if trait enabled)
// $agent->delete();           // soft
// $agent->forceDelete();      // permanent
// Agent::withTrashed()->get();
// Agent::onlyTrashed()->get();

// Pruning Models (if trait enabled)
// In Agent: implement prunable() and optionally pruning()
// Schedule with `php artisan model:prune`

// Replicating Models
$original = Agent::find(1);
$copy = $original->replicate()->fill(['email'=>'copy@example.com']);
$copy->save();
