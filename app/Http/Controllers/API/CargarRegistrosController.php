<?php

namespace App\Http\Controllers\API;

use App\CargarRegistros;
use App\Registro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CargarRegistrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CargarRegistros::latest()->paginate(10);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CargarRegistros  $cargarRegistros
     * @return \Illuminate\Http\Response
     */
    public function show(CargarRegistros $cargarRegistros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CargarRegistros  $cargarRegistros
     * @return \Illuminate\Http\Response
     */
    public function edit(CargarRegistros $cargarRegistros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CargarRegistros  $cargarRegistros
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CargarRegistros $cargarRegistros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CargarRegistros  $cargarRegistros
     * @return \Illuminate\Http\Response
     */
    public function destroy(CargarRegistros $cargarRegistros)
    {
        //
    }

    public function fechasRegistrosExistentes()
    {
        set_time_limit(0);
        $fechas_totales = [];
        $unique = [];
        $fechas_registros_existentes = Registro::orderBy('date', 'ASC')->get(['date']);
        foreach ($fechas_registros_existentes as $llave => $fecha) {
            array_push($unique, $fecha['date']);
        }

        $fechas_totales = array_unique($unique);
        return $fechas_totales;
    }

    public function ultimoRegistro()
    {
        set_time_limit(0);
        $ultimo_registro = CargarRegistros::latest()->first();
        $ultimo_registro_fecha_inicial = $ultimo_registro['fecha_inicial'];
        $ultimo_registro_fecha_final = $ultimo_registro['fecha_final'];
        return [$ultimo_registro_fecha_inicial, $ultimo_registro_fecha_final];
    }
    public function rangoFechas($contenido_aun_mas_separado)
    { 
        /*
            Esta funcion carga el array de contenido con todos los registros, obteniendo las fechas nada más
            ordenandolos de menor a mayor
        */
        set_time_limit(0);
        $array_fechas = [];
        foreach ($contenido_aun_mas_separado as $key => $value) {
            array_push($array_fechas, $this->formateo_fecha($value[5]));
        }
        sort($array_fechas);
        return [$array_fechas[0], end($array_fechas)];  
    }
    public function formateo_fecha($fecha_sin_formatear)
    {
        //Esta funcion pasa de una fecha 17/08/2020 a 2020-08-17 para poder guardarlo
        $array_fecha = explode("-", $fecha_sin_formatear); // array = [0 = 17, 1 = 08, 2 = 2020]
        if ($fecha_sin_formatear[2] == '/') {
            $array_fecha = explode("/", $fecha_sin_formatear);
        }
        if ($array_fecha[0] > 1000) {
            $fecha_formateada = $array_fecha[0]."-".$array_fecha[1]."-".$array_fecha[2];
        }
        else {
            $fecha_formateada = $array_fecha[2]."-".$array_fecha[1]."-".$array_fecha[0];
        }
        return $fecha_formateada;
    }

    public function encode_utf8($data)
    {
        if ($data === null || $data === '') {
            return $data;
        }
        if (!mb_check_encoding($data, 'UTF-8')) {
            return mb_convert_encoding($data, 'UTF-8');
        } else {
            return $data;
        }
    }
    public function cargarTexto(Request $request)
    {
        set_time_limit(0);
        $fechas_registros_existentes = $this->fechasRegistrosExistentes();
        $contenido_aun_mas_separado = [];
        $contenido = file_get_contents($request->texto_registro->path());
        $contenido = $this->encode_utf8($contenido);

        $contenido_separado = array_unique(explode("\n", $contenido, -1));
        foreach ($contenido_separado as $key => $value) {
            array_push($contenido_aun_mas_separado, explode(";", $contenido_separado[$key]));
        }
        // Encontrar una manera más elegante de eliminar los primeros dos valores

        array_shift($contenido_aun_mas_separado);
        array_shift($contenido_aun_mas_separado);

        $array_fecha_inicial_final = $this->rangoFechas($contenido_aun_mas_separado);
        foreach (array_chunk($contenido_aun_mas_separado, 500) as $key => $array_info) 
        {
        $data_registro=[];
            foreach ($array_info as $value) {

                if (in_array($this->formateo_fecha($value[5]), $fechas_registros_existentes) != TRUE) 
                {
                    $data_registro[] = [
                            'dev_id' => $value[0],
                            'description' => $value[1],
                            'model' => $value[2],
                            'empleado_id' => $value[3],
                            'username' => $value[4],
                            'date' => $this->formateo_fecha($value[5]),
                            'time' => $value[6],
                            'in_out' => $value[7],
                            'workcode' => $value[8],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    /*
                    $registro_cargado = new Registro();
                    $registro_cargado->dev_id = $value[0];
                    $registro_cargado->description = $value[1];
                    $registro_cargado->model = $value[2];
                    $registro_cargado->empleado_id = $value[3];
                    $registro_cargado->username = $value[4];
                    $registro_cargado->date = $this->formateo_fecha($value[5]);
                    $registro_cargado->time = $value[6];
                    $registro_cargado->in_out = $value[7];
                    $registro_cargado->workcode = $value[8];
                    $registro_cargado->save();
                    */
                }
            }
        Registro::insert($data_registro);
        }

        $informe_registro_cargado = new CargarRegistros();
        $informe_registro_cargado->texto_registro = $contenido;
        $informe_registro_cargado->nombre_texto = $request['nombre_texto'];
        $informe_registro_cargado->registrado_por = $request['registrado_por'];
        $informe_registro_cargado->fecha_inicial = $array_fecha_inicial_final[0];
        $informe_registro_cargado->fecha_final = $array_fecha_inicial_final[1];
        $informe_registro_cargado->save();
        set_time_limit(60);

        return 1;
    }
}
