<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;

abstract class BaseAgentController extends Controller
{
    protected function agent(): Agent
    {
        $agent = auth()->user()->agent;
        abort_unless($agent, 403, 'Agent profile not found.');

        return $agent;
    }

    /** Write action (quote, message, report) er jonno active agent lagbe */
    protected function operational(): Agent
    {
        $agent = $this->agent();
        abort_unless($agent->isOperational(), 403, 'Your agent account is not active.');

        return $agent;
    }
}