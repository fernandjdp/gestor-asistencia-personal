<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div v-if="$gate.isAdminOrAuthor()" class="card">
              <div class="card-header">
                <h3 class="card-title">Estadísticos</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Nuevo <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <b-table 
                    id="tabla_estadisticos"
                    :items="estadisticos"
                    :per-page="perPage"
                    :current-page="currentPage"
                    :fields="campos"
                ></b-table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                    <b-pagination
                      v-model="currentPage"
                      :total-rows="rows"
                      :per-page="perPage"
                      aria-controls="tabla_estadisticos"
                    ></b-pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div v-if="$gate.isUser()" class="card">
              <div class="card-header">
                <h3 class="card-title">Estadísticos</h3>
              </div>
              <div class="card-body p-0">
                <form @submit.prevent="editmode ? test() : test()">
                <div class="modal-body">
                     <div role="tabpanel">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#pestaña_ausencias">Ausencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_estadisticos">Estadísticos</a>
                            </li>
                            <!-- Preguntar si esto es necesario -->
                            <!--<li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_global">Recalcular global</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_individual">Recalcular individual</a>
                            </li> -->
                        </ul>
                        <div class="tab-content pt-xl-1">

                            <!-- INICIO PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->
                            <div role="tabpanel" class="tab-pane active" id="pestaña_ausencias">
                                <div class="form-group p-2">
                                    <label>Empresa:</label>
                                    <multiselect v-model="form.empresas_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_empresa" placeholder="Selecciona una empresa" :options="empresas" :searchable="true" :allow-empty="true">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_empresa}}</strong></template>
                                        </multiselect>
                                </div>
                                <div class="form-group p-2">
                                    <label>Fecha inicial</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_inicial"
                                    :only-date = true
                                    :auto-close = true
                                    :right = false
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="form-group p-2">
                                     <label>Fecha final</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_final"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="pl-2">
                                <button class="btn btn-success" @click="calcular('ausencia')">Calcular<i class="fas fa-user-plus fa-fw"></i></button>
                                </div>
                            </div>
                            <!-- FINAL PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->

                            <!-- INICIO PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->
                            <div role="tabpanel" class="tab-pane" id="pestaña_estadisticos">
                                <div class="form-group p-2">
                                    <label>Empresa:</label>
                                    <multiselect v-model="form.empresas_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_empresa" placeholder="Selecciona una empresa" :options="empresas" :searchable="false" :allow-empty="false">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_empresa}}</strong></template>
                                        </multiselect>
                                </div>
                                <div class="form-group p-2">
                                    <label>Fecha inicial</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_inicial"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="form-group p-2">
                                     <label>Fecha final</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_final"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="pl-2">
                                <button class="btn btn-success" @click="calcular('estadistico')">Calcular<i class="fas fa-user-plus fa-fw"></i></button>
                                </div>
                            </div>
                            <!-- FINAL PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </form>
              </div>
              <div class="card-footer">
              </div>
            </div>

    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nueva Estadística</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar Estadística</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? test() : test()">
                <div class="modal-body">
                     <div role="tabpanel">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#pestaña_ausencias">Ausencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_estadisticos">Estadísticos</a>
                            </li>
                            <!-- Preguntar si esto es necesario -->
                            <!--<li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_global">Recalcular global</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_individual">Recalcular individual</a>
                            </li> -->
                        </ul>
                        <div class="tab-content pt-xl-1">

                            <!-- INICIO PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->
                            <div role="tabpanel" class="tab-pane active" id="pestaña_ausencias">
                                <div class="form-group p-2">
                                    <label>Empresa:</label>
                                    <multiselect v-model="form.empresas_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_empresa" placeholder="Selecciona una empresa" :options="empresas" :searchable="true" :allow-empty="true">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_empresa}}</strong></template>
                                        </multiselect>
                                </div>
                                <div class="form-group p-2">
                                    <label>Fecha inicial</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_inicial"
                                    :only-date = true
                                    :auto-close = true
                                    :right = false
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="form-group p-2">
                                     <label>Fecha final</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_final"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="pl-2">
                                <button class="btn btn-success" @click="calcular('ausencia')">Calcular<i class="fas fa-user-plus fa-fw"></i></button>
                                </div>
                            </div>
                            <!-- FINAL PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->

                            <!-- INICIO PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->
                            <div role="tabpanel" class="tab-pane" id="pestaña_estadisticos">
                                <div class="form-group p-2">
                                    <label>Empresa:</label>
                                    <multiselect v-model="form.empresas_id" deselect-label="Can't remove this value" track-by="id" label="descripcion_empresa" placeholder="Selecciona una empresa" :options="empresas" :searchable="false" :allow-empty="false">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.descripcion_empresa}}</strong></template>
                                        </multiselect>
                                </div>
                                <div class="form-group p-2">
                                    <label>Fecha inicial</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_inicial"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="form-group p-2">
                                     <label>Fecha final</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_final"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="pl-2">
                                <button class="btn btn-success" @click="calcular('estadistico')">Calcular<i class="fas fa-user-plus fa-fw"></i></button>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="pestaña_global">
                                <div class="form-group p-2">
                                    <label>Fecha inicial</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_inicial"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="form-group p-2">
                                     <label>Fecha final</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_final"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="pl-2">
                                <button class="btn btn-success" @click="calcular('recalculo_global')">Calcular<i class="fas fa-user-plus fa-fw"></i></button>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="pestaña_individual">
                                <div class="form-group p-2">
                                    <label>Empleado:</label>
                                    <multiselect v-model="form.empleados_id" track-by="id" label="nombre_completo" placeholder="Selecciona una empresa" :options="empleados" :searchable="true" :allow-empty="true">
                                        <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.nombre_completo}}</strong></template>
                                        </multiselect>
                                </div>
                                <div class="form-group p-2">
                                    <label>Fecha inicial</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_inicial"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="form-group p-2">
                                     <label>Fecha final</label>
                                    <VueCtkDateTimePicker
                                    v-model="form.fecha_final"
                                    :only-date = true
                                    :auto-close = true
                                    :right = true
                                    locale = "es"
                                    format="YYYY-MM-DD"
                                    formatted="l"
                                    label="Seleccione la fecha"
                                    />
                                </div>
                                <div class="pl-2">
                                <button class="btn btn-success" @click="calcular('recalculo_individual')">Calcular<i class="fas fa-user-plus fa-fw"></i></button>
                                </div>
                            </div>
                            <!-- FINAL PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </form>
                </div>
            </div>
            </div>
    </div>

</template>

<script>
    import Multiselect from 'vue-multiselect'
    export default {
        components: {
        Multiselect,
      },
        data() {
            return {
                perPage: 10,
                currentPage: 1,
                campos: [
                  {
                    key: 'id',
                    label: 'Clave',
                    sortable: true
                  },
                  {
                    key: 'empleado_id',
                    label: 'ID Empleado',
                    sortable: true,
                  },
                  {
                    key: 'fecha',
                    label: 'Fecha',
                    sortable: true,
                  },
                  {
                    key: 'dia',
                    label: 'Dia',
                    sortable: true,
                  },
                  {
                    key: 'estado_asistencia',
                    label: 'Estado Asistencia',
                    sortable: true,
                  },
                  {
                    key: 'hora_entra',
                    label: 'Hora Entrada',
                    sortable: true,
                  },
                  {
                    key: 'hora_sale',
                    label: 'Hora Salida',
                    sortable: true,
                  },
                ],
                editmode: false,
                estadisticos : {},
                empresas: [],
                empleados: [],
                memes: '',
                form: new Form({
                    id:'',
                    empresas_id : '',
                    fecha_inicial:'',
                    fecha_final:'',
                })
            }
        },
        computed: {
          rows() {
            return this.estadisticos.length
          }
        },
        methods: {
            test(){
                //
            },
            getResults(page = 1) {
                        axios.get('api/estadistico?page=' + page)
                            .then(response => {
                                this.estadisticos = response.data;
                            });
                },
            updateEstadistico(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/estadistico/'+this.form.id)
                .then(() => {
                    // success
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
            editModal(estadistico){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(estadistico);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteEstadistico(id){
                swal({
                    title: 'Estas segura/o?',
                    text: "No podras revertir esta acción!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, elimínalo'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/estadistico/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El estadistico ha sido eliminado de la lista',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Error!", "Algo ha salido mal", "warning");
                                });
                         }
                    })
            },
            loadEstadistico(){
                axios.get("api/estadistico").then(({ data }) => (this.estadisticos = data));
            },

            loadMultiselectOptions(){
                    axios.get("api/empresa").then(({ data }) => (this.empresas = data.data));
                    axios.get("api/empleado").then(({ data }) => (this.empleados = data.data));
            },


            calcular(tipo){
                swal(
                    'Paciencia',
                    'Por favor espere a que los estadisticos sean calculados',
                    'info'
                    )
                this.$Progress.start();
                axios.get("api/calcular/" + tipo + '/' + this.form.fecha_inicial + '/' + this.form.fecha_final)
                .then(()=>{
                Fire.$emit('AfterCreate');
                $('#addNew').modal('hide')
                swal(
                    'Listo!',
                    'Los estadisticos han sido calculados exitosamente',
                    'success'
                    )
                this.$Progress.finish();

                })
                .catch(()=>{

                })
            },

            createEstadistico(){
                this.$Progress.start();

                this.form.post('api/estadistico')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El estadistico ha sido registrado exitosamente'
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
                    this.estadisticos = data.data
                })
                .catch(() => {

                })
            })
           this.loadEstadistico();
           this.loadMultiselectOptions();
           Fire.$on('AfterCreate',() => {
               this.loadEstadistico();
               this.loadMultiselectOptions();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
