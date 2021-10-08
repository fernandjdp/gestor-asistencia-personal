<?php

namespace App\Http\Controllers\API;

use App\Departamento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Departamento::latest()->paginate(10);
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
            'descripcion_departamento' => 'required|string|max:191',
        ]);

        return Departamento::create([
            'descripcion_departamento' => $request['descripcion_departamento'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function show(Departamento $departamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Departamento $departamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $departamento = Departamento::findOrFail($id);

        $this->validate($request,[
            'descripcion_departamento' => 'required|string|max:191'
        ]);

        $departamento->update($request->all());
        return ['message' => 'La información del departamento ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $departamento = Departamento::findOrFail($id);
        // delete the user

        $departamento->delete();

        return ['message' => 'Departamento Eliminado'];
    }

    public function buscar()
    {

        if ($search = \Request::get('q')) {
            $departamento = Departamento::where(function($query) use ($search){
                $query->where('descripcion_departamento','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $departamento = Departamento::latest()->paginate(5);
        }

        return $departamento;

    }
}
