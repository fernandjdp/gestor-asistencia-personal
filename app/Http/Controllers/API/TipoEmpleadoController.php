<?php

namespace App\Http\Controllers\API;

use App\Tipo_empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoEmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tipo_empleado::latest()->paginate(10);
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
            'descripcion_tipo_empleado' => 'required|string|max:191',
        ]);

        return Tipo_empleado::create([
            'descripcion_tipo_empleado' => $request['descripcion_tipo_empleado'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tipo_empleado  $tipo_empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Tipo_empleado $tipo_empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tipo_empleado  $tipo_empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipo_empleado $tipo_empleado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tipo_empleado  $tipo_empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipo_empleado = Tipo_empleado::findOrFail($id);

        $this->validate($request,[
            'descripcion_tipo_empleado' => 'required|string|max:191'
        ]);

        $tipo_empleado->update($request->all());
        return ['message' => 'La información del tipo de empleado ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipo_empleado  $tipo_empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $tipo_empleado = Tipo_empleado::findOrFail($id);
        // delete the user

        $tipo_empleado->delete();

        return ['message' => 'Tipo_empleado Eliminado'];
    }

    public function buscar()
    {

        if ($search = \Request::get('q')) {
            $tipo_empleado = Tipo_empleado::where(function($query) use ($search){
                $query->where('descripcion_tipo_empleado','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $tipo_empleado = Tipo_empleado::latest()->paginate(5);
        }

        return $tipo_empleado;

    }
}
