<?php

namespace App\Actions\Sessions;

use DB;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

class DeleteUserSessions
{
    /**
     * @throws AuthenticationException
     */
    public function handle(Request $request, StatefulGuard $guard): void
    {

        $guard->logoutOtherDevices($request->input('password'));

        $this->deleteOtherSessionRecords($request);

    }

    /**
     * Delete the other browser session records from storage.
     *
     * @param Request $request
     *
     * @return void
     */
    private function deleteOtherSessionRecords(Request $request): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }
}
