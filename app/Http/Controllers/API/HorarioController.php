<?php

namespace App\Http\Controllers\API;

use App\Horario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Horario::latest()->paginate(10);
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
            'descripcion_horario' => 'required|string|max:191',
            'dias_laborables' => 'max:191',
            'dias_no_laborables' => 'max:191',
            'dias_operativos' => 'max:191',
            'dias_hrs' => 'max:191',
            'hora_entrada' => 'max:191',
            'hora_salida' => 'max:191',
            'inicio_descanso' => 'max:191',
            'fin_descanso' => 'max:191',
            'tolerancia_entrada_antes' => 'max:191',
            'tolerancia_entrada_despues' => 'max:191',
            'tolerancia_salida_horas_extra' => 'max:191',
            'tolerancia_descanso_fin' => 'max:191',

        ]);

        $dias_laborables_serializado = serialize($request['dias_laborables']);
        $dias_no_laborables_serializado = serialize($request['dias_no_laborables']);
        $dias_operativos_serializado = serialize($request['dias_operativos']);  

        return Horario::create([
            'descripcion_horario' => $request['descripcion_horario'],
            'dias_laborables' => $dias_laborables_serializado,
            'dias_no_laborables' => $dias_no_laborables_serializado,
            'dias_operativos' => $dias_operativos_serializado,
            'dias_hrs' => $request['dias_hrs'],
            'hora_entrada' => $request['hora_entrada'],
            'hora_salida' => $request['hora_salida'],
            'inicio_descanso' => $request['inicio_descanso'],
            'fin_descanso' => $request['fin_descanso'],
            'tolerancia_entrada_antes' => $request['tolerancia_entrada_antes'],
            'tolerancia_entrada_despues' => $request['tolerancia_entrada_despues'],
            'tolerancia_salida_horas_extra' => $request['tolerancia_salida_horas_extra'],
            'tolerancia_descanso_fin' => $request['tolerancia_descanso_fin']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $horario = Horario::find($id);

        $this->validate($request,[
            'descripcion_horario' => 'required|string|max:191',
            'dias_laborables' => 'max:191',
            'dias_no_laborables' => 'max:191',
            'dias_operativos' => 'max:191',
            'dias_hrs' => 'max:191',
            'hora_entrada' => 'required|time|max:191',
            'hora_salida' => 'required|time|max:191',
            'inicio_descanso' => 'required|time|max:191',
            'fin_descanso' => 'required|time|max:191',
            'tolerancia_entrada_antes' => 'required|time|max:191',
            'tolerancia_entrada_despues' => 'required|time|max:191',
            'tolerancia_salida_horas_extra' => 'required|time|max:191',
            'tolerancia_descanso_fin' => 'required|time|max:191',
        ]);

        //Debido a que hay datos que serializar, debemos hacerlo a mano...
        //$horario->update($request->all());

        $dias_laborables_serializado = serialize($request['dias_laborables']);
        $dias_no_laborables_serializado = serialize($request['dias_no_laborables']);
        $dias_operativos_serializado = serialize($request['dias_operativos']);

        $horario->descripcion_horario = $request['descripcion_horario'];
        $horario->dias_laborables = $dias_laborables_serializado;
        $horario->dias_no_laborables = $dias_no_laborables_serializado;
        $horario->dias_operativos = $dias_operativos_serializado;
        $horario->dias_hrs = $request['dias_hrs'];
        $horario->hora_entrada = $request['hora_entrada'];
        $horario->hora_salida = $request['hora_salida'];
        $horario->inicio_descanso = $request['inicio_descanso'];
        $horario->fin_descanso = $request['fin_descanso'];
        $horario->tolerancia_entrada_antes = $request['tolerancia_entrada_antes'];
        $horario->tolerancia_entrada_despues = $request['tolerancia_entrada_despues'];
        $horario->tolerancia_salida_horas_extra = $request['tolerancia_salida_horas_extra'];
        $horario->tolerancia_descanso_fin = $request['tolerancia_descanso_fin'];
        $horario->save();

        return ['message' => 'La información del horario ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $horario = Horario::findOrFail($id);
        // delete the user

        $horario->delete();

        return ['message' => 'Horario Eliminado'];
    }

    public function dias_serializados($id)
    {
        $horario = Horario::find($id);
        $array_dias = [
            'dias_laborables' => [], 
            'dias_no_laborables' => [],
            'dias_operativos' => []
        ];
        $array_dias['dias_laborables'] = unserialize($horario['dias_laborables']);
        $array_dias['dias_no_laborables'] = unserialize($horario['dias_no_laborables']);
        $array_dias['dias_operativos'] = unserialize($horario['dias_operativos']);

        return $array_dias;
    }

    public function buscar()
    {

        if ($search = \Request::get('q')) {
            $horario = Horario::where(function($query) use ($search){
                $query->where('descripcion_horario','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $horario = Horario::latest()->paginate(5);
        }

        return $horario;

    }
}
