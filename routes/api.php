<?php

use Illuminate\Http\Request;

/*php
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
         						 ADMINISTRACION
*/

// USUARIOS

Route::apiResources(['user' => 'API\UserController']);
Route::get('profile', 'API\UserController@profile');
Route::get('findUser', 'API\UserController@search');
Route::put('profile', 'API\UserController@updateProfile');

// DEPARTAMENTOS

Route::apiResources(['departamento' => 'API\DepartamentoController']);
Route::get('buscar_departamento', 'API\DepartamentoController@buscar');

// CARGOS

Route::apiResources(['cargo' => 'API\CargoController']);
Route::get('buscar_cargo', 'API\CargoController@buscar');

// DIAS FESTIVOS

Route::apiResources(['dia_festivo' => 'API\DiaFestivoController']);
Route::get('buscar_dia_festivo', 'API\DiaFestivoController@buscar');

// NOMINAS

Route::apiResources(['nomina' => 'API\NominaController']);
Route::get('buscar_nomina', 'API\NominaController@buscar');

// TIPO DE EMPLEADOS

Route::apiResources(['tipo_empleado' => 'API\TipoEmpleadoController']);
Route::get('buscar_tipo_empleado', 'API\TipoEmpleadoController@buscar');
Route::get('cargar_empresa', 'API\TipoEmpleadoController@loadEmpresa');

// EMPRESAS

Route::apiResources(['empresa' => 'API\EmpresaController']);
Route::get('buscar_empresa', 'API\EmpresaController@buscar');

// HORARIOS

Route::apiResources(['horario' => 'API\HorarioController']);
Route::get('horario_serialize/{id}', 'API\HorarioController@dias_serializados');
Route::get('buscar_horario', 'API\HorarioController@buscar');

// EMPLEADOS

Route::apiResources(['empleado' => 'API\EmpleadoController']);
Route::get('empleado_datos_nomina_horario/{id}', 'API\EmpleadoController@nomina_horario_datos');
Route::get('buscar_empleado/{busqueda}', 'API\EmpleadoController@buscar');

// REGISTROS

Route::apiResources(['registro' => 'API\RegistroController']);
Route::get('buscar_registro', 'API\RegistroController@buscar');

// PERMISOS

Route::apiResources(['permiso' => 'API\PermisoController']);
Route::get('buscar_permiso', 'API\PermisoController@buscar');

// TIPOS DE PERMISOS

Route::apiResources(['tipo_permiso' => 'API\TipoPermisoController']);
Route::get('buscar_tipo_permiso', 'API\TipoPermisoController@buscar');

// CARGAR REGISTROS

Route::apiResources(['cargar_registro' => 'API\CargarRegistrosController']);
Route::get('ultimo_registro', 'API\CargarRegistrosController@ultimoRegistro');
Route::post('cargar_texto', 'API\CargarRegistrosController@cargarTexto');
Route::get('buscar_registro', 'API\CargarRegistrosController@buscar');

// ESTADISTICOS	

Route::apiResources(['estadistico' => 'API\EstadisticoController']);
Route::get('buscar_estadistico', 'API\EstadisticoController@buscar');
Route::get('calcular/{tipo}/{fecha_inicial}/{fecha_final}', 'API\EstadisticoController@calcular');

// REPORTES	

Route::apiResources(['reporte' => 'API\ReporteController']);
Route::get('crear_reporte/{usuario}/{clasificacion_reporte}/{tipo_reporte}/{id_tipo_reporte}/{clasificar_por}/{id_clasificar_por}/{amplitud_reporte}/{ordernar_por}/{fecha_inicial}/{fecha_final}', 'API\ReporteController@crear_reporte');
Route::get('buscar_reporte', 'API\ReporteController@buscar');
Route::get('usuario', 'API\ReporteController@usuario');
Route::get('info_empleados', 'API\ReporteController@cargarInfoEmpleados');
