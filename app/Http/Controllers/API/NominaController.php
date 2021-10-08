<?php

namespace App\Http\Controllers\API;

use App\Nomina;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NominaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Nomina::latest()->paginate(10);
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
            'descripcion_nomina' => 'required|string|max:191',
        ]);

        return Nomina::create([
            'descripcion_nomina' => $request['descripcion_nomina'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nomina  $nomina
     * @return \Illuminate\Http\Response
     */
    public function show(Nomina $nomina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nomina  $nomina
     * @return \Illuminate\Http\Response
     */
    public function edit(Nomina $nomina)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nomina  $nomina
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $nomina = Nomina::findOrFail($id);

        $this->validate($request,[
            'descripcion_nomina' => 'required|string|max:191'
        ]);

        $nomina->update($request->all());
        return ['message' => 'La información del nomina ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nomina  $nomina
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $nomina = Nomina::findOrFail($id);
        // delete the user

        $nomina->delete();

        return ['message' => 'Nomina Eliminado'];
    }

    public function buscar()
    {

        if ($search = \Request::get('q')) {
            $nomina = Nomina::where(function($query) use ($search){
                $query->where('descripcion_nomina','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $nomina = Nomina::latest()->paginate(5);
        }

        return $nomina;

    }
}
