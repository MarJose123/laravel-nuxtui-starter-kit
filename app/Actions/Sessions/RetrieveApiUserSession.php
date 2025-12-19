<?php

namespace App\Actions\Sessions;

use App\Services\Agent;
use Composer\InstalledVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Fluent;
use Laravel\Sanctum\Sanctum;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

class RetrieveApiUserSession
{
    public function handle(Request $request): Collection
    {
        return $this->userSessions($request);
    }

    /**
     * Get the user sessions from the database.
     *
     * @param Request $request
     *
     * @return Collection<int, Fluent>
     */
    private function userSessions(Request $request): Collection
    {
        if (! InstalledVersions::isInstalled('laravel/sanctum')) {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(resolve(Sanctum::personalAccessTokenModel())->getTable())
                ->where('tokenable_id', $request->user()->getAuthIdentifier())
                ->latest('last_used_at')
                ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);
            /** @var Position $location */
            $location = Location::get($session->ip_address);

            return new Fluent([
                'agent' => [
                    'is_desktop'   => $agent->isDesktop(),
                    'platform'     => $agent->platform(),
                    'browser'      => $agent->browser(),
                    'country'      => $location->countryName,
                    'country_code' => $location->countryCode,
                    'city'         => $location->cityName,
                    /** @phpstan-ignore property.notFound */
                    'isp'          => $location->isp,
                    'timezone'     => $location->timezone,
                    'latitude'     => $location->latitude,
                    'longitude'    => $location->longitude,

                ],
                'id'                => $session->id,
                'name'              => $session->name,
                'ip_address'        => $session->ip_address,
                'is_current_device' => $session->id === $request->user()?->currentAccessToken()?->id,
                'last_used'         => $session->last_used_at ? Date::parse($session->last_used_at)->diffForHumans() : 'Never',
            ]);
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
        return tap(new Agent, fn (Agent $agent): string => $agent->setUserAgent($session->user_agent));
    }
}
