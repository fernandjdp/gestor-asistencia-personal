<?php

namespace App\Http\Controllers\API;

use App\Estadistico;
use App\Registro;
use App\Registro_respaldo;
use App\Empleado;
use App\Horario;
use App\Permiso;
use App\Dia_festivo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstadisticoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Estadistico::latest()->get();
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
            'empleado_id' => 'max:191',
            'hora_entra' => 'max:191',
            'hora_sale' => 'max:191',   
            'ausencia' => 'max:191',
            'retardo' => 'max:191',
            'horas_extra' => 'max:191',
        ]);

        return Estadistico::create([
            'empleado_id' => $request['empleado_id'],
            'hora_entra' => $request['hora_entra'],
            'hora_sale' => $request['hora_sale'],
            'ausencia' => $request['ausencia'],
            'retardo' => $request['retardo'],
            'horas_extra' => $request['horas_extra'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Estadistico  $estadistico
     * @return \Illuminate\Http\Response
     */
    public function show(Estadistico $estadistico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Estadistico  $estadistico
     * @return \Illuminate\Http\Response
     */
    public function edit(Estadistico $estadistico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Estadistico  $estadistico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estadistico = Estadistico::findOrFail($id);

        $this->validate($request,[
            'empleado_id' => 'max:191',
            'hora_entra' => 'max:191',
            'hora_sale' => 'max:191',
            'ausencia' => 'max:191',
            'retardo' => 'max:191',
            'horas_extra' => 'max:191',
        ]);

        $estadistico->update($request->all());
        return ['message' => 'La información del estadistico ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Estadistico  $estadistico
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $estadistico = Estadistico::findOrFail($id);
        // delete the user

        $estadistico->delete();

        return ['message' => 'Estadistico Eliminado'];
    }

    public function BuscarPorFecha($fecha, $array)
    {
        $registros_del_dia = array();
        foreach ($array as $key => $value) {
           
            if ($value['date'] === $fecha)
            {
                array_push($registros_del_dia, $value);
            }

        }
        return $registros_del_dia;
    }

    public function cargarDiasLaborales()
    {
        $empleados = Empleado::all()->where('condicion_empleado', 1);
        $horarios = Horario::all();
        $dias_laborales_total =
        [
            "Lunes" => [], "Martes" => [], "Miercoles" => [],
            "Jueves" => [], "Viernes" => [], "Sabado" =>[],
            "Domingo" => []
        ];
        foreach ($empleados as $llave_empleados => $array_info_empleado) {
            $array_dia_laboral_empleado = unserialize(Horario::where('id',$array_info_empleado['horarios_id'])->first()['dias_laborables']);

            foreach ($array_dia_laboral_empleado as $llave_dias => $dia) {
                array_push($dias_laborales_total[$dia], $array_info_empleado['biometrico_id']);
            }
        }

        return $dias_laborales_total;
    }

    public function cargarDiasFestivos()
    {
        $dias_festivos = Dia_festivo::all();
        $dias_festivos_total = [];
        foreach ($dias_festivos as $llave_dia => $fecha) {
            array_push($dias_festivos_total, date("Y")."-".$fecha["fecha"]);
        }

        return $dias_festivos_total;
    }

    public function cargarRegistrosDias($registros, $dias_festivos)
    {
        //Esta funcion carga todos los registros ordenados por fecha, coloca en un array todas las fechas de los registros obtenidos y les resta las fechas de dias festivos obteniendo un array de dias laborables que se usaran para calcular las ausencias

        $rango_dias = [];
        foreach ($registros as $llave_registro => $info_registro) {
            $rango_dias[$info_registro['date']] = [];
        };
        //$rango_dias_unicos = array_unique($rango_dias);
        //$registros_dias_laborables = \array_diff($rango_dias_unicos, $dias_festivos);
        return $rango_dias;
    }

    public function cargarRegistrosFecha($registros, $registros_dias_laborables, $unico)
    {
        $registros_por_fecha = $registros_dias_laborables;
        $unique = [];

        foreach ($registros as $llave_registro => $info_registro) {
            array_push($registros_dias_laborables[$info_registro['date']], $info_registro['empleado_id']);
        };
        if ($unico) {

            foreach ($registros_dias_laborables as $fecha => $array_registros) {
                $unique = array_unique($registros_dias_laborables[$fecha]);

                foreach ($unique as $key => $value) {
                    array_push($registros_por_fecha[$fecha], (string)$value);
                    //return $registros_dias_laborables;
                }
            };
            
            return $registros_por_fecha; //Array = ["2020-06-23" => ["1", "203", "220"], "2020-06-24" => ["1", "230", "333"]] ect...
        }
        return $registros_dias_laborables; // Lo mismo pero sin registros unicos, mostrando check ins y outs
    }

    public function DiasSemanaEspañol($fecha)
    {
        //La función date("l", strotime($fecha)) devuelve perfectamente el día de la semana, pero en inglés, para usar el cálculo de ausencias bien se puede traducir estos días al español (No se puede traducir el arreglo en $dias_laborales_empleado debido a que estas están de acuerdo al arreglo de dias laborales en la tabla horarios.)

        // O bien puedo usar el número del día de la semana y traducirlo con su posición en un arreglo:

        $dias_de_semana = ["","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado", "Domingo"];
        $dia = date("N", strtotime($fecha));
        return ($dias_de_semana[$dia]);
    }
    public function cargarDiasPermiso($permisos)
    {
        $dias_permisos = [];
        $rango_dias_permisos = [];
        foreach ($permisos as $llave_permisos => $info_permisos) {
            array_push($dias_permisos, $info_permisos['fecha_inicial']);
            if ($info_permisos['fecha_final']) {
                array_push($dias_permisos, $info_permisos['fecha_final']);
            }
        }
        for ($i=strtotime(min($dias_permisos)); $i <= strtotime(max($dias_permisos)) ; $i+=86400) 
        { 
            $rango_dias_permisos[date("Y-m-d", $i)] = [];
        }
        return $rango_dias_permisos;
    }
    public function DiasDePermiso($permisos, $registros_dias_laborables)
    {
        $array_empleados_con_permisos = [];
        $rango_dias_permisos = $this->cargarDiasPermiso($permisos);
        foreach ($permisos as $llave_permisos => $info_permisos) {
            if ($info_permisos['fecha_inicial'] && $info_permisos['fecha_final']) 
            {
                for ($i=strtotime($info_permisos['fecha_inicial']); $i <= strtotime($info_permisos['fecha_final']) ; $i+=86400) 
                { 
                    $rango_dias_permisos[date("Y-m-d", $i)][$info_permisos['razon']] = $info_permisos['empleado_id'];
                }
            }
            else
            {
               $rango_dias_permisos[$info_permisos['fecha_inicial']][$info_permisos['razon']] = $info_permisos['empleado_id']; 
            }
        }
        // Las siguientes dos lineas lo que hacen es detectar las diferencias entre las fechas donde hubo registros en sus respectivos dias laborables y las fechas donde hubo permisos, de manera de que no existan registros de permisos en dias NO LABORABLES, ya que no hacen falta y generan errores
        $array_diferencias_permisos_registros = array_diff(array_keys($rango_dias_permisos), array_keys($registros_dias_laborables));
        foreach ($array_diferencias_permisos_registros as $key => $value) {
            unset($rango_dias_permisos[$value]);
        }

        return $rango_dias_permisos;
    }

    public function horarioPersonalConfiable($horario)
    {
        //Esta funcion devuelve un array con los datos de la hora de entrada y hora de salida en base de las tolerancias del horario para el personal confiable (personal que no marcará pero sus registros deben estar al día)
        $hora_entra = date('H:i:s', 
            mt_rand(
                strtotime($horario['tolerancia_entrada_antes']),
                strtotime($horario['tolerancia_entrada_despues'])
            )
        );
        $hora_sale = date('H:i:s', 
            mt_rand(
                strtotime($horario['hora_salida']),
                strtotime($horario['tolerancia_salida_horas_extra'])
            )
        );

        $array_horario = [$hora_entra, $hora_sale];
        return $array_horario;
    }   

    public function ausencias($dias_laborales_empleado, $registros_dias_ordenados, $registros_dias_laborables, $fechas_estadisticos_existentes)
    {
        //Por cada llave en $registros_dias_laborables, verificar qué dia de la semana es con date("I",$fecha) y comprarar ese día como llave de $dias_laborales_empleado, comparar la información de ambos, los que no aparezcan, colocarlos en otro array que serán los ausentes
        $empleados_confiables = Empleado::where('confianza',1)->select('id', 'biometrico_id', 'horarios_id')->get();
        $array_biometicoId_confiables = $empleados_confiables->pluck('biometrico_id')->toArray();
        $horarios_confiables = Horario::whereIn('id',$empleados_confiables->pluck('horarios_id'))->get();
        $array_ausentes = $registros_dias_laborables;
        foreach ($registros_dias_ordenados as $fecha => $array_empleados_presentes) 
        {
            // 2020-06-23

            $dia = $this->DiasSemanaEspañol($fecha);
            $ausencias = array_diff($dias_laborales_empleado[$dia], $registros_dias_ordenados[$fecha]);
                
            // $ausencias es un array, pero quiero colocar en $array_ausentes los valores del array $ausencias y no el array completo:

            foreach ($ausencias as $key => $value) {
                array_push($array_ausentes[$fecha], $value);
            }
        }
        
        if (Permiso::count()) 
        {
            $permisos = Permiso::all();
            $array_dias_permisos = $this->DiasDePermiso($permisos, $registros_dias_laborables);
            $array_post_permisos = $array_ausentes;
            foreach ($array_dias_permisos as $fecha => $array_permisos_id) 
            {
                foreach ($array_permisos_id as $key => $value) {
                    try 
                    {
                        if (in_array($value, $array_ausentes[$fecha]))
                        {
                            $llave = array_search($value, $array_ausentes[$fecha]);
                            unset($array_post_permisos[$fecha][$llave]);
                        }
                    } 
                    catch (\Throwable $t) 
                    {
                        echo "El error fue: ".$t->getMessage(). " Para el registro: ".$value;
                        continue;
                    }
                }
            };
            $data_permisos=[];
            foreach ($array_dias_permisos as $fecha => $array_permisos_id) 
            {
               if (in_array($fecha, $fechas_estadisticos_existentes) != TRUE) {
                    $dia = $this->DiasSemanaEspañol($fecha);
                    foreach ($array_permisos_id as $tipo_permiso => $empleado_id) {
                        $data_insertar_permisos = [
                            'empleado_id' => $empleado_id,
                            'fecha' => $fecha,
                            'dia' => $dia,
                            'estado_asistencia' => $tipo_permiso,
                            'hora_entra' => '00:00:00',
                            'hora_sale' => '00:00:00',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $data_permisos[] = $data_insertar_permisos;
                    }
                }
            }
            $data_permisos = collect($data_permisos);
            $chunks_permisos = $data_permisos->chunk(500);
            foreach ($chunks_permisos as $chunk) {
                Estadistico::insert($chunk->toArray());
            }
            
        }
        
        //Hasta AQUI todo bien

        $data_ausencias=[];
        $array_post_permisos = $array_ausentes;
        foreach ($array_post_permisos as $fecha => $array_ausentes_id) {
            if (in_array($fecha, $fechas_estadisticos_existentes) != TRUE) {
                $dia = $this->DiasSemanaEspañol($fecha);
                foreach ($array_ausentes_id as $key => $empleado_id) {
                    if (in_array($empleado_id, $array_biometicoId_confiables)) {
                        // Si el empleado es de confianza, entonces...
                        $horario = $this->horarioPersonalConfiable($horarios_confiables->where('id',$empleados_confiables->where('biometrico_id',$empleado_id)->first()['horarios_id'])->first());
                        /*
                            $horario = array[
                                0 => 'hora_entra'
                                1 => 'hora_sale'
                            ]
                        */

                        $data_insertar_ausencias = [
                            'empleado_id' => $empleado_id,
                            'fecha' => $fecha,
                            'dia' => $dia,
                            'estado_asistencia' => 'NORMAL',
                            'hora_entra' => $horario[0],
                            'hora_sale' => $horario[1],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    else{
                        $data_insertar_ausencias = [
                            'empleado_id' => $empleado_id,
                            'fecha' => $fecha,
                            'dia' => $dia,
                            'estado_asistencia' => 'FALTA INJUST',
                            'hora_entra' => '00:00:00',
                            'hora_sale' => '00:00:00',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    $data_ausencias[] = $data_insertar_ausencias;
                }
            }
        }
        $data_ausencias = collect($data_ausencias);
        $chunks_ausencias = $data_ausencias->chunk(500);
        foreach ($chunks_ausencias as $chunk) {
            Estadistico::insert($chunk->toArray());
        }
        return 'Exito';
    }

    public function estadisticos($registros, $dias_laborales_empleado, $registros_ordenados_fecha, $registros_dias_laborables, $fechas_estadisticos_existentes){
        set_time_limit(0);
        $empleados = Empleado::all();
        $horarios = Horario::all();
        $array_estadisticos = [];
        $data_estadistico = [];
        foreach ($registros_ordenados_fecha as $fecha => $array_registros) 
        {
            //Se asegura de que no hayan ya estadisticos en esa fecha (evitar estadisticos duplicados), solo por si acaso
            if (in_array($fecha, $fechas_estadisticos_existentes) != TRUE) 
            {
                foreach ($array_registros as $llave => $empleado_id) 
                {
                    try 
                    {
                        $horario_empleado_id = $empleados->where('biometrico_id', $empleado_id)->first()['horarios_id'];
                        $horario_empleado = $horarios->where('id', $horario_empleado_id)->first();
                        $entrada_horario = strtotime($horario_empleado['hora_entrada']);
                        $salida_horario = strtotime($horario_empleado['hora_salida']);

                        $entrada_registro_count = ($registros->where('empleado_id', $empleado_id)->where('in_out', 'Check In')->where('date', $fecha)->count());

                        $salida_registro_count = ($registros->where('empleado_id', $empleado_id)->where('in_out', 'Check Out')->where('date', $fecha)->count());

                        $entrada_registro = ($registros->where('empleado_id', $empleado_id)->where('in_out', 'Check In')->where('date', $fecha)->first());

                        $salida_registro = ($registros->where('empleado_id', $empleado_id)->where('in_out', 'Check Out')->where('date', $fecha)->last());

                        if ($entrada_registro_count > 1) 
                        {

                            // Realiza un conteo de registros, si hay dos repetidos: Calcula la diferencia entre el ultimo repetido y el horario de salida para escartar que haya marcado entrada a la hora de salida por accidente. Si la diferencia entre la hora en que marcó su entrada y la hora de salida en su horario es menor que 30 minutos, entonces es evidente el accidente. Se marca su hora de salida como corresponde corrigiendo su error.

                            $auxiliar_entrada_doble = ($registros->where('empleado_id', $empleado_id)->where('in_out', 'Check In')->where('date', $fecha)->last());
                            if (abs($salida_horario - strtotime($auxiliar_entrada_doble['time'])) < 1800) 
                            {
                                $salida_registro = $auxiliar_entrada_doble;
                            }

                        }

                        if ($salida_registro_count > 1) 
                        {
                            // Lo mismo que para entrada pero este caso es para salidas dobles

                            $auxiliar_salida_doble = ($registros->where('empleado_id', $empleado_id)->where('in_out', 'Check Out')->where('date', $fecha)->first());
                            if (abs($entrada_horario - strtotime($auxiliar_salida_doble['time'])) < 1800) {
                                $entrada_registro = $auxiliar_salida_doble;
                            }

                        }

                        if ($salida_horario < $entrada_horario) 
                        { 
                            // Que significa: Si la hora de salida es menor a la de la entrada-->(Turno Nocturno)

                            $fecha_dia_adicional = date('Y-m-d', strtotime($fecha) + 86400); //Le agrega un día a la fecha iterada

                            $salida_registro = ($registros->where('empleado_id', $empleado_id)->where('in_out', 'Check Out')->where('date', $fecha_dia_adicional)->first()); //En este caso la salida no será el mismo día, sino que la buscará en el día siguiente
                        }

                        ($entrada_registro ? $entrada_registro_time = strtotime($entrada_registro['time']) : $entrada_registro_time = null);

                        ($salida_registro ? $salida_registro_time = strtotime($salida_registro['time']) : $salida_registro_time = null);

                        $dia = $this->DiasSemanaEspañol($fecha);

                        /*$tabla_estadistico = new Estadistico();
                        $tabla_estadistico->empleado_id = $empleado_id;
                        $tabla_estadistico->fecha = $fecha;
                        $tabla_estadistico->dia = $dia;
                        ($entrada_registro ? $tabla_estadistico->hora_entra = $entrada_registro['time'] : $tabla_estadistico->hora_entra = null );
                        ($salida_registro ? $tabla_estadistico->hora_sale = $salida_registro['time'] : $tabla_estadistico->hora_sale = null );*/
                        ($entrada_registro ? $hora_entra = $entrada_registro['time'] : $hora_entra = null );
                        ($salida_registro ? $hora_sale = $salida_registro['time'] : $hora_sale = null );


                        switch (true) 
                        {
                            case $entrada_registro_time == true && $salida_registro_time == true:
                                $tolerancia_entrada_antes = strtotime($horario_empleado['tolerancia_entrada_antes']);
                                $tolerancia_entrada_despues = strtotime($horario_empleado['tolerancia_entrada_despues']);
                                $tolerancia_salida_antes = strtotime($horario_empleado['hora_salida']) - ($tolerancia_entrada_despues - strtotime($horario_empleado['hora_entrada']));
                                $tolerancia_salida_horas_extra = strtotime($horario_empleado['tolerancia_salida_horas_extra']);
                                $tolerancia_descanso_fin = strtotime($horario_empleado['tolerancia_descanso_fin']);

                                if ($tolerancia_entrada_antes <= $entrada_registro_time  && $entrada_registro_time <= $tolerancia_entrada_despues && $tolerancia_salida_antes <= $salida_registro_time && $salida_registro_time <= $tolerancia_salida_horas_extra  ) 
                                {
                                    $estado_asistencia = "NORMAL";
                                }
                                elseif ($entrada_registro_time < $tolerancia_entrada_antes) 
                                {

                                    $estado_asistencia = "ENTRADA ANTES";
                                }
                                elseif ($entrada_registro_time > $tolerancia_entrada_despues)
                                {

                                    $estado_asistencia = "ENTRADA TARDE";
                                }

                                elseif ($tolerancia_salida_antes > $salida_registro_time) 
                                {
                                    $estado_asistencia = "SALIDA ANTES";
                                }

                                elseif ($salida_registro_time > $tolerancia_salida_horas_extra) 
                                {
                                    $estado_asistencia = "HORAS EXTRA";
                                }
                                break;

                            case $entrada_registro_time == null && $salida_registro_time == true:
            
                                $estado_asistencia = "NO MARCO ENTRADA";
                                break;

                            case $entrada_registro_time == true && $salida_registro_time == null:
                                
                                $estado_asistencia = "NO MARCO SALIDA";
                                break;

                            default:
                                $estado_asistencia = "ERROR NO ESPECIFICADO";
                                break;
                        }
                        //$tabla_estadistico->save();
                        $data_estadistico[] = [
                            'empleado_id' => $empleado_id,
                            'fecha' => $fecha,
                            'dia' => $dia,
                            'estado_asistencia' => $estado_asistencia,
                            'hora_entra' => $hora_entra,
                            'hora_sale' => $hora_sale,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                            
                    } 
                    catch (\Throwable $t) 
                    {
                        echo "El error fue: ".$t->getMessage(). " Para el registro: ".$empleado_id;
                        continue;
                    }
                }
            }
        }
        $data_estadistico = collect($data_estadistico);
        $chunks_estadisticos = $data_estadistico->chunk(500);
        foreach ($chunks_estadisticos as $chunk) {
            Estadistico::insert($chunk->toArray());
        }
    }

    public function fechasEstadisticosExistentes()
    {
    
        $fechas_totales = [];
        $unique = [];
        $fechas_estadisticos_existentes = Estadistico::orderBy('fecha', 'ASC')->get(['fecha']);
        foreach ($fechas_estadisticos_existentes as $llave => $fecha) {
            array_push($unique, $fecha['fecha']);
        }

        $fechas_totales = array_unique($unique);
        return $fechas_totales;
    }

    public function calcular($tipo, $fecha_inicial, $fecha_final)
    {
        $desde = date_create($fecha_inicial);
        $hasta = date_create($fecha_final);
        $dias_laborales_empleado = $this->cargarDiasLaborales();
        $dias_festivos = $this->cargarDiasFestivos();
        $registros = Registro::oldest('date')
                                ->orderBy('time', 'ASC')
                                ->whereBetween('date', [$desde, $hasta])
                                ->get();
        $fechas_estadisticos_existentes = $this->fechasEstadisticosExistentes();

        $registros_dias_laborables = $this->cargarRegistrosDias($registros, $dias_festivos) ;
        $registros_ordenados_fecha = $this->cargarRegistrosFecha($registros, $registros_dias_laborables, True);
        $registros_ordenados_fecha_todos = $this->cargarRegistrosFecha($registros, $registros_dias_laborables, False);

        $empleados_presentes = [];

        // IF TIPO = ESTADISTICO

            switch (true) {
                case $tipo == "estadistico":
                    $this->ausencias($dias_laborales_empleado, $registros_ordenados_fecha, $registros_dias_laborables, $fechas_estadisticos_existentes);
                    $this->estadisticos($registros, $dias_laborales_empleado, $registros_ordenados_fecha, $registros_dias_laborables, $fechas_estadisticos_existentes);
                    break;
                case $tipo == "ausencia":
                    $this->ausencias($dias_laborales_empleado, $registros_ordenados_fecha, $registros_dias_laborables, $fechas_estadisticos_existentes);
                    break;
                
                default:
                    $this->ausencias($dias_laborales_empleado, $registros_ordenados_fecha, $registros_dias_laborables, $fechas_estadisticos_existentes);
                    $this->estadisticos($registros, $dias_laborales_empleado, $registros_ordenados_fecha, $registros_dias_laborables, $fechas_estadisticos_existentes);
                    break;
            }

        }
}

