<?php

namespace App\Http\Controllers\API;

use App\Cargo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cargo::latest()->paginate(10);
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
            'descripcion_cargo' => 'required|string|max:191',
        ]);

        return Cargo::create([
            'descripcion_cargo' => $request['descripcion_cargo'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Departamento  $cargo
     * @return \Illuminate\Http\Response
     */
    public function show(Departamento $cargo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Departamento  $cargo
     * @return \Illuminate\Http\Response
     */
    public function edit(Departamento $cargo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Departamento  $cargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cargo = Cargo::findOrFail($id);

        $this->validate($request,[
            'descripcion_cargo' => 'required|string|max:191'
        ]);

        $cargo->update($request->all());
        return ['message' => 'La información del cargo ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Departamento  $cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $cargo = Cargo::findOrFail($id);
        // delete the user

        $cargo->delete();

        return ['message' => 'Departamento Eliminado'];
    }

    public function buscar()
    {

        if ($search = \Request::get('q')) {
            $cargo = Cargo::where(function($query) use ($search){
                $query->where('descripcion_cargo','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $cargo = Cargo::latest()->paginate(5);
        }

        return $cargo;

    }
}
