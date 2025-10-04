<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;
use Illuminate\Support\Uri;
use Override;
use Stevebauman\Location\Drivers\Driver;
use Stevebauman\Location\Position;
use Stevebauman\Location\Request;

class GeoIpLocation extends Driver
{
    /**
     * Get a position from the request.
     */
    #[Override]
    public function get(Request $request): Position|false
    {
        return Cache::flexible(key: 'geoip-'.md5($request->getIp()), ttl: [15, 25], callback: function () use ($request) {
            $data = $this->process($request);

            $position = $this->makePosition();

            // Here we will ensure the location's data we received isn't empty.
            // Some IP location providers will return empty JSON. We want
            // to avoid this, so we can call the next fallback driver.
            if ($data instanceof Fluent && ! $this->isEmpty($data)) {
                $position = $this->hydrate($position, $data);

                $position->ip = $request->getIp();
                $position->driver = static::class;
            }

            if (! $position->isEmpty()) {
                return $position;
            }

            return $this->fallback ? $this->fallback->get($request) : false;
        });
    }

    /**
     * @throws ConnectionException
     */
    protected function process(Request $request): Fluent|false
    {
        $url = Uri::of('https://api.ipquery.io')
            ->withPath('/'.$request->getIp())
            ->withQuery(['format' => 'json']);
        $response = Http::get($url);

        return new Fluent($response->json());
    }

    protected function hydrate(Position $position, Fluent $location): Position
    {
        $position->isp = filled($location->isp['isp']) ? $location->isp['isp'] : null;
        $position->countryName = filled($location->location['country']) ? $location->location['country'] : null;
        $position->countryCode = filled($location->location['country_code']) ? $location->location['country_code'] : null;
        $position->cityName = filled($location->location['city']) ? $location->location['city'] : null;
        $position->timezone = filled($location->location['timezone']) ? $location->location['timezone'] : null;
        $position->latitude = filled($location->location['latitude']) ? $location->location['latitude'] : null;
        $position->longitude = filled($location->location['longitude']) ? $location->location['longitude'] : null;

        return $position;
    }
}
