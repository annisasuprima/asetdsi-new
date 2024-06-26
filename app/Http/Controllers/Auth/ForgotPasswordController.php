<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\admin;
use App\Models\Mahasiswa;
use App\Models\PersonInCharge;
use Error;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    public function showForgetPasswordForm()

    {

        return view('auth.passwords.recover-pw');
    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function submitForgetPasswordForm(Request $request)

    {

        // $request->validate([

        //     'email' => 'required|email|exists:admins',



        // ]);

        // $validatedData = $request->validate([
        //     'email' => 'exists:App\Models\Admin,email'
        // ]);
       $cek= admin::firstWhere('email', $request->email);
        if(!$cek){
           $cek = PersonInCharge::firstWhere('email', $request->email);
           if(!$cek){
            $cek = Mahasiswa::firstWhere('email', $request->email);
           }
        }
        if(!$cek){
            return back()->with(['message' => '"Email tidak ada"',
        'status' => '"error"']);

        }
    

        $token = Str::random(64);


        DB::table('password_resets')->insert([


            'email' => $request->email,

            'token' => $token,

            'created_at' => Carbon::now()

        ]);


        Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {

            $message->to($request->email);

            $message->subject('Reset Password');
        });



        return back()->with([
            'message' => '"Reset Password Sudah Terkirim Ke Email! Silahkan Cek Email"',
        'status' => '"success"'
    ]);
    }

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function showResetPasswordForm($token)
    {

        return view('auth.passwords.forgetPasswordLink', ['token' => $token]);
    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function submitResetPasswordForm(Request $request)

    {

        $request->validate([

            'password' => 'required|string|min:6|confirmed',

            'password_confirmation' => 'required'

        ]);



        $updatePassword = DB::table('password_resets')

            ->where([
                'token' => $request->token

            ])

            ->first();



        if (!$updatePassword) {

            return back()->withInput()->with('error', 'Invalid token!');
        }



        $user = admin::where('email', $updatePassword->email)->get();
        $pj_user = PersonInCharge::where('email', $updatePassword->email)->get();
        $mahasiswa = Mahasiswa::where('email', $updatePassword->email)->get();

        if ($user) {

            $user = admin::where('email', $updatePassword->email)
                ->update(['password' => bcrypt($request->password)]);
        }

        if ($pj_user) {
            $pj_user = PersonInCharge::where('email', $updatePassword->email)
                ->update(['password' => bcrypt($request->password)]);
        }

        if ($mahasiswa) {
            $mahasiswa = Mahasiswa::where('email', $updatePassword->email)
                ->update(['password' => bcrypt($request->password)]);
        }

        if ($user) {

            DB::table('password_resets')->where(['email' => $request->email])->delete();

            return redirect('login')->with('message', 'Your password has been changed!');
        }

        if ($pj_user) {

            DB::table('password_resets')->where(['email' => $request->email])->delete();

            return redirect('login')->with('message', 'Your password has been changed!');
        }

        if ($mahasiswa) {

            DB::table('password_resets')->where(['email' => $request->email])->delete();

            return redirect('login')->with('message', 'Your password has been changed!');
        }




        return back()->withInput()->with('error', 'Something wrong!');
    }


    // function request(){
    //     return view('auth.passwords.recover-pw');

    // }

    use SendsPasswordResetEmails;
}
