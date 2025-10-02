<?php

namespace App\Actions\Sessions;

use App\Services\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RetrieveWebUserSession
{
    public function handle(Request $request): Collection
    {
        return $this->userSessions($request);
    }

    private function userSessions(Request $request): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform'   => $agent->platform(),
                    'browser'    => $agent->browser(),
                ],
                'ip_address'        => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active'       => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });

    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param mixed $session
     *
     * @return Agent
     */
    private function createAgent(mixed $session): Agent
    {
        return tap(new Agent, fn (Agent $agent) => $agent->setUserAgent($session->user_agent));
    }
}
