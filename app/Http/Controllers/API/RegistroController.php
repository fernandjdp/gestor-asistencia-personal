<?php

namespace App\Http\Controllers\API;

use App\Registro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Registro::latest()->get();
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
            'dev_id' => 'required|string|max:191',
            'description' => 'required|string|max:191',
            'model' => 'required|string|max:191',
            'empleado_id' => 'required|max:191',
            'username' => 'required|string|max:191',
            'date' => 'required|max:191',
            'time' => 'required|max:191',
            'in_out' => 'required|string|max:191',
            'workcode' => 'required|string|max:191',
            'department' => 'required|string|max:191',
            'emp_num' => 'required|string|max:191',

        ]);

        return Registro::create([
            'dev_id' => $request['dev_id'],
            'description' => $request['description'],
            'model' => $request['model'],
            'empleado_id' => $request['empleado_id'],
            'username' => $request['username'],
            'date' => $request['date'],
            'time' => $request['time'],
            'in_out' => $request['in_out'],
            'workcode' => $request['workcode'],
            'department' => $request['department'],
            'emp_num' => $request['emp_num'],

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function show(Registro $registro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function edit(Registro $registro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $registro = Registro::findOrFail($id);

        $this->validate($request,[
            'dev_id' => 'required|string|max:191',
            'description' => 'required|string|max:191',
            'model' => 'required|string|max:191',
            'empleado_id' => 'required|max:191',
            'username' => 'required|string|max:191',
            'date' => 'required|max:191',
            'time' => 'required|max:191',
            'in_out' => 'required|string|max:191',
            'workcode' => 'required|string|max:191',
            'department' => 'required|string|max:191',
            'emp_num' => 'required|string|max:191',
        ]);

        $registro->update($request->all());
        return ['message' => 'La información del registro ha sido actualizada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $registro = Registro::findOrFail($id);
        // delete the user

        $registro->delete();

        return ['message' => 'Registro Eliminado'];
    }

    public function buscar()
    {

        if ($search = \Request::get('q')) {
            $registro = Registro::where(function($query) use ($search){
                $query->where('empleado_id','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $registro = Registro::latest()->paginate(5);
        }

        return $registro;

    }
}
