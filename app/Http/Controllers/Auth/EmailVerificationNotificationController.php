<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\User;
use Inertia\Response;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): Mixed
    {
        $user = auth()->user();

        if ($user->is_active) {
            return response()->json(['message'=>'User is already active.']);
        }

        $now = Carbon::now();

        // Convert the Carbon instance to a Unix timestamp
        $timestamp = $now->timestamp;

        // Convert the Unix timestamp to a string using strtotime
        $created_at = strtotime($timestamp);
        $token = Common::createTokenHash($user->email, $created_at);
      
        // The sendEmailVerificationNotification function is overriden in the User model.
        $user->sendEmailVerificationNotificationFunction($token);

        //resend verification link
        return Inertia::render('Auth/VerifyEmail');
    }
}