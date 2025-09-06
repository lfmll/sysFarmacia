<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $ventas = Venta::select(DB::raw("COUNT(*) as count"),
                                DB::raw("MONTHNAME(fecha_venta)as month_name"))
                                ->where('estado','A')
                                ->whereYear('created_at',date('Y'))
                                ->groupBy(DB::raw("month_name"))
                                ->orderBy('id','ASC')
                                ->pluck('count','month_name');
                        
        $labels = ["ENE","FEB","MAR","ABR",
                    "MAY","JUN","JUL","AGO",
                    "SEP","OCT","NOV","DIC"];
        
        $datas = $ventas->values();                                                     

        if (Auth::check()) {
            return view('admin.home', compact('labels','datas'));
        }else {
            return view('auth.login');
        }
    }
}
