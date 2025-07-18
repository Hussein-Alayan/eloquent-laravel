<?php

namespace App\Models;

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

