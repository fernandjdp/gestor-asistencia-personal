<?php

namespace App\Http\Controllers\API;

use App\Dia_festivo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiaFestivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Dia_festivo::latest()->paginate(10);
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
            'fecha' => 'required|max:191',
            'tipo' => 'required|string|max:191',
            'descripcion_dia_festivo' => 'required|string|max:191',
        ]);

        return Dia_festivo::create([
            'fecha' => $request['fecha'],
            'tipo' => $request['tipo'],
            'descripcion_dia_festivo' => $request['descripcion_dia_festivo'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dia_festivo  $dia_festivo
     * @return \Illuminate\Http\Response
     */
    public function show(Dia_festivo $dia_festivo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dia_festivo  $dia_festivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Dia_festivo $dia_festivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dia_festivo  $dia_festivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dia_festivo = Dia_festivo::findOrFail($id);

        $this->validate($request,[
            'descripcion_dia_festivo' => 'required|string|max:191',
            'tipo' => 'required|string|max:191',
            'descripcion_dia_festivo' => 'required|string|max:191',
        ]);

        $dia_festivo->update($request->all());
        return ['message' => 'La información del dia festivo ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dia_festivo  $dia_festivo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dia_festivo = Dia_festivo::findOrFail($id);
        // delete the user

        $dia_festivo->delete();

        return ['message' => 'Dia festivo Eliminado'];
    }

    public function buscar()
    {

        if ($search = \Request::get('q')) {
            $dia_festivo = Dia_festivo::where(function($query) use ($search){
                $query->where('descripcion_dia_festivo','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $dia_festivo = Dia_festivo::latest()->paginate(5);
        }

        return $dia_festivo;

    }
}
