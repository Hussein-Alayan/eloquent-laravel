<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    foreach (Agent::all() as @agent) {
        echo $agent->name;
    }


    $agents = Agent::
}
