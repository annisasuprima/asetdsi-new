<?php

namespace App\Exceptions;
use Illuminate\Auth\AuthenticationException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }   


    protected function unauthenticated($request,
        AuthenticationException $exception)
    {
        if($request->expectsJson()){
            return response()->json(['error'=>'Unauthenticated.'],
                401);
        }
        $guard = $exception->guards()[0];
        switch ($guard) {
            case 'person_in_charge':
                return redirect()->guest(route('pj-aset.show'));
                break;
            case 'dosen':
            return redirect()->guest(route('signindosen'));
            break;
            
            default:
                 return redirect()->guest(route('login'));
                break;
        }
    }
}
