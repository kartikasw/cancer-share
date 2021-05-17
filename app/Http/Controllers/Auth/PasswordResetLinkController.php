<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'pulihan' => 'required|email',
        ]);
        
        $user = Password::getUser($request->only('email'));

        if (is_null($user)) {
            return back()->withInput($request->only('email'))->withErrors(['email' => __('passwords.user')]);
        }

        try{
            $details =
                [
                    'nama' => $request->user()->firstname,
                ];

            Mail::to($request->pulihan)->send(new PasswordMail($details));
            $status=true;
        }
        catch(\Exception $e){
            $status=false;
        }

        return back();
    }
}
