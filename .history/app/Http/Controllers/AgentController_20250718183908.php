<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function showAll(){

    $agents = Agent:all();

    foreach (Agent::all() as $agents) {
    echo $agent->name;
}


}
