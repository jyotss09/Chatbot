<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Lead;
use App\Models\Opportunity;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    public function processQuery(Request $request)
    {
        $query = $request->input('query');
        
        
        $response = $this->getAIResponse($query);

        if (str_contains($response, 'pending tasks')) {
            $tasks = Task::where('status', 'pending')->get(['title']);  // Only select 'title' field
            if ($tasks->isEmpty()) {
                return response()->json(['response' => 'No pending tasks found.']);
            }
            $taskList = $this->formatList($tasks->pluck('title')->toArray()); // Format tasks into numbered list
            return response()->json(['response' => 'Pending tasks: ' . $taskList]);
        } elseif (str_contains($response, 'latest leads')) {
            $leads = Lead::orderBy('created_at', 'desc')->get(['name', 'contact_info']);  
            if ($leads->isEmpty()) {
                return response()->json(['response' => 'No leads found.']);
            }
            $leadList = $this->formatList($leads->map(function($lead) {
                return "{$lead->name} (Contact: {$lead->contact_info})";
            })->toArray());
            return response()->json(['response' => 'Latest leads: ' . $leadList]);
        } elseif (str_contains($response, 'open opportunities')) {
            $opportunities = Opportunity::where('status', 'open')->get(['description', 'value']);  
            if ($opportunities->isEmpty()) {
                return response()->json(['response' => 'No open opportunities found.']);
            }
            $opportunityList = $this->formatList($opportunities->map(function($opportunity) {
                return "{$opportunity->description} (Value: \${$opportunity->value})";
            })->toArray());
            return response()->json(['response' => 'Open opportunities: ' . $opportunityList]);
        } else {
            return response()->json(['response' => 'Sorry, I didn\'t understand your request.']);
        }
    }

    private function getAIResponse($query)
    {
        if (str_contains(strtolower($query), 'pending tasks')) {
            return 'pending tasks';
        } elseif (str_contains(strtolower($query), 'latest leads')) {
            return 'latest leads';
        } elseif (str_contains(strtolower($query), 'open opportunities')) {
            return 'open opportunities';
        } else {
            return 'unknown query';
        }
    }

    private function formatList($items)
    {
        
        $formatted = '';
        foreach ($items as $index => $item) {
            $formatted .= ($index + 1) . ") " . $item . "\n";
        }
        return nl2br($formatted);  
    }
}
