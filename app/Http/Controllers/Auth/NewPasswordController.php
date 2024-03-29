<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8'
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

        $email = $request->session()->get('email');

        try{
            $update = User::where('email', $email)->update(['password' => Hash::make($request->input('password'))]);
            $request->session()->forget('email');
            $status=true;
        } catch(\Exception $e){
            $status=false;
        }

        if($status==true){
            Alert::success('Akun Berhasil Dipulihkan!', "Selamat, akun Anda berhasil dipulihkan! Silahkan masuk dengan kata sandi yang baru.");
        }

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == true
            ? redirect('/login')
            : back()->withErrors(['email' => __($status)]);
    }

    public function getDataUser(Request $request){
        $email = $request->session()->get('email');

        $user =  User::where('email', $email)->first();

        return view('auth.resetpassword')->with('user', $user);
    }
}
