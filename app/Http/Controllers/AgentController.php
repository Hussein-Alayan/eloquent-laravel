<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    // Show all agents
    public function showAll()
    {
        return Agent::all();
    }

    // Delete a single agent by ID
    public function deleteAgent($id = null)
    {
        if (!$id) {
            return response()->json(['message' => 'ID is required'], 400);
        }

        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $agent->delete();

        return response()->json(['message' => 'Agent deleted successfully']);
    }

    // Destroy multiple agents (accepts comma-separated IDs)
    public function destroyAgent($ids = null)
    {
        if (!$ids) {
            return response()->json(['message' => 'IDs are required'], 400);
        }

        $idArray = explode(',', $ids);
        Agent::destroy($idArray);

        return response()->json(['message' => 'Agents destroyed']);
    }

    // Copy an existing agent
    public function copyAgent(Request $request)
    {
        $original = Agent::find($request->input('id'));

        if (!$original) {
            return response()->json(['message' => 'Original agent not found'], 404);
        }

        $newAgent = $original->replicate()->fill([
            'name' => $request->input('name', $original->name . ' Copy'),
            'email' => $request->input('email', null),
        ]);

        $newAgent->save();

        return response()->json(['message' => 'Agent copied', 'agent' => $newAgent]);
    }
}
