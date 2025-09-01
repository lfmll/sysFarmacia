<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\Agencia;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout','guardarUbicacion');
    }

    public function authenticated()
    {
        $sucursales = Agencia::where('estado', 'A')
            ->whereHas('puntosVenta', function ($q) {
                $q->whereHas('usuarios', function ($q2){
                    $q2->where('user_id', Auth::user()->id)
                       ->where('estado', 'A');
                });
            })->get();

        return view('auth.loginUbicacion')->with('sucursales', $sucursales);
    }

    public function guardarUbicacion(Request $request)
    {
        $request->validate([
            'agencia_id' => 'required|exists:agencias,id',
            'punto_venta_id' => 'required|exists:punto_ventas,id',
        ]);

        session([
            'agencia_id' => $request->agencia_id, 
            'punto_venta_id' => $request->punto_venta_id,
        ]);

        return redirect('/');
    }
}
