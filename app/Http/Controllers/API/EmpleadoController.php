<?php

namespace App\Http\Controllers\API;

use App\Empleado;
use App\Cargo;
use App\Departamento;
use App\Empresa;
use App\Horario;
use App\Nomina;
use App\Tipo_empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Empleado::latest()->get();
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
            'nombre_completo' => 'required|string|max:191',
            'cedula' => 'required|string|max:191',
            'biometrico_id' => 'required|string|max:191',
            'nacionalidad' => 'required|string|max:191',
            'estado_civil' => 'required|string|max:191',
            'estado_nacimiento' => 'required|string|max:191',
            'lugar_nacimiento' => 'required|string|max:191',
            'instruccion_academica' => 'required|string|max:191',
            'profesion' => 'required|string|max:191',
            'telefono_fijo' => 'required|string|max:191',
            'telefono_movil' => 'required|string|max:191',
            'correo' => 'required|string|max:191',
            'sexo' => 'required|string|max:191',
            'condicion_empleado' => 'nullable|boolean|max:191',
            'empresas_id' => 'required',
            'cargos_id' => 'required',
            'departamentos_id' => 'required',
            'tipo_empleados_id' => 'required',
            'tipo_nominas_id' => 'required',
            'horarios_id' => 'required',
            'jornada_laboral' => 'required|max:191',
            'horas_minimas_cesta_ticket' => 'required|max:191',
            'valor_ticket' => 'required|max:191',
            'codigo_punto_venta' => 'required|string|max:191',
            'prorratear_cesta_ticket' => 'required|boolean|max:191',
            'pago_horas_extra_antes' => 'required|boolean|max:191',
            'pago_horas_extra_despues' => 'required|boolean|max:191',
            'marcar_entrada_salida' => 'required|boolean|max:191',
            'salida_nocturna' => 'nullable|boolean|max:191',
            'inicio_salida_nocturna' => 'nullable|max:191',
            'fin_salida_nocturna' => 'nullable|max:191',
            'descontar_horas_descanso' => 'required|boolean|max:191',
            'marca_rango_comida' => 'required|boolean|max:191',
            'duracion_rango_comida' => 'nullable|max:191',
            'cesta_sabado_domingo_rango' => 'required|boolean|max:191',
            'duracion_rango_cesta' => 'nullable|max:191',
        ]);

        return Empleado::create([
            'nombre_completo' => $request['nombre_completo'],
            'cedula' => $request['cedula'],
            'biometrico_id' => $request['biometrico_id'],
            'nacionalidad' => $request['nacionalidad'],
            'estado_civil' => $request['estado_civil'],
            'estado_nacimiento' => $request['estado_nacimiento'],
            'lugar_nacimiento' => $request['lugar_nacimiento'],
            'instruccion_academica' => $request['instruccion_academica'],
            'profesion' => $request['profesion'],
            'telefono_fijo' => $request['telefono_fijo'],
            'telefono_movil' => $request['telefono_movil'],
            'correo' => $request['correo'],
            'sexo' => $request['sexo'],
            'condicion_empleado' => $request['condicion_empleado'],
            //Desde aqui 
            'empresas_id' => $request['empresas_id']['id'],
            'cargos_id' => $request['cargos_id']['id'],
            'departamentos_id' => $request['departamentos_id']['id'],
            'tipo_empleados_id' => $request['tipo_empleados_id']['id'],
            'tipo_nominas_id' => $request['tipo_nominas_id']['id'],
            'horarios_id' => $request['horarios_id']['id'],
            // Hasta aqui  son objetos, pero interesa guardar solo el ID de referencia, no el objeto completo
            'jornada_laboral' => (int)$request['jornada_laboral'],
            'horas_minimas_cesta_ticket' => (int)$request['horas_minimas_cesta_ticket'],
            'valor_ticket' => $request['valor_ticket'],
            'codigo_punto_venta' => $request['codigo_punto_venta'],
            'prorratear_cesta_ticket' => $request['prorratear_cesta_ticket'],
            'pago_horas_extra_antes' => $request['pago_horas_extra_antes'],
            'pago_horas_extra_despues' => $request['pago_horas_extra_despues'],
            'marcar_entrada_salida' => $request['marcar_entrada_salida'],
            'salida_nocturna' => $request['salida_nocturna'],
            'inicio_salida_nocturna' => $request['inicio_salida_nocturna'],
            'fin_salida_nocturna' => $request['fin_salida_nocturna'],
            'descontar_horas_descanso' => $request['descontar_horas_descanso'],
            'marca_rango_comida' => $request['marca_rango_comida'],
            'duracion_rango_comida' => $request['duracion_rango_comida'],
            'cesta_sabado_domingo_rango' => $request['cesta_sabado_domingo_rango'],
            'duracion_rango_cesta' => $request['duracion_rango_cesta'],
            'confianza' => $request['confianza'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $this->validate($request,[
            'nombre_completo' => 'required|string|max:191',
            'cedula' => 'required|string|max:191',
            'biometrico_id' => 'required|string|max:191',
            'nacionalidad' => 'required|string|max:191',
            'estado_civil' => 'required|string|max:191',
            'estado_nacimiento' => 'required|string|max:191',
            'lugar_nacimiento' => 'required|string|max:191',
            'instruccion_academica' => 'required|string|max:191',
            'profesion' => 'required|string|max:191',
            'telefono_fijo' => 'required|string|max:191',
            'telefono_movil' => 'required|string|max:191',
            'correo' => 'required|string|max:191',
            'sexo' => 'required|string|max:191',
            'condicion_empleado' => 'nullable|boolean|max:191',
            'empresas_id' => 'required',
            'cargos_id' => 'required',
            'departamentos_id' => 'required',
            'tipo_empleados_id' => 'required',
            'tipo_nominas_id' => 'required',
            'horarios_id' => 'required',
            'jornada_laboral' => 'required|max:191',
            'horas_minimas_cesta_ticket' => 'required|max:191',
            'valor_ticket' => 'required|max:191',
            'codigo_punto_venta' => 'required|string|max:191',
            'prorratear_cesta_ticket' => 'required|boolean|max:191',
            'pago_horas_extra_antes' => 'required|boolean|max:191',
            'pago_horas_extra_despues' => 'required|boolean|max:191',
            'marcar_entrada_salida' => 'required|boolean|max:191',
            'salida_nocturna' => 'nullable|boolean|max:191',
            'inicio_salida_nocturna' => 'nullable|max:191',
            'fin_salida_nocturna' => 'nullable|max:191',
            'descontar_horas_descanso' => 'required|boolean|max:191',
            'marca_rango_comida' => 'required|boolean|max:191',
            'duracion_rango_comida' => 'nullable|max:191',
            'cesta_sabado_domingo_rango' => 'required|boolean|max:191',
            'duracion_rango_cesta' => 'nullable|max:191',
        ]);

            $empleado->nombre_completo = $request['nombre_completo'];
            $empleado->cedula = $request['cedula'];
            $empleado->biometrico_id = $request['biometrico_id'];
            $empleado->nacionalidad = $request['nacionalidad'];
            $empleado->estado_civil = $request['estado_civil'];
            $empleado->estado_nacimiento = $request['estado_nacimiento'];
            $empleado->lugar_nacimiento = $request['lugar_nacimiento'];
            $empleado->instruccion_academica = $request['instruccion_academica'];
            $empleado->profesion = $request['profesion'];
            $empleado->telefono_fijo = $request['telefono_fijo'];
            $empleado->telefono_movil = $request['telefono_movil'];
            $empleado->correo = $request['correo'];
            $empleado->sexo = $request['sexo'];
            $empleado->condicion_empleado = $request['condicion_empleado'];
            //Desde aqui 
            $empleado->empresas_id = $request['empresas_id']['id'];
            $empleado->cargos_id = $request['cargos_id']['id'];
            $empleado->departamentos_id = $request['departamentos_id']['id'];
            $empleado->tipo_empleados_id = $request['tipo_empleados_id']['id'];
            $empleado->tipo_nominas_id = $request['tipo_nominas_id']['id'];
            $empleado->horarios_id = $request['horarios_id']['id'];
            // Hasta aqui  son objetos, pero interesa guardar solo el ID de referencia, no el objeto completo
            $empleado->jornada_laboral = (int)$request['jornada_laboral'];
            $empleado->horas_minimas_cesta_ticket = (int)$request['horas_minimas_cesta_ticket'];
            $empleado->valor_ticket = $request['valor_ticket'];
            $empleado->codigo_punto_venta = $request['codigo_punto_venta'];
            $empleado->prorratear_cesta_ticket = $request['prorratear_cesta_ticket'];
            $empleado->pago_horas_extra_antes = $request['pago_horas_extra_antes'];
            $empleado->pago_horas_extra_despues = $request['pago_horas_extra_despues'];
            $empleado->marcar_entrada_salida = $request['marcar_entrada_salida'];
            $empleado->salida_nocturna = $request['salida_nocturna'];
            $empleado->inicio_salida_nocturna = $request['inicio_salida_nocturna'];
            $empleado->fin_salida_nocturna = $request['fin_salida_nocturna'];
            $empleado->descontar_horas_descanso = $request['descontar_horas_descanso'];
            $empleado->marca_rango_comida = $request['marca_rango_comida'];
            $empleado->duracion_rango_comida = $request['duracion_rango_comida'];
            $empleado->cesta_sabado_domingo_rango = $request['cesta_sabado_domingo_rango'];
            $empleado->duracion_rango_cesta = $request['duracion_rango_cesta'];
            $empleado->confianza = $request['confianza'];

        $empleado->save();
        return ['message' => 'La información del empleado ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $empleado = Empleado::findOrFail($id);
        // delete the user

        $empleado->delete();

        return ['message' => 'Empleado Eliminado'];
    }

    public function buscar($busqueda)
    {
        while ($busqueda != '')
        {
            $empleados = Empleado::where(function($query) use ($busqueda)
            {
                $query->where('nombre_completo','LIKE',"%$busqueda%")
                        ->orWhere('cedula','LIKE',"%$busqueda%")
                        ->orWhere('biometrico_id','LIKE',"%$busqueda%");

            })->get();
            return $empleados;
        }
            

        return Empleado::latest()->get();

    }

    public function loadEmpresa()
    {

        if ($search = \Request::get('q')) {
            $empleado = Empleado::where(function($query) use ($search){
                $query->where('nombre_completo','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $empleado = Empleado::latest()->paginate(5);
        }

        return $empleado;

    }

    public function nomina_horario_datos($id)
    {
        $datos_empleado = Empleado::find($id);
        $empresa = Empresa::all();
        $cargo = Cargo::all();
        $departamento = Departamento::all();
        $nomina = Nomina::all();
        $tipo_empleado = Tipo_empleado::all();
        $horario = Horario::all();

        $array_datos_empleado = [
            'empresa' => [], 
            'cargo' => [],
            'departamento' => [],
            'nominas' => [],
            'tipo_empleado' => [],
            'horario' => [],
        ];

        $array_datos_empleado['empresa'] = $empresa->where('id', $datos_empleado['empresas_id'])->first();
        $array_datos_empleado['cargo'] = $cargo->where('id', $datos_empleado['cargos_id'])->first();
        $array_datos_empleado['departamento'] = $departamento->where('id', $datos_empleado['departamentos_id'])->first();
        $array_datos_empleado['nominas'] = $nomina->where('id', $datos_empleado['tipo_nominas_id'])->first();
        $array_datos_empleado['tipo_empleado'] = $tipo_empleado->where('id', $datos_empleado['tipo_empleados_id'])->first();
        $array_datos_empleado['horario'] = $horario->where('id', $datos_empleado['horarios_id'])->first();

        return $array_datos_empleado;
    }
}
