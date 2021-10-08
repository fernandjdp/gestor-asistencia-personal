<?php

namespace App\Http\Controllers\API;

use App\Reporte;
use App\Empleado;
use App\Cargo;
use App\Departamento;
use App\Empresa;
use App\Horario;
use App\TipoPermiso;
use App\Tipo_empleado;
use App\Estadistico;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Reporte::latest()->paginate(10);
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
            'usuario' => 'required|string|max:191',
            'clasificacion_reporte' => 'required|max:191',
            'tipo_reporte' => 'required|max:191',
            'amplitud_reporte' => 'required|string|max:191',
            'ordenar_por' => 'required|string|max:191',
            'fecha_inicial' => 'required|max:191',
            'fecha_final' => 'required|max:191',
        ]);

        $reporte = [
            'usuario' => $request['usuario'],
            'clasificacion_reporte' => $request['clasificacion_reporte'],
            'tipo_reporte' => $request['tipo_reporte'],
            'amplitud_reporte' => $request['amplitud_reporte'],
            'ordenar_por' => $request['ordenar_por'],
            'fecha_inicial' => $request['fecha_inicial'],
            'fecha_final' => $request['fecha_final'],
        ];
        return dd($reporte);
        Reporte::insert($reporte);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function show(Reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function edit(Reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reporte $reporte)
    {
        //
    }

    public function usuario()
    {
        return auth('api')->user()->name;
    }

    public function cargarRegistrosDias($estadistico)
    {
        //Agarra todas las estadisticas y crea un array vacio con cada empleado_id como llave
        $array_empleados_id = [];
        foreach ($estadistico as $llave => $info) {
            $array_empleados_id[$info['empleado_id']] = [];
        };

        return $array_empleados_id; // array = [57 => [], 68 => [], etc]
    }

    public function cargarInfoEmpleados()
    {   
        set_time_limit(0);
        $estadistico = Estadistico::orderBy('fecha', 'ASC')->select('empleado_id')->get();
        $tipo_empleado_lista = Tipo_empleado::all();
        $cargo = Cargo::all();
        $departamento = Departamento::all();
        $empleados = Empleado::select('id','biometrico_id','cedula','nombre_completo','cargos_id','departamentos_id','tipo_empleados_id')->get();
        $array_info_empleados_id = $this->cargarRegistrosDias($estadistico);
        $array_info_empleados_id_unique = $array_info_empleados_id;

        $lista_empleados_con_estadisticos = $estadistico->pluck('empleado_id')->toArray();
        $lista_empleados_con_estadisticos = array_unique($lista_empleados_con_estadisticos);
        foreach ($lista_empleados_con_estadisticos as $llave => $info) 
        {
            //return dd($info);
            try 
            {   
                $biometrico_id = $info;
                $info_empleado = $empleados->where('biometrico_id', $biometrico_id)->first();
                $cedula = $info_empleado['cedula'];
                $nombre = $info_empleado['nombre_completo'];   
                $cargo_empleado = $cargo->where('id', $info_empleado['cargos_id'])->first()['descripcion_cargo'];
                $departamento_empleado = $departamento->where('id', $info_empleado['departamentos_id'])->first()['descripcion_departamento']; 
                $tipo_empleado = $tipo_empleado_lista->where('id', $info_empleado['tipo_empleados_id'])->first()['descripcion_tipo_empleado'];

                $array_info_empleados_id[$biometrico_id] = [
                    $biometrico_id,
                    $cedula,
                    $nombre,
                    $cargo_empleado,
                    $departamento_empleado,
                    $tipo_empleado
                    ]; 
            }


            catch (\Throwable $t) {
                echo "El error fue: ".$t->getMessage(). " Para el registro: ".$info;
                continue;
            
            }
        }
        
        return $array_info_empleados_id; // array = [800 => [id, cedula, nombre...]]
    }

    public function empleadoPorDepartamentos($departamentos_id)
    {
        $array_empleados_departamentos = [];
        $empleados = Empleado::where('departamentos_id',$departamentos_id)->get();
        foreach ($empleados as $llave => $array_info) {
            array_push($array_empleados_departamentos, $array_info['biometrico_id']);
        }

        return $array_empleados_departamentos;

    }

    public function empleadoPorTipoEmpleado($tipo_empleados_id)
    {
        $array_empleados_tipo = [];
        $empleados = Empleado::where('tipo_empleados_id',$tipo_empleados_id)->get();
        foreach ($empleados as $llave => $array_info) {
            array_push($array_empleados_tipo, $array_info['biometrico_id']);
        }

        return $array_empleados_tipo;

    }

    public function cargarDepartamentos($departamento)
    {
        $lista_departamentos_id = [];
        foreach ($departamento as $key => $value) {
            $lista_departamentos_id[$value['id']] = [];
        }
        return $lista_departamentos_id;
        /*
        *   El array es como el siguiente:
        *   array:2 [      
        *   "1" => []
        *   "2" => []
        *   ]
        */
    }

    public function ordenarEstadisticosPorDepartamento($lista_departamentos_id)
    {
        //Lo que HARÁ esta función es extraer la llave de cada item en lista_departamentos_id y ponerlo en un array
        //Por cada item en ese array, array[item] = departamento_id de ese item.
        // Un sort sencillo de php de ordenar array por su valor y listo.

        $array_auxiliar = [];
        $lista_ordenada_por_departamentos = [];
        foreach ($lista_departamentos_id as $departamento_id => $array_empleados_id) {
            $array_auxiliar[$departamento_id] = array_unique($lista_departamentos_id[$departamento_id]);
        }
        /* array_auxiliar = [
            departamento_id => [
                id_Array => "empleado_id"
            ]
        ]
        */
       return $array_auxiliar;
    }

    public function ordenarPor($array_empleados_por_departamentos, $ordenar_por, $empleado)
    {
        $array_auxiliar = $array_ordenado = $array_empleados_por_departamentos;
        $lista_ordenada = [];
        switch ($ordenar_por) {
            case 'biometrico_id':
                foreach ($array_empleados_por_departamentos as $key => $value) {               
                    uasort($array_ordenado[$key], function($a, $b)
                    {
                        if ($a == $b) {
                            return 0;
                        }
                        return ($a < $b) ? -1 : 1;
                    });
                }
                // ($array_ordenado);
                break;
            case 'cedula':
                foreach ($array_empleados_por_departamentos as $departamento_id => $array_empleados_id) 
                {
                    foreach ($array_empleados_id as $key => $value) 
                    {
                        $array_ordenado[$departamento_id][$key] = $empleado->where('biometrico_id',$value)->first()['cedula'];
                    }

                    uasort($array_ordenado[$departamento_id], function($a, $b)
                    {
                        return $a <=> $b;
                    });

                }
                foreach ($array_ordenado as $departamento_id => $array_empleados_id) {
                    foreach ($array_empleados_id as $key => $value) {
                        $array_ordenado[$departamento_id][$key] = $empleado->where('cedula',$value)->first()['biometrico_id'];
                    }
                }
                break;
            case 'nombre_completo':
                foreach ($array_empleados_por_departamentos as $departamento_id => $array_empleados_id) 
                {
                    foreach ($array_empleados_id as $key => $value) 
                    {
                        $array_ordenado[$departamento_id][$key] = $empleado->where('biometrico_id',$value)->first()['nombre_completo'];
                    }

                    uasort($array_ordenado[$departamento_id], function($a, $b)
                    {
                        return $a <=> $b;
                    });

                }
                foreach ($array_ordenado as $departamento_id => $array_empleados_id) {
                    foreach ($array_empleados_id as $key => $value) {
                        $array_ordenado[$departamento_id][$key] = $empleado->where('nombre_completo',$value)->first()['biometrico_id'];
                    }
                }
                break;
            default:
                # code...
                break;
        }
        foreach ($array_ordenado as $departamento_id => $array_empleados_id_unicos) {
            foreach ($array_empleados_id_unicos as $key => $value) {
                array_push($lista_ordenada, $value);
            }
        }
        return $lista_ordenada;
    }

    public function crear_reporte($usuario, $clasificacion_reporte, $tipo_reporte, $id_tipo_reporte, $clasificar_por, $id_clasificar_por, $amplitud_reporte, $ordenar_por, $fecha_inicial, $fecha_final)
    {   
        set_time_limit(0);
        $estadistico_completo = Estadistico::oldest('fecha')
                                ->orderBy('empleado_id', 'ASC')
                                ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                                ->get();
        $estadistico_completo_ticket = Estadistico::whereRaw('hora_sale-hora_entra >= 6')
                                ->whereIn('estado_asistencia', array("NORMAL","ENTRADA ANTES", "ENTRADA TARDE"))
                                ->oldest('fecha')
                                ->orderBy('empleado_id', 'ASC')
                                ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                                ->get();
        $lista_permisos = TipoPermiso::all()->pluck('descripcion_tipo_permiso')->toArray();
        $empleado = Empleado::all()->sortBy('departamentos_id');
        $horario = Horario::all();
        $departamento = Departamento::all()->sortBy('descripcion_departamento');
        $tipo_empleado = Tipo_empleado::all()->sortBy('descripcion_tipo_empleado');;

        switch (true) {
            case $clasificacion_reporte === 'ausencias':
                $estadistico_post_clasificacion = $estadistico_completo->where('estado_asistencia','FALTA INJUST');
                break;
            case $clasificacion_reporte === 'retardos':
                $estadistico_post_clasificacion = $estadistico_completo->where('estado_asistencia','ENTRADA TARDE');
                break;
            case $clasificacion_reporte === 'permisos':
                $estadistico_post_clasificacion = $estadistico_completo->whereIn('estado_asistencia', $lista_permisos);
                break;
            case $clasificacion_reporte === 'resumen general':
                $estadistico_post_clasificacion = $estadistico_completo;
                break;
            case $clasificacion_reporte === 'cesta ticket':
                $estadistico_post_clasificacion = $estadistico_completo_ticket;
                break;
            default:
                $estadistico_post_clasificacion = $estadistico_completo;
                break;
        }

        switch (true) {
            case $tipo_reporte === 'departamento':
                $empleados_array = $this->empleadoPorDepartamentos($id_tipo_reporte);
                $estadistico_post_tipo_reporte = $estadistico_post_clasificacion->whereIn('empleado_id',$empleados_array);
            break;
            case $tipo_reporte === 'individual':
                $empleado_id_biometrico = $empleado->where('id',$id_tipo_reporte)->first()['biometrico_id'];
                $estadistico_post_tipo_reporte = $estadistico_post_clasificacion->where('empleado_id',$empleado_id_biometrico);
            break;
            default:
                $estadistico_post_tipo_reporte = $estadistico_post_clasificacion;
            break;
        }

        switch (true) {
            case $clasificar_por === 'tipo_empleado':
            $empleados_array = $this->empleadoPorTipoEmpleado($id_clasificar_por);
                $estadistico = $estadistico_post_tipo_reporte->whereIn('empleado_id',$empleados_array)->all();
            break;
            case $clasificar_por === 'incidencias':
                $estadistico = $estadistico_post_tipo_reporte->where('estado_asistencia',$id_clasificar_por)->all();
            break;
            default:
                $estadistico = $estadistico_post_tipo_reporte;
            break;
        }
        $reporte = [];
        $array_empleados_id = $this->cargarRegistrosDias($estadistico); // array = [57 => [], 68 => [], etc]
        $array_departamentos_id = $this->cargarDepartamentos($departamento); // array = [1 => [], 2 => [], etc] (departamentos_id)

        if ($amplitud_reporte === "completo") 
        {   
            foreach ($estadistico as $llave => $info) 
            {   
                $empleado_actual = $empleado->where('biometrico_id',$info['empleado_id'])->first();
                $departamento_empleado_actual = $empleado_actual['departamentos_id'];
                $horario_empleado_actual = $horario->where('id', $empleado_actual['horarios_id'])->first();
                $entrada_horario = strtotime($horario_empleado_actual['hora_entrada']);
                $salida_horario = strtotime($horario_empleado_actual['hora_salida']);
                $horas_trabajadas_diurnas = "";
                $horas_trabajadas_nocturnas = "";
                $horas_trabajadas_diurnas = "";
                $horas_trabajadas_nocturnas = "";
                $entrada_antes = "";            // Los declaro todas de antemano porque en algunos casos unas que otras opciones
                $entrada_despues = "";          // están vacías, así evito llamar una variable inexistente.
                $salida_antes = "";
                $salida_despues = "";
                $cesta_ticket = "0";

                if ($info['estado_asistencia'] === "NORMAL" || $info['estado_asistencia'] === "ENTRADA ANTES" || $info['estado_asistencia'] === "ENTRADA TARDE") 
                {

                    // Calcular horas trabajadas

                    $horas_trabajadas_diurnas = date('H:i:s',strtotime($info['hora_sale']) - strtotime($info['hora_entra']));

                    if ($salida_horario < $entrada_horario) 
                    {   // Que significa: Si la hora de salida es menor a la de la entrada-->(Turno Nocturno)
                        $horas_trabajadas_nocturnas = date('H:i:s', (strtotime("24:00:00")-strtotime($info['hora_entra'])) + strtotime($info['hora_sale']));
                        $horas_trabajadas_diurnas = ''; 
                    }


                    // Calcular horas de entrada antes y despues
                    if ($entrada_horario < strtotime($info['hora_entra'])) {
                        $entrada_despues = date('H:i:s',strtotime($info['hora_entra']) - $entrada_horario);
                    }
                    elseif ($entrada_horario > strtotime($info['hora_entra'])) {
                        $entrada_antes = date('H:i:s', strtotime("24:00:00")- (strtotime($info['hora_entra']) - $entrada_horario));
                    }
                    else {
                        $entrada_antes = ''; $entrada_despues = '';
                    }
                    //Si la diferencia es positiva, entró antes, si es negativa entró despues

                    // Calcular horas de salida antes y despues
                    if ($salida_horario < strtotime($info['hora_sale'])) {
                        $salida_despues = date('H:i:s',strtotime($info['hora_sale']) - $salida_horario);
                    }
                    elseif ($salida_horario > strtotime($info['hora_sale'])) {
                        $salida_antes = date('H:i:s', strtotime("24:00:00")- (strtotime($info['hora_sale']) - $salida_horario));
                    }
                    else {
                        $salida_antes = ''; $salida_despues = '';
                    }
                    /*$diferencia_hora_salida = date('H:i:s',$salida_horario - strtotime($info['hora_sale']));
                    ($diferencia_hora_salida>=0 ? $salida_antes = $diferencia_hora_salida : $salida_despues = abs($diferencia_hora_salida));*/ 
                    //La lógica es la misma así que no te des mala vida

                    // Calcular cesta ticket
                    if (IntVal(substr($horas_trabajadas_diurnas, 0, 2))>=$empleado_actual['horas_minimas_cesta_ticket'] || IntVal(substr($horas_trabajadas_nocturnas, 0, 2))>=$empleado_actual['horas_minimas_cesta_ticket'])
                    {
                        $cesta_ticket = "1";
                    }
                }

                    array_push($array_empleados_id[$info['empleado_id']], [$info['empleado_id'], $info['fecha'], $info['dia'], $info['estado_asistencia'], $info['hora_entra'], $info['hora_sale'], $horas_trabajadas_diurnas, $horas_trabajadas_nocturnas, $entrada_antes, $entrada_despues, $salida_antes, $salida_despues, $cesta_ticket]);
                    /* El array contiene:

                        empleado_id correspondiente = 
                        [
                            0 = empleado_id
                            1 = fecha
                            2 = dia
                            3 = estado_asistopencia
                            4 = hora_entra
                            5 = hora_sale
                            6 = horas_trabajadas_diurnas
                            7 = horas_trabajadas_nocturnas
                            8 = entrada_antes
                            9 = entrada_despues
                            10 = salida_antes
                            11 = salida_despues
                            12 = cesta_ticket (1 ó 0)
                            13 = departamento_id
                            
                        ]
                    */
                    array_push($array_departamentos_id[$departamento_empleado_actual], $info['empleado_id']);

            }
            $lista_ordenada_por_departamentos = $this->ordenarEstadisticosPorDepartamento($array_departamentos_id);
            $lista_completamente_ordenada = $this->ordenarPor($lista_ordenada_por_departamentos, $ordenar_por, $empleado);

            $reporte = [
            'usuario' => $usuario,
            'clasificacion_reporte' => $clasificacion_reporte,
            'tipo_reporte' => $tipo_reporte,
            'amplitud_reporte' => $amplitud_reporte,
            'ordenar_por' => $ordenar_por,
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'created_at' => now(),
            'updated_at' => now()
        ];
        Reporte::insert($reporte);

            return [$array_empleados_id,$lista_completamente_ordenada];
        }
        else 
        {
            foreach ($estadistico as $llave => $info) 
            {   
                $empleado_actual = $empleado->where('biometrico_id',$info['empleado_id'])->first();
                $departamento_empleado_actual = $empleado_actual['departamentos_id'];
                array_push($array_empleados_id[$info['empleado_id']],  [$info['empleado_id'], $info['fecha'], $info['dia'], $info['estado_asistencia'], $info['hora_entra'], $info['hora_sale']]);
                /* El array contiene:

                    empleado_id correspondiente = 
                    [
                        0 = empleado_id
                        1 = fecha
                        2 = dia
                        3 = estado_asistencia
                        4 = hora_entra
                        5 = hora_sale
                    ]
                */
                array_push($array_departamentos_id[$departamento_empleado_actual], $info['empleado_id']);
            }
            $lista_ordenada_por_departamentos = $this->ordenarEstadisticosPorDepartamento($array_departamentos_id);
            $lista_completamente_ordenada = $this->ordenarPor($lista_ordenada_por_departamentos, $ordenar_por, $empleado);

            $reporte = [
            'usuario' => $usuario,
            'clasificacion_reporte' => $clasificacion_reporte,
            'tipo_reporte' => $tipo_reporte,
            'amplitud_reporte' => $amplitud_reporte,
            'ordenar_por' => $ordenar_por,
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'created_at' => now(),
            'updated_at' => now()
        ];
        Reporte::insert($reporte);
            return [$array_empleados_id,$lista_completamente_ordenada];
            // array = [57 => {id='00', hora='23:43:34', etc...}]     
        } 

    }
}
