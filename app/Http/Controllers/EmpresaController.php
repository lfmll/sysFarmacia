<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use App\Models\Ajuste;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Helpers\BitacoraHelper;
use App\Models\UserPuntoVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('setup.pendiente')->only(['create', 'store']);
        $this->middleware(['auth', 'admin'])->only(['edit', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa=new Empresa();
        return view('empresa.create',['empresa' => $empresa]);       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'admin_email'    => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
            'cover'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [], [
            'admin_email'    => 'correo del administrador',
            'admin_password' => 'contraseña del administrador',
            'cover'          => 'logo',
        ]);  
        
        $sinClaves = $request->except(['admin_password', 'admin_password_confirmation']);
 
        if ($validator->fails()) {
            return back()->withInput($sinClaves)->with('toast_error', $validator->errors()->first());
        }

        // URL de SIAT según el ambiente elegido
        $wsdl = config('siat.wsdl.' . $request->ambiente);
        if (empty($wsdl)) {
            return back()->withInput($sinClaves)->with('toast_error',
                'No hay una URL de SIAT configurada para ese ambiente. Revise config/siat.php y el archivo .env.');
        }

        try {
            DB::beginTransaction();
            $hasFile=$request->hasFile('cover') && $request->cover->isValid();
            $empresa= new Empresa($request->all());
            $empresa->nombre = $request->razon_social;
            $empresa->nit = $request->nit;
            $empresa->correo = $request->correo;
            if ($hasFile) {
                $extension=$request->cover->extension();
                $empresa->extension=$extension;
            }
            $empresa->sistema = $request->sistema;
            $empresa->codigo_sistema = $request->codigo_sistema;
            $empresa->version = $request->version;
            $empresa->modalidad = $request->modalidad;
            $empresa->ambiente = $request->ambiente;
            $empresa->estado = 'A';
            $empresa->save();
            $path = public_path('/imagen');
            if ($hasFile) {
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $request->cover->move('imagen',"$empresa->id.$extension");
            }                
            $sucursal = new Agencia;
            $sucursal->codigo = 0;
            $sucursal->nombre = "Casa Matriz";
            $sucursal->departamento = $request->departamento;
            $sucursal->municipio = $request->municipio;
            $sucursal->direccion = $request->direccion;
            $sucursal->telefono = $request->telefono;
            $sucursal->estado = 'A';
            $sucursal->empresa_id = $empresa->id;
            $sucursal->save();
                        
            $puntoventa = new PuntoVenta;
            $puntoventa->codigo = 0;
            $puntoventa->nombre = "Por defecto";            
            $puntoventa->agencia_id = $sucursal->id;
            $puntoventa->estado = "A";
            $puntoventa->save();                        

            // NUEVO: el primer usuario es el administrador
            $usuario = new User;
            $usuario->name = $request->admin_name;
            $usuario->email = $request->admin_email;
            $usuario->password = Hash::make($request->admin_password);
            $usuario->rol = 'admin';
            $usuario->save();
            
            $userPuntoVenta = new UserPuntoVenta;
            $userPuntoVenta->user_id = $usuario->id;
            $userPuntoVenta->punto_venta_id = $puntoventa->id;
            $userPuntoVenta->estado = 'A';
            $userPuntoVenta->fecha_asignacion = Carbon::now('America/La_Paz');
            $userPuntoVenta->save();

            $ajuste = new Ajuste;
            $ajuste->username = $request->correo;
            $ajuste->punto_venta_id = $puntoventa->id;
            $ajuste->token = $request->token;
            $ajuste->wsdl = $wsdl;
            $ajuste->save();

            DB::commit();

            // NUEVO: entra directo como administrador, ya ubicado en la matriz / punto de venta 0
            Auth::login($usuario);
            $request->session()->regenerate();
            session(['agencia_id' => $sucursal->id, 'punto_venta_id' => $puntoventa->id]);
            BitacoraHelper::registrar('Registro Inicial', 'Instalación inicial realizada por el usuario: ' . $usuario->name, 'Empresa');

            return redirect('/ajuste')->with('toast_success', 'Empresa registrada. Sincronice CUIS, CUFD y catálogos con SIAT para empezar a facturar.');                          
        } catch (\Exception $e) {          
            DB::rollBack();
            return redirect()->route('empresa.create')->with('toast_error',"Excepción: " . $e->getMessage());
        }           
                           
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        $empresa = Empresa::first();
        return view('empresa.edit',['empresa'=>$empresa]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $empresa = Empresa::first();

        $validator = Validator::make($request->all(), [
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [], ['cover' => 'logo']);
        if ($validator->fails()) {
            return back()->withInput()->with('toast_error', $validator->errors()->first());
        }
        
        $hasFile=$request->hasFile('cover') && $request->cover->isValid();
        $empresa->nombre = $request->nombre;
        $empresa->nit = $request->nit;
        $empresa->correo = $request->correo;
        if ($hasFile) {
            $extension=$request->cover->extension();
            $empresa->extension=$extension;
        }
        $empresa->sistema = $request->sistema;
        $empresa->codigo_sistema = $request->codigo_sistema;
        $empresa->version = $request->version;
        if ($hasFile) {
            $request->cover->move('imagen',"$empresa->id.$extension");
        }        
        Alert::warning('Actualizar Datos Empresa', 'Desea guardar los cambios?');

        if ($empresa->save()) {
            //Registrar Bitacora
            BitacoraHelper::registrar('Actualización Empresa', 'Empresa modificada por el usuario: ' . Auth::user()->name, 'Empresa');
            return view('empresa.edit',['empresa'=>$empresa])->with('toast_success','Datos de Empresa actualizado');
        } else {
            return view('empresa.edit',['empresa'=>$empresa])->with('toast_error',"Error al registrar");        
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
