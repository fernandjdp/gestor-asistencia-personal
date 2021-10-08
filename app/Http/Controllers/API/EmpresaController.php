<?php

namespace App\Http\Controllers\API;

use App\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Empresa::latest()->paginate(10); 
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
            'descripcion_empresa' => 'required|string|max:191',
            'subtitulo_reporte' => 'required|string|max:191',
        ]);

        return Empresa::create([
            'descripcion_empresa' => $request['descripcion_empresa'],
            'logo' => $request['logo'],
            'subtitulo_reporte' => $request['subtitulo_reporte'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $this->validate($request,[
            'descripcion_empresa' => 'required|string|max:191',
            'subtitulo_reporte' => 'required|string|max:191',
        ]);

        $empresa->update($request->all());
        return ['message' => 'La información del empresa ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $empresa = Empresa::findOrFail($id);
        // delete the user

        $empresa->delete();

        return ['message' => 'Empresa Eliminado'];
    }

    public function buscar()
    {

        if ($search = \Request::get('q')) {
            $empresa = Empresa::where(function($query) use ($search){
                $query->where('descripcion_empresa','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $empresa = Empresa::latest()->paginate(5);
        }

        return $empresa;

    }
}
