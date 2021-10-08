<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Empleados</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Nuevo <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <div class="input-group mb-3 col-12 pt-xl-4 pb-xl-4">
                        <input type="text" class="form-control" @keyup="busqueda_empleado()" v-model="buscar_empleado" id="buscar_empleado" placeholder="Buscar" aria-label="Buscar" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" id="button-addon2"><i class="fa fa-search white"></i></button>
                        </div>
                    </div>
                <b-table 
                    id="tabla_empleados"
                    :items="empleados"
                    :per-page="perPage"
                    :current-page="currentPage"
                    :fields="campos"
                >
                    <template #cell(Acciones)="row">
                        <td>
                        <a href="#" @click="editModal(row.item)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        -
                        <a href="#" @click="deleteEmpleado(row.item.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                      </template>
                </b-table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                    <b-pagination
                      v-model="currentPage"
                      :total-rows="rows"
                      :per-page="perPage"
                      aria-controls="tabla_empleados"
                    ></b-pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo empleado</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar descripción del empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateEmpleado() : createEmpleado()">
                <!-- <Modal Body> -->
                <div class="modal-body">
                    <div role="tabpanel">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#pestaña_datos_personales">Datos Personales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_nomina_horario">Datos de Nómina y Horario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_cesta_ticket">Cesta Ticket</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_especiales">Especiales</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-xl-2">

                            <!-- INICIO PESTAÑA DE DATOS PERSONALES-->
                            <div role="tabpanel" class="tab-pane show active" id="pestaña_datos_personales">
                                <div class="container pt-3">
                                <div class="row">
                                  <div class="col">
                                    <div class="form-group">
                                        <label>Nombre completo</label>
                                        <input v-model="form.nombre_completo" type="text" name="nombre_completo"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('nombre_completo') }">
                                        <has-error :form="form" field="nombre_completo"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Cédula</label>
                                        <input v-model="form.cedula" type="text" name="cedula"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('cedula') }">
                                        <has-error :form="form" field="cedula"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>ID Biométrico</label>
                                        <input v-model="form.biometrico_id" type="text" name="biometrico_id"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('biometrico_id') }">
                                        <has-error :form="form" field="biometrico_id"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Nacionalidad</label>
                                        <select v-model="form.nacionalidad" class="form-control" id="nacionalidad" name="nacionalidad" :class="{ 'is-invalid': form.errors.has('nacionalidad') }">
                                          <option value="venezolana">Venezolana</option>
                                          <option value="extranjera">Extranjera</option>
                                        </select>
                                        <has-error :form="form" field="nacionalidad"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Estado civil</label>
                                        <input v-model="form.estado_civil" type="text" name="estado_civil"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('estado_civil') }">
                                        <has-error :form="form" field="estado_civil"></has-error>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Estado de nacimiento</label>
                                        <input v-model="form.estado_nacimiento" type="text" name="estado_nacimiento"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('estado_nacimiento') }">
                                        <has-error :form="form" field="estado_nacimiento"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Condición del empleado</label>
                                        <select v-model="form.condicion_empleado" class="form-control" id="condicion_empleado" name="condicion_empleado" :class="{ 'is-invalid': form.errors.has('condicion_empleado') }">
                                          <option :value=true>Activo</option>
                                          <option :value=false>Inactivo</option>
                                        </select>
                                        <has-error :form="form" field="condicion_empleado"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Lugar de nacimiento</label>
                                        <input v-model="form.lugar_nacimiento" type="text" name="lugar_nacimiento"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('lugar_nacimiento') }">
                                        <has-error :form="form" field="lugar_nacimiento"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Profesión</label>
                                        <input v-model="form.profesion" type="text" name="profesion"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('profesion') }">
                                        <has-error :form="form" field="profesion"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Instrucción académica</label>
                                        <input v-model="form.instruccion_academica" type="text" name="instruccion_academica"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('instruccion_academica') }">
                                        <has-error :form="form" field="instruccion_academica"></has-error>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Teléfono fijo</label>
                                        <input v-model="form.telefono_fijo" type="text" name="telefono_fijo"
                                            class="form-control" placeholder="0000-0000000" :class="{ 'is-invalid': form.errors.has('telefono_fijo') }">
                                        <has-error :form="form" field="telefono_fijo"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Teléfono móvil</label>
                                        <input v-model="form.telefono_movil" type="text" name="telefono_movil"
                                            class="form-control" placeholder="0000-0000000" :class="{ 'is-invalid': form.errors.has('telefono_movil') }">
                                        <has-error :form="form" field="telefono_movil"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Correo electrónico</label>
                                        <input v-model="form.correo" type="text" name="correo"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('correo') }">
                                        <has-error :form="form" field="correo"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Género</label>
                                        <select v-model="form.sexo" class="form-control" id="sexo" name="sexo" :class="{ 'is-invalid': form.errors.has('sexo') }">
                                          <option value="masculino">Masculino</option>
                                          <option value="femenino">Femenino</option>
                                        </select>
                                        <has-error :form="form" field="sexo"></has-error>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            </div>
                            <!-- FINAL PESTAÑA DE DATOS PERSONALES-->

                            <!-- INICIO PESTAÑA DE SELECCIÓN DE NOMINA Y HORARIO -->
                            <div role="tabpanel" class="tab-pane fade" id="pestaña_nomina_horario">
                             <div class="container pt-3">
                                <div class="row">
                                  <div class="col">
                                    <div class="form-group">
                                        <label class="typo__label">Empresa</label>
                                        <multiselect v-model="form.empresas_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_empresa" placeholder="Selecciona una empresa" :options="empresas" :searchable="false" :allow-empty="false" :class="{ 'is-invalid': form.errors.has('empresas_id') }">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_empresa }}</strong> - <strong>  {{ option.subtitulo_reporte }}</strong></template>
                                        </multiselect>
                                        <has-error :form="form" field="empresas_id"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label class="typo__label">Cargo</label>
                                        <multiselect v-model="form.cargos_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_cargo" placeholder="Selecciona un cargo" :options="cargos" :searchable="false" :allow-empty="false" :class="{ 'is-invalid': form.errors.has('cargos_id') }">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_cargo }}</strong></template>
                                        </multiselect>
                                        <has-error :form="form" field="cargos_id"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label class="typo__label">Departamento</label>
                                        <multiselect v-model="form.departamentos_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_departamento" placeholder="Selecciona un departamento" :options="departamentos" :searchable="false" :allow-empty="false" :class="{ 'is-invalid': form.errors.has('departamentos_id') }">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_departamento }}</strong></template>
                                        </multiselect>
                                        <has-error :form="form" field="departamentos_id"></has-error>
                                    </div> 
                                    <div class="form-group">
                                        <label class="typo__label">Nóminas</label>
                                        <multiselect v-model="form.tipo_nominas_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_nomina" placeholder="Selecciona una nómina" :options="tipo_nominas" :searchable="false" :allow-empty="false" :class="{ 'is-invalid': form.errors.has('tipo_nominas_id') }">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_nomina }}</strong></template>
                                        </multiselect>
                                        <has-error :form="form" field="tipo_nominas_id"></has-error>
                                    </div> 
                                    <div class="form-group">
                                        <label class="typo__label">Tipo de empleado</label>
                                        <multiselect v-model="form.tipo_empleados_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_tipo_empleado" placeholder="Selecciona un tipo de empleado" :options="tipo_empleados" :searchable="false" :allow-empty="false" :class="{ 'is-invalid': form.errors.has('tipo_empleados_id') }">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_tipo_empleado }}</strong></template>
                                        </multiselect>
                                        <has-error :form="form" field="tipo_empleados_id"></has-error>
                                    </div> 
                                    <div class="form-group">
                                        <label class="typo__label">Horario</label>
                                        <multiselect v-model="form.horarios_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_horario" placeholder="Selecciona un horario" :options="horarios" :searchable="false" :allow-empty="false" :class="{ 'is-invalid': form.errors.has('horarios_id') }">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_horario }}</strong></template>
                                        </multiselect>
                                        <has-error :form="form" field="horarios_id"></has-error>
                                    </div> 
                                  </div>
                              </div>
                            </div>
                          </div>
                            <!-- FINAL PESTAÑA DE SELECCIÓN DE NOMINA Y HORARIO -->

                            <!-- INICIO PESTAÑA DE SELECCIÓN DE CESTA TICKET -->
                            <div role="tabpanel" class="tab-pane fade" id="pestaña_cesta_ticket">
                             <div class="container pt-3">
                                <div class="row">
                                  <div class="col">
                                    <div class="form-group">
                                        <label>Horas de la jornada laboral</label>
                                        <input v-model="form.jornada_laboral" type="text" name="jornada_laboral"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('jornada_laboral') }">
                                        <has-error :form="form" field="jornada_laboral"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Horas mínimas cesta ticket</label>
                                        <input v-model="form.horas_minimas_cesta_ticket" type="text" name="horas_minimas_cesta_ticket"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('horas_minimas_cesta_ticket') }">
                                        <has-error :form="form" field="horas_minimas_cesta_ticket"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Valor del ticket</label>
                                        <input v-model="form.valor_ticket" type="text" name="valor_ticket"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('valor_ticket') }">
                                        <has-error :form="form" field="valor_ticket"></has-error>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Código punto de venta</label>
                                        <input v-model="form.codigo_punto_venta" type="text" name="codigo_punto_venta"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('codigo_punto_venta') }">
                                        <has-error :form="form" field="codigo_punto_venta"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Prorratear Cesta Ticket</label>
                                        <select v-model="form.prorratear_cesta_ticket" class="form-control" id="prorratear_cesta_ticket" name="prorratear_cesta_ticket" :class="{ 'is-invalid': form.errors.has('prorratear_cesta_ticket') }">
                                          <option :value=true>Si</option>
                                          <option :value=false>No</option>
                                        </select>
                                        <has-error :form="form" field="prorratear_cesta_ticket"></has-error>
                                    </div>
                                    <div v-if="$gate.isAdminOrAuthor()"class="form-group">
                                        <label>Confianza</label>
                                        <select v-model="form.confianza" class="form-control" id="confianza" name="confianza" :class="{ 'is-invalid': form.errors.has('confianza') }">
                                          <option selected :value=false>No</option>
                                          <option :value=true>Si</option>
                                        </select>
                                        <has-error :form="form" field="confianza"></has-error>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                            <!-- FINAL PESTAÑA DE SELECCIÓN DE CESTA TICKET -->

                            <!-- INICIO PESTAÑA DE SELECCIÓN DE ESPECIALES -->
                            <div role="tabpanel" class="tab-pane fade" id="pestaña_especiales">
                             <div class="container pt-3">
                                <div class="row">
                                  <div class="col">
                                    <div class="text-left">                                       
                                    <label class="text-primary">Pago de horas extra</label>
                                    </div>
                                    <div class="form-group pt-3 text-left">
                                        <div  class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" :value=true name="todo1" id="todoCheck1">
                                        </div>   
                                        <label class="pl-2">Entradas antes</label>
                                    </div> 
                                    <div class="form-group text-left">
                                        <div  class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" :value=true name="todo1" id="todoCheck1">
                                        </div>   
                                        <label class="pl-2">Salidas tardes</label>
                                    </div> 
                                    <div class="text-left">                                       
                                    <label class="text-primary">Horario de presencias</label>
                                    </div>
                                    <div class="form-group pt-3 text-left">
                                        <div  class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" :value=true name="todo1" id="todoCheck1">
                                        </div>   
                                        <label class="pl-2">Solo para marcar entrada/salida</label>
                                    </div>
                                    <div class="text-left">                                       
                                    <label class="text-primary">Salidas Nocturnas</label>
                                    </div>
                                    <div class="form-group pt-3 text-left">
                                        <div  class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" :value=true v-model="form.salida_nocturna" name="todo1" id="todoCheck1">
                                        </div>   
                                        <label class="pl-2">Permitir</label>
                                    </div>
                                    <div v-show="form.salida_nocturna">
                                        <div class="form-group">
                                            <label>Desde:</label>
                                            <vue-timepicker v-model="form.inicio_salida_nocturna" :minute-interval="5" format="HH:mm:ss" close-on-complete></vue-timepicker>
                                        </div>
                                        <div class="form-group">
                                            <label>Hasta:</label>
                                            <vue-timepicker v-model="form.fin_salida_nocturna" :minute-interval="5" format="HH:mm:ss" close-on-complete></vue-timepicker>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="text-left">                                       
                                    <label class="text-primary">Marcaje hora de descanso</label>
                                    </div>
                                    <div class="form-group pt-3 text-left">
                                        <div  class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" :value=true v-model="form.descontar_horas_descanso" name="todo1" id="todoCheck1">
                                        </div>   
                                        <label class="pl-2">Descontar horas de descanso</label>
                                    </div>
                                    <div class="form-group pt-3 text-left">
                                        <div  class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" :value=true v-model="form.marca_rango_comida" name="todo1" id="todoCheck1">
                                        </div>   
                                        <label class="pl-2">Marca por rango de comida</label>
                                        <div class="form-group pt-3">
                                            <label>Duración del rango:</label>
                                            <vue-timepicker v-model="form.duracion_rango_comida" format="mm" close-on-complete></vue-timepicker>
                                        </div>
                                    </div>
                                    <div class="text-left">                                       
                                    <label class="text-primary">Cesta tickets</label>
                                    </div>
                                    <div class="form-group pt-3 text-left">
                                        <div  class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" :value=true v-model="form.cesta_sabado_domingo_rango" name="todo1" id="todoCheck1">
                                        </div>   
                                        <label class="pl-2">Pagar sábados y domingos por rango</label>
                                        <div class="form-group pt-3">
                                            <label>Duración del rango:</label>
                                            <vue-timepicker v-model="form.duracion_rango_cesta" format="mm" close-on-complete></vue-timepicker>
                                        </div>
                                    </div>                                                         
                                  </div>
                              </div>
                            </div>
                          </div>
                            <!-- FINAL PESTAÑA DE SELECCIÓN DE ESPECIALES -->
                        </div>
                    </div>                           
                </div>
                <!-- </Modal Body> -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Crear</button>
                </div>

                </form>

                </div>
            </div>
            </div>
    </div>



</template>

<script>
    import Multiselect from 'vue-multiselect'
    import VueTimepicker from 'vue2-timepicker'

    export default {
        components: {
            Multiselect,
            VueTimepicker
      },
        data() {
            return {
                perPage: 10,
                currentPage: 1,
                campos: [
                  {
                    key: 'nombre_completo',
                    label: 'Nombre',
                    sortable: true
                  },
                  {
                    key: 'cedula',
                    label: 'Cedula',
                    sortable: true,
                  },
                  {
                    key: 'condicion_empleado',
                    label: 'Condicion del Empleado',
                    sortable: true,
                  },
                  {
                    key: 'biometrico_id',
                    label: 'ID Biometrico',
                    sortable: true,
                  },
                  'Acciones'
                ],
                editmode: false,
                empleados :[],
                empresas: [],
                cargos: [],
                departamentos: [],
                tipo_empleados: [],
                tipo_nominas: [],
                horarios: [], 
                buscar_empleado: '',
                form: new Form({
                    id:'',
                    nombre_completo : '',
                    cedula : '',
                    biometrico_id : '',
                    nacionalidad : '',
                    estado_civil : '',  
                    estado_nacimiento : '',
                    lugar_nacimiento : '',
                    instruccion_academica : '',
                    profesion : '',
                    telefono_fijo : '',
                    telefono_movil : '',
                    correo : '',
                    sexo: '',
                    condicion_empleado : true,
                    empresas_id : '',
                    cargos_id : '',
                    departamentos_id : '',
                    tipo_empleados_id : '',
                    tipo_nominas_id : '',
                    horarios_id : '',
                    jornada_laboral : '8',
                    horas_minimas_cesta_ticket : '6',
                    valor_ticket : '',
                    codigo_punto_venta : '',
                    prorratear_cesta_ticket : false,
                    pago_horas_extra_antes : false,
                    pago_horas_extra_despues : false,
                    marcar_entrada_salida : false,
                    salida_nocturna : false,
                    inicio_salida_nocturna : '00:00:00',
                    fin_salida_nocturna : '00:00:00',
                    descontar_horas_descanso : false,
                    marca_rango_comida : false,
                    duracion_rango_comida : '00',
                    cesta_sabado_domingo_rango : false,
                    duracion_rango_cesta : '00',
                    confianza: false,
                })
            }
        },
        computed: {
          rows() {
            return this.empleados.length
          }
        },
        methods: {

            cargarObjetosDatosNominaHorario(id){
                axios.get("api/empleado_datos_nomina_horario/"+id).then(({ data }) => {
                    this.form.empresas_id = data['empresa'];
                    this.form.cargos_id = data['cargo'];
                    this.form.departamentos_id = data['departamento'];
                    this.form.tipo_empleados_id = data['tipo_empleado'];
                    this.form.tipo_nominas_id = data['nominas'];
                    this.form.horarios_id = data['horario'];
                });
            },

            dynamicDropdown(url, data){
            axios.get(url)
            .then(response => this[data] = response.data.data)
            .catch(error => console.log(error))
            },

            cargarDropdowns(){
            let cargo = 'api/cargo';
            this.dynamicDropdown(cargo, 'cargos')
            let departamento = 'api/departamento';
            this.dynamicDropdown(departamento, 'departamentos')
            let nomina = 'api/nomina';
            this.dynamicDropdown(nomina, 'tipo_nominas')
            let tipoEmpleado = 'api/tipo_empleado';
            this.dynamicDropdown(tipoEmpleado, 'tipo_empleados')
            let Empresa = 'api/empresa';
            this.dynamicDropdown(Empresa, 'empresas')
            let Horario = 'api/horario';
            this.dynamicDropdown(Horario, 'horarios')
            },

            getResults(page = 1) {
                        axios.get('api/empleado?page=' + page)
                            .then(response => {
                                this.empleados = response.data;
                            });
                },
            updateEmpleado(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/empleado/'+this.form.id)
                .then(() => {
                    // success
                    $('#addNew').modal('hide');
                     swal(
                        'Listo!',
                        'La información ha sido actualizada exitosamente',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            busqueda_empleado(){
                if(this.buscar_empleado != ''){
                    axios.get('api/buscar_empleado/' + this.buscar_empleado )
                    .then(({data})=>(this.empleados=data));
                }
            },
            editModal(empleado){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(empleado);
                this.cargarObjetosDatosNominaHorario(this.form.id)
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
                $('#addNew').modal('handleUpdate');
            },
            deleteEmpleado(id){
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
                                this.form.delete('api/empleado/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El empleado ha sido removido exitosamente',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal. !", " Por favor contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },
            loadEmpleado(){
                    axios.get("api/empleado").then(({ data }) => (this.empleados = data));
            },

            createEmpleado(){
                this.$Progress.start();

                this.form.post('api/empleado')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El empleado ha sido registrado exitosamente'
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
                axios.get('api/buscar_nomina?q=' + query)
                .then((data) => {
                    this.empleados = data.data
                })
                .catch(() => {

                })
            })
           this.loadEmpleado();
           this.cargarDropdowns()
           Fire.$on('AfterCreate',() => {
               this.loadEmpleado();
               this.cargarDropdowns()
               this.buscar_empleado = '';
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
