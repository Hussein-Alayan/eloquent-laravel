<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
