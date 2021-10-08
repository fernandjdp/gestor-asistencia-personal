<?php

namespace App\Http\Controllers\API;

use App\Permiso;
use App\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Permiso::latest()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $this->validate($request,[
            'empleado_id' => 'required',
            'razon' => 'required|string|max:191',
            'fecha_inicial' => 'required',
        ]);

       if ($request['fecha_final']) 
       {
           $this->validate($request,[
            'fecha_final' => 'required',
        ]);
       }

        $permiso = new Permiso();
        $permiso->empleado_id = $request['empleado_id'];
        $permiso->nombre_empleado = Empleado::where('biometrico_id',$request['empleado_id'])->first()['nombre_completo'];
        $permiso->razon = $request['razon'];
        $permiso->fecha_inicial = $request['fecha_inicial'];

        if ($request['fecha_final']) {
            $permiso->fecha_final = $request['fecha_final'];
        }

        $permiso->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permiso  $permiso
     * @return \Illuminate\Http\Response
     */
    public function show(Permiso $permiso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permiso  $permiso
     * @return \Illuminate\Http\Response
     */
    public function edit(Permiso $permiso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permiso  $permiso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);

        $this->validate($request,[
            'empleado_id' => 'required',
            'razon' => 'required|string|max:191',
            'fecha_inicial' => 'required',
        ]);

       if ($request['fecha_final']) 
       {
           $this->validate($request,[
            'fecha_final' => 'required',
        ]);
       }

        $permiso->update($request->all());
        return ['message' => 'La información del tipo de permiso ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipo_empleado  $permiso
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $permiso = Permiso::findOrFail($id);
        // delete the user

        $permiso->delete();

        return ['message' => 'Tipo permiso Eliminado'];
    }
}
