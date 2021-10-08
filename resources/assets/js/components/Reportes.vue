<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div v-if="$gate.isAdminOrAuthor()" class="card">
              <div class="card-header">
                <h3 class="card-title">Reportes</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Nuevo <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th v-if="$gate.isAdminOrAuthor()">Clave</th>
                        <th>Emisor</th>
                        <th>Clasificación</th>
                        <th>Tipo de reporte</th>                       
                        <th>Amplitud</th>                        
                        <th>Ordenado</th>
                        <th>Emisión</th>
                        <th>Fecha inicial</th>
                        <th>Fecha final</th>
                    </tr>


                  <tr v-for="reporte in reportes.data" :key="reporte.id">

                    <td v-if="$gate.isAdminOrAuthor()">{{reporte.id}}</td>
                    <td>{{reporte.usuario}}</td>
                    <td>{{reporte.clasificacion_reporte}}</td>
                    <td>{{reporte.tipo_reporte}}</td>
                    <td>{{reporte.amplitud_reporte}}</td>
                    <td>{{reporte.ordenar_por}}</td>
                    <td>{{reporte.created_at}}</td>
                    <td>{{reporte.fecha_inicial}}</td>
                    <td>{{reporte.fecha_final}}</td>
    
                  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="reportes" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="$gate.isUser()" class="card">
              <div class="card-header">
                <h3 class="card-title">Reportes</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <form @submit.prevent="editmode ? test() : test()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group pt-3">
                                <label>Clasificación del reporte:</label>
                                <select v-model="form.clasificacion_reporte" class="form-control" id="clasificacion_reporte" name="clasificacion_reporte" :class="{ 'is-invalid': form.errors.has('clasificacion_reporte') }" required>
                                    <option :value=null disabled selected>Seleccione una clasificacion de reporte</option>
                                    <option value="ausencias">Ausencias</option>
                                    <option value="retardos">Retardos</option>
                                    <option value="permisos">Permisos</option>
                                    <option value="resumen general">Resumen General</option>
                                    <option value="cesta ticket">Cesta ticket</option>
                                </select>
                                <has-error :form="form" field="clasificacion_reporte"></has-error>
                            </div>
                            <!-- Tantos v-if son porque muchas opciones se repetian entre las pestañas del modulo de reportes, era esto o escribir un par de selectores DISTINTOS por CADA PESTAÑA que se verían exatamente iguales, omitiendo o mostrando algunos
                            Esta alternativa si bien es menos elegante, termina siendo más práctica. --->
                            <div class="form-group pt-3">
                                <label>Tipo de Reporte:</label>
                                <select @change="loadSelectOptions" v-model="form.tipo_reporte" class="form-control" id="tipo_reporte" name="tipo_reporte" :class="{ 'is-invalid': form.errors.has('tipo_reporte') }">
                                    <option :value=null disabled selected>Seleccione un tipo de reporte</option>
                                    <option v-if="form.clasificacion_reporte != 'generales'" value="general">General</option>
                                    <option v-if="form.clasificacion_reporte === 'generales'" value="catalogo">Catalogo</option>
                                    <option v-if="form.clasificacion_reporte != 'generales'" value="departamento">Departamento</option>
                                    <option v-if="form.clasificacion_reporte != 'cesta_ticket'" value="individual">Individual</option>
                                    <option v-if="form.clasificacion_reporte === 'retardos'" value="especial">Especial</option>
                                </select>
                                <has-error :form="form" field="tipo_reporte"></has-error>
                            </div>
                            <!-- Dependiendo del tipo de reporte, mostrará el selector y sus respectivas opciones diferentes --->
                            <div v-if="form.tipo_reporte && form.tipo_reporte != 'general'" class="form-group pt-3">
                                <select v-model="form.id_tipo_reporte" class="form-control" id="tipo_reporte" name="tipo_reporte" :class="{ 'is-invalid': form.errors.has('tipo_reporte') }" required>
                                <option value="" selected disabled>Seleccione un {{ this.form.tipo_reporte }}</option>
                                <option v-for="opcion in opciones_select" :value="opcion.id" :key="opcion.id">
                                    <b v-if="form.tipo_reporte === 'general'"></b>
                                    <b v-if="form.tipo_reporte === 'departamento'">{{opcion.descripcion_departamento}}</b>
                                    <b v-if="form.tipo_reporte === 'individual'">{{opcion.nombre_completo}}</b>
                                </option>
                                </select>
                                <has-error :form="form" field="tipo_reporte"></has-error>
                            </div>
                            <div class="form-group pt-3">
                                <label>Amplitud del reporte:</label>
                                <select v-model="form.amplitud_reporte" class="form-control" id="amplitud_reporte" name="amplitud_reporte" :class="{ 'is-invalid': form.errors.has('amplitud_reporte') }" required>
                                    <option :value=null disabled selected>Seleccione la amplitud del reporte</option>
                                    <option value="basico">Básico</option>
                                    <option value="completo">Completo</option>
                                </select>
                                <has-error :form="form" field="amplitud_reporte"></has-error>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group pt-3 text-left">
                                <div  class="icheck-primary d-inline ml-2">
                                    <input v-model="form.clasificar_por" type="radio" value="tipo_empleado" name="clasificacion" id="clasificacion1">
                                    <label class="pl-2">Clasificar por tipo de empleado:</label>
                                </div>   
                            </div>
                            <div v-show="form.clasificacion_reporte === 'permisos'" class="form-group pt-3 text-left">
                                <div  class="icheck-primary d-inline ml-2">
                                    <input v-model="form.clasificar_por" type="radio" value="incidencias" name="clasificacion" id="clasificacion1">
                                    <label class="pl-2">Clasificar por incidencias:</label>
                                </div>   
                            </div>
                            <div v-show="form.clasificar_por" class="form-group">
                                <select v-model="form.id_clasificar_por" class="form-control" id="clasificar_por" name="clasificar_por" :class="{ 'is-invalid': form.errors.has('clasificar_por') }">
                                    <option value="" selected disabled>Seleccione un tipo</option>
                                    <option v-if="form.clasificar_por === 'tipo_empleado'" v-for="opcion in tipo_empleados" :value="opcion.id" :key="opcion.id">
                                        {{opcion.descripcion_tipo_empleado}}
                                    </option>
                                    <option v-if="form.clasificar_por === 'incidencias'" v-for="opcion in incidencias" :value="opcion.descripcion_tipo_permiso" :key="opcion.id">
                                        {{opcion.descripcion_tipo_permiso}}
                                    </option>
                                </select>
                                <has-error :form="form" field="clasificar_por"></has-error>
                            </div>
                            <div v-show="form.clasificar_por" class="form-group pt-3 text-left">
                                <div  class="icheck-primary d-inline ml-2">
                                    <input v-model="form.clasificar_por" type="radio" :value=null name="clasificacion" id="clasificacion1">
                                    <label class="pl-2">No clasificar</label>
                                </div>   
                            </div>  
                        </div>                  
                    </div>

                    <div v-show="form.clasificacion_reporte != 'generales'" class="row">
                        <div class="col">
                            <div  class="form-group pt-3">
                                <label>Ordenar por:</label>
                                <select v-model="form.ordenar_por" class="form-control" id="ordenar_por" name="ordenar_por" :class="{ 'is-invalid': form.errors.has('ordenar_por') }" required>
                                    <option selected value="biometrico_id">ID Biometrico</option>
                                    <option value="cedula">Número de cédula</option>
                                    <option value="nombre_completo">Apellidos y Nombres</option>
                                </select>
                                <has-error :form="form" field="ordenar_por"></has-error>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group pt-3">
                                <label>Empresa:</label>
                                <select v-model="form.empresas_id" class="form-control" id="empresas_id" name="empresas_id" :class="{ 'is-invalid': form.errors.has('empresas_id') }">
                                   <option value="" selected disabled>Seleccione un {{ this.form.tipo_reporte }}</option>
                                <option v-for="opcion in empresas" :value="opcion.id" :key="opcion.id">
                                    {{opcion.descripcion_empresa}}
                                </option>
                                </select>
                                <has-error :form="form" field="empresas_id"></has-error>
                            </div>
                        </div>
                    </div>
                    <div v-show="form.clasificacion_reporte != 'generales'" class="row">
                        <div class="col">
                            <div class="form-group pt-2">
                                <label>Fecha inicial</label>
                                <VueCtkDateTimePicker
                                v-model="form.fecha_inicial"
                                :only-date = true
                                :auto-close = true
                                :right = false
                                locale = "es"
                                format="YYYY-MM-DD"
                                formatted="l"
                                label="Seleccione la fecha inicial"
                                required
                                />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group pt-2 align-right">
                                <label>Fecha final</label>
                                <VueCtkDateTimePicker
                                v-model="form.fecha_final"
                                :only-date = true
                                :auto-close = true
                                :right = false
                                locale = "es"
                                format="YYYY-MM-DD"
                                formatted="l"
                                label="Seleccione la fecha final"
                                required
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                    <button v-show="!editmode" @click="loadArrayInfoReporte" class="btn btn-primary">Crear reporte</button>
                </div>

                </form>
              </div>
              <div class="card-footer">
              </div>
            </div>

    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="false" data-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo Reporte</h5>
                    <!--<h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar</h5> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? test() : test()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group pt-3">
                                <label>Clasificación del reporte:</label>
                                <select v-model="form.clasificacion_reporte" class="form-control" id="clasificacion_reporte" name="clasificacion_reporte" :class="{ 'is-invalid': form.errors.has('clasificacion_reporte') }" required>
                                    <option :value=null disabled selected>Seleccione una clasificacion de reporte</option>
                                    <option value="ausencias">Ausencias</option>
                                    <option value="retardos">Retardos</option>
                                    <option value="permisos">Permisos</option>
                                    <option value="resumen general">Resumen General</option>
                                    <option value="cesta ticket">Cesta ticket</option>
                                </select>
                                <has-error :form="form" field="clasificacion_reporte"></has-error>
                            </div>
                            <!-- Tantos v-if son porque muchas opciones se repetian entre las pestañas del modulo de reportes, era esto o escribir un par de selectores DISTINTOS por CADA PESTAÑA que se verían exatamente iguales, omitiendo o mostrando algunos
                            Esta alternativa si bien es menos elegante, termina siendo más práctica. --->
                            <div class="form-group pt-3">
                                <label>Tipo de Reporte:</label>
                                <select @change="loadSelectOptions" v-model="form.tipo_reporte" class="form-control" id="tipo_reporte" name="tipo_reporte" :class="{ 'is-invalid': form.errors.has('tipo_reporte') }">
                                    <option :value=null disabled selected>Seleccione un tipo de reporte</option>
                                    <option v-if="form.clasificacion_reporte != 'generales'" value="general">General</option>
                                    <option v-if="form.clasificacion_reporte === 'generales'" value="catalogo">Catalogo</option>
                                    <option v-if="form.clasificacion_reporte != 'generales'" value="departamento">Departamento</option>
                                    <option v-if="form.clasificacion_reporte != 'cesta_ticket'" value="individual">Individual</option>
                                    <option v-if="form.clasificacion_reporte === 'retardos'" value="especial">Especial</option>
                                </select>
                                <has-error :form="form" field="tipo_reporte"></has-error>
                            </div>
                            <!-- Dependiendo del tipo de reporte, mostrará el selector y sus respectivas opciones diferentes --->
                            <div v-if="form.tipo_reporte && form.tipo_reporte != 'general'" class="form-group pt-3">
                                <select v-model="form.id_tipo_reporte" class="form-control" id="tipo_reporte" name="tipo_reporte" :class="{ 'is-invalid': form.errors.has('tipo_reporte') }" required>
                                <this className="form tipo_report"></this>e }}</option>
                                <option v-for="opcion in opciones_select" :value="opcion.id" :key="opcion.id">
                                    <b v-if="form.tipo_reporte === 'general'"></b>
                                    <b v-if="form.tipo_reporte === 'departamento'">{{opcion.descripcion_departamento}}</b>
                                    <b v-if="form.tipo_reporte === 'individual'">{{opcion.nombre_completo}}</b>
                                </option>
                                </select>
                                <has-error :form="form" field="tipo_reporte"></has-error>
                            </div>
                            <div class="form-group pt-3">
                                <label>Amplitud del reporte:</label>
                                <select v-model="form.amplitud_reporte" class="form-control" id="amplitud_reporte" name="amplitud_reporte" :class="{ 'is-invalid': form.errors.has('amplitud_reporte') }" required>
                                    <option :value=null disabled selected>Seleccione la amplitud del reporte</option>
                                    <option value="basico">Básico</option>
                                    <option value="completo">Completo</option>
                                </select>
                                <has-error :form="form" field="amplitud_reporte"></has-error>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group pt-3 text-left">
                                <div  class="icheck-primary d-inline ml-2">
                                    <input v-model="form.clasificar_por" type="radio" value="tipo_empleado" name="clasificacion" id="clasificacion1">
                                    <label class="pl-2">Clasificar por tipo de empleado:</label>
                                </div>   
                            </div>
                            <div v-show="form.clasificacion_reporte === 'permisos'" class="form-group pt-3 text-left">
                                <div  class="icheck-primary d-inline ml-2">
                                    <input v-model="form.clasificar_por" type="radio" value="incidencias" name="clasificacion" id="clasificacion1">
                                    <label class="pl-2">Clasificar por incidencias:</label>
                                </div>   
                            </div>
                            <div v-show="form.clasificar_por" class="form-group">
                                <select v-model="form.id_clasificar_por" class="form-control" id="clasificar_por" name="clasificar_por" :class="{ 'is-invalid': form.errors.has('clasificar_por') }">
                                    <option value="" selected disabled>Seleccione un tipo</option>
                                    <option v-if="form.clasificar_por === 'tipo_empleado'" v-for="opcion in tipo_empleados" :value="opcion.id" :key="opcion.id">
                                        {{opcion.descripcion_tipo_empleado}}
                                    </option>
                                    <option v-if="form.clasificar_por === 'incidencias'" v-for="opcion in incidencias" :value="opcion.descripcion_tipo_permiso" :key="opcion.id">
                                        {{opcion.descripcion_tipo_permiso}}
                                    </option>
                                </select>
                                <has-error :form="form" field="clasificar_por"></has-error>
                            </div>
                            <div v-show="form.clasificar_por" class="form-group pt-3 text-left">
                                <div  class="icheck-primary d-inline ml-2">
                                    <input v-model="form.clasificar_por" type="radio" :value=null name="clasificacion" id="clasificacion1">
                                    <label class="pl-2">No clasificar</label>
                                </div>   
                            </div>  
                        </div>                  
                    </div>

                    <div v-show="form.clasificacion_reporte != 'generales'" class="row">
                        <div class="col">
                            <div  class="form-group pt-3">
                                <label>Ordenar por:</label>
                                <select v-model="form.ordenar_por" class="form-control" id="ordenar_por" name="ordenar_por" :class="{ 'is-invalid': form.errors.has('ordenar_por') }" required>
                                    <option selected value="biometrico_id">ID Biometrico</option>
                                    <option value="cedula">Número de cédula</option>
                                    <option value="nombre_completo">Apellidos y Nombres</option>
                                </select>
                                <has-error :form="form" field="ordenar_por"></has-error>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group pt-3">
                                <label>Empresa:</label>
                                <select v-model="form.empresas_id" class="form-control" id="empresas_id" name="empresas_id" :class="{ 'is-invalid': form.errors.has('empresas_id') }">
                                   <option value="" selected disabled>Seleccione un {{ this.form.tipo_reporte }}</option>
                                <option v-for="opcion in empresas" :value="opcion.id" :key="opcion.id">
                                    {{opcion.descripcion_empresa}}
                                </option>
                                </select>
                                <has-error :form="form" field="empresas_id"></has-error>
                            </div>
                        </div>
                    </div>
                    <div v-show="form.clasificacion_reporte != 'generales'" class="row">
                        <div class="col">
                            <div class="form-group pt-2">
                                <label>Fecha inicial</label>
                                <VueCtkDateTimePicker
                                v-model="form.fecha_inicial"
                                :only-date = true
                                :auto-close = true
                                :right = false
                                locale = "es"
                                format="YYYY-MM-DD"
                                formatted="l"
                                label="Seleccione la fecha inicial"
                                required
                                />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group pt-2 align-right">
                                <label>Fecha final</label>
                                <VueCtkDateTimePicker
                                v-model="form.fecha_final"
                                :only-date = true
                                :auto-close = true
                                :right = false
                                locale = "es"
                                format="YYYY-MM-DD"
                                formatted="l"
                                label="Seleccione la fecha final"
                                required
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                    <button v-show="!editmode" @click="loadArrayInfoReporte" class="btn btn-primary">Crear reporte</button>
                </div>

                </form>

                </div>
            </div>
            </div>
    </div>

</template>

<script>
    import Multiselect from 'vue-multiselect'
    import jsPDF from 'jspdf'
    import autoTable from 'jspdf-autotable'
    export default {
        components: {
        Multiselect,
        jsPDF
      },
        data() {
            return {
                editmode: false,
                reportes : {},
                empleados : {},
                auxiliar:[],
                doc: new jsPDF('l', 'mm', 'A4'),
                array_info_reporte: [], //Array de objetos donde estará la lista del reporte
                array_info_reporte_keys: [],
                opciones_select: [],
                opciones_clasificacion:[], //Quizás no se use mucho, solo guardará tipos de empleados o "incidencias" segun sea necesario
                empresas: [],
                tipo_empleados: [],
                incidencias: [], // Ni puta idea de qué es esto... Investigar
                departamentos: [],
                form: new Form({
                    id:'',
                    usuario : null,
                    clasificacion_reporte: null, //Movimientos, ausencias, retardos, permisos, Resumen General, etc
                    tipo_reporte: null, //Todos, Solo los de cierto departamento o un empleado individual
                    id_tipo_reporte: null,
                    clasificar_por: null, //[ 0 => Tipo de clasificacion, 1 => Opcion de clasificacion]
                    id_clasificar_por: null,
                    amplitud_reporte: "basico", //Cuánta información mostrará el reporte, se dividirá en "Básico", "Completo"
                    empresas_id: null,
                    ordenar_por: 'biometrico_id',
                    fecha_inicial:null,
                    fecha_final:null,
                })
            }
        },
        methods: {
            test(){
                //
            },
            loadArrayInfoReporte(){
                swal(
                    'Paciencia',
                    'Por favor espere a que los estadisticos sean calculados',
                    'info'
                    )
                this.$Progress.start();
                axios.get("api/crear_reporte/"
                    +this.form.usuario+"/"
                    +this.form.clasificacion_reporte+"/"
                    +this.form.tipo_reporte+"/"
                    +this.form.id_tipo_reporte+"/"
                    +this.form.clasificar_por+"/"
                    +this.form.id_clasificar_por+"/"
                    +this.form.amplitud_reporte+"/"
                    +this.form.ordenar_por+"/"
                    +this.form.fecha_inicial+"/"
                    +this.form.fecha_final+"/"
                    ).then(({ data }) =>{ 
                    this.array_info_reporte = data[0];
                    this.array_info_reporte_keys = data[1];
                    this.$Progress.finish();
                    /*
                    array_info_reporte_keys es un array que guarda una lista con todos los empleados con sus estadisticas en el plazo de tiempo acordado, identificandolo por su biometrico_id
                    Ej:  ["57", "148"...etc]
                    */
                    this.crearPDF();
                    /*swal({
                    title: '¿Guardar reporte?',
                    text: "¿Quieres guardar un registro del reporte?",
                    type: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, guárdalo',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.createReporte();
                         }
                    })*/
                    swal(
                    'Listo!',
                    'Los estadisticos han sido calculados exitosamente',
                    'success'
                    )
                    $('#addNew').modal('hide')
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                    swal("Algo ha salido mal", "Por favor, contacta al soporte técnico", "warning");
                });
            },
            iterarEstadisticas(item, index) {
                var ancho_de_pagina = this.doc.internal.pageSize.width;
                var largo_de_pagina = this.doc.internal.pageSize.height;
                var titulo_informe = "INFORME: "+this.form.clasificacion_reporte.toUpperCase()+ ' - ' +this.form.tipo_reporte.toUpperCase();
                var informacion_empleado = this.empleados[item];
                var fecha = new Date();
                let fecha_actual = fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear();
                //ARREGLAR LA FECHA Y HORA
                let hora_actual = fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds();
                var cabecera_tabla = [['ID', 'Fecha', 'Dia',' Estado', 'Hora Entrada', 'Hora Salida']]
                /*
                var img = new Image();
                img.src = "/img/logo_alcaldia.png"
                    0 = id
                    1 = cedula
                    2 = nombre
                    3 = cargo
                    4 = departamento
                    5 = tipo de empleado
                */

                if (this.form.amplitud_reporte === "completo") {
                    cabecera_tabla = [['ID', 'Fecha', 'Dia',' Estado', 'Ent', 'Sal', 'Diur', 'Noct', 'E.Ant', 'E.Des', 'S.Ant', 'S.Des', 'Tick']]
                };

                this.doc.autoTable({
                    margin:{
                        top:30
                    },
                    //startY=30,
                    theme: 'plain', 
                    head: cabecera_tabla,
                    body: this.array_info_reporte[item],

                    //Funcion que permite agregar información una vez formateada la tabla en la respectiva página, útil para cabeceras, pies de páginas e información adicional
                    didDrawPage: (data) => {
                    this.doc.setFontSize(12);
                    this.doc.setFont('helvetica','bold');
                    this.doc.setDrawColor(0,0,0);
                    this.doc.setLineWidth(0.5);
                    // TITULO INICIO
                    var imgLogo = new Image();
                    imgLogo.src = 'img/logo_alcaldia_mediano_2.png'
                    this.doc.addImage(imgLogo, 'JPEG', 6, 1, 30, 18); //shows correct image
                    this.doc.text("DIRECCION DE TALENTO HUMANO", ancho_de_pagina/2, 10, 'center')
                    this.doc.text(titulo_informe, ancho_de_pagina/2, 15, 'center')
                    this.doc.setFontSize(8);
                    this.doc.text("Desde: "+this.form.fecha_inicial+"   "+"Hasta: "+this.form.fecha_final, ancho_de_pagina-15, 10, 'right')
                    this.doc.text(informacion_empleado[5], ancho_de_pagina-10, 15, 'right')
                    // TITULO FIN
                    // INFO EMPLEADO INICIO
                    this.doc.line(5, 18, ancho_de_pagina-5, 18)
                    this.doc.setFontSize(8);
                    this.doc.setFont('helvetica','bold');
                    this.doc.text(
                        'ID: '+informacion_empleado[0]+'    '+
                        'No. C. I.: '+informacion_empleado[1]+'    '+
                        'Nombre: '+informacion_empleado[2]+'    '+
                        'Cargo: '+informacion_empleado[3]+'    '+
                        'Departamento: '+informacion_empleado[4]+'    '
                        ,ancho_de_pagina/2, 23.5, 'center')
                    this.doc.line(5, 27, ancho_de_pagina-5, 27)
                    // INFO EMPLEADO FIN
                    // PIE DE PAGINA INICIO
                    this.doc.setFontSize(8);
                    this.doc.setFont('helvetica','italic');
                    this.doc.text("Impreso por: "+this.form.usuario+" Fecha de impresión: "+fecha_actual+" Hora de impresión: "+hora_actual, 5, largo_de_pagina-5, 'left' )
                    //PIE DE PAGINA FIN
                    
                  },
                })
                let final_table_Y = this.doc.previousAutoTable.finalY;


                this.doc.line(5, final_table_Y+5, ancho_de_pagina-5, final_table_Y+5)
                this.doc.addPage();
                
            },
            crearPDF(){
                axios.get("api/usuario").then(({ data }) => (this.form.usuario = data));
                //Por cada biometrico_id en el array, has la funcion correspondiente usando como item el biometrico_id
                this.array_info_reporte_keys.forEach(this.iterarEstadisticas)
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                today = dd + '-' + mm + '-' + yyyy;
                //this.doc.output('dataurlnewwindow');
                this.doc.save("REPORTE_BIOMETRICO_MIA_"+today)
                this.doc = new jsPDF('l', 'mm', 'A4');
            },
            getResults(page = 1) {
                        axios.get('api/reporte?page=' + page)
                            .then(response => {
                                this.reportes = response.data;
                            });
                },
            updateReporte(){
                this.$Progress.start();
                this.form.put('api/reporte/'+this.form.id)
                .then(() => {
                    $('#addNew').modal('hide');
                     swal(
                        'Listo!',
                        'La información ha sido actualizada',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(reporte){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(reporte);
                axios.get("api/usuario").then(({ data }) => (this.form.usuario = data));
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                axios.get("api/usuario").then(({ data }) => (this.form.usuario = data));
                this.loadInfo();
                $('#addNew').modal('show');
            },
            deleteReporte(id){
                swal({
                    title: 'Estas segura/o?',
                    text: "No podras revertir esta acción!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, elimínalo',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.post('api/reporte').then(()=>{
                                        swal(
                                        'Creado!',
                                        'El reporte ha sido registrado exitosamente',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Error!", "Algo ha salido mal", "warning");
                                });
                         }
                    })
            },
            loadReporte(){
                axios.get("api/reporte").then(({ data }) => (this.reportes = data));
                axios.get("api/info_empleados").then(({ data }) => (this.empleados = data));
                
            },

            loadInfo(){
                //axios.get("api/info_empleados").then(({ data }) => (this.empleados = data));
                axios.get("api/empresa").then(({ data }) => (this.empresas = data.data));
                axios.get("api/tipo_empleado").then(({ data }) => (this.tipo_empleados = data.data));
                axios.get("api/tipo_permiso").then(({ data }) => (this.incidencias = data.data));
            },

            loadSelectOptions(){
                switch (this.form.tipo_reporte) {
                    case 'individual':
                        axios.get("api/empleado").then(({ data }) => (this.opciones_select = data));
                    break;

                    case 'departamento':
                        axios.get("api/departamento").then(({ data }) => (this.opciones_select = data.data));
                    break;
                    default:
                    //
                }
            },

            calcular(tipo){
                this.$Progress.start();
                axios.get("api/calcular/" + tipo + '/' + this.form.fecha_inicial + '/' + this.form.fecha_final)
                .then(()=>{
                //Fire.$emit('AfterCreate');
                //$('#addNew').modal('hide')

                toast({
                    type: 'success',
                       title: 'El reporte ha sido registrado exitosamente'
                       })
                this.$Progress.finish();

                })
                .catch(()=>{

                })
            },

            createReporte(){
                this.$Progress.start();

                this.form.post('api/reporte')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El reporte ha sido registrado exitosamente'
                        })
                    this.$Progress.finish();

                })
                .catch(()=>{

                })
            }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/buscar_reporte?q=' + query)
                .then((data) => {
                    this.reportes = data.data
                })
                .catch(() => {

                })
            })
           this.loadReporte();
           Fire.$on('AfterCreate',() => {
               this.loadReporte();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
