<?php

namespace App\Http\Controllers\API;

use App\TipoPermiso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoPermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TipoPermiso::latest()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'descripcion_tipo_permiso' => 'required|string|max:191',
        ]);

        return TipoPermiso::create([
            'descripcion_tipo_permiso' => $request['descripcion_tipo_permiso'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoPermiso  $tipoPermiso
     * @return \Illuminate\Http\Response
     */
    public function show(TipoPermiso $tipoPermiso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoPermiso  $tipoPermiso
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoPermiso $tipoPermiso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoPermiso  $tipoPermiso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipo_permiso = TipoPermiso::findOrFail($id);

        $this->validate($request,[
            'descripcion_tipo_permiso' => 'required|string|max:191'
        ]);

        $tipo_permiso->update($request->all());
        return ['message' => 'La información del tipo de permiso ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipo_empleado  $tipo_permiso
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $tipo_permiso = TipoPermiso::findOrFail($id);
        // delete the user

        $tipo_permiso->delete();

        return ['message' => 'Tipo permiso Eliminado'];
    }
}
