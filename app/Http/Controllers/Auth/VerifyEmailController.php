<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Common;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyEmailController extends \App\Http\Controllers\Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return void
     */
    public function verify(Request $request)
    {
        auth()->logout();

        $current_token = Common::createTokenHash($request->segment(2), $request->segment(3));
        if ($current_token !== $request->segment(4)) {
            // If the current token does not match the token in the URL segment,
            // it indicates potential tampering or the use of fake data.
            abort(404);
        }

        // Get the created date of the account from the URL.
        $createdAt = Carbon::createFromTimestamp($request->segment(3));
        // Add the interval to the created date.
        $validityPeriod = $createdAt->add(new \DateInterval("P" . config('app.verification_interval_days', 2) . "D"));

        if (Carbon::now()->gte($validityPeriod)) {
            // Abort when the request exceeded the validity period.
            abort(404);
        }

        // Valid request
        User::where('email', $request->email)->firstOrFail()->markEmailAsVerified();
        return redirect()->route('login')->with('message', 'Your email has already been verified. Please log in to continue.');
    }
}
