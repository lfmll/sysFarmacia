<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        //Obtener punto de venta
        $puntoVenta = PuntoVenta::where('id',$data['punto_venta_id'])->first();
        if ($puntoVenta) {
            $user->puntosVenta()->attach($puntoVenta, ['estado' => 'A', 'fecha_asignacion' => Carbon::now('America/La_Paz')]);
        }
        return $user;
    }

    public function cargarPuntosVentaP(Request $request)
    {
        if ($request->ajax()) {
            $data = Agencia::find($request->agencia)->puntosVenta()->where('estado','A')->get(['id','nombre']);
            if ($data->isEmpty()) {
                return response()->json(["mensaje"=>"No existe puntos de ventas en esta Sucursal"],409);
            }
        }
        
        return response()->json($data);
    }
}
