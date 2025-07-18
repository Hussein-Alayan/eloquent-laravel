<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ActiveAgentScope;

class Agent extends Model
{
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



protected static function booted(): void
{
    static::addGlobalScope(new ActiveAgentScope);

    static::addGlobalScope('recent', function ($query) {
    $query->where('created_at', '>=', now()->subDays(30));
}

}