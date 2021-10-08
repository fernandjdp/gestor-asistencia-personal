<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Horarios</h3>

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
                        <th>Descripcion</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Inicio descanso</th>
                        <th>Fin descanso</th>
                        <th>Acciones</th>
                  </tr>


                  <tr v-for="horario in horarios.data" :key="horario.id">

                    <td v-if="$gate.isAdminOrAuthor()">{{horario.id}}</td>
                    <td>{{horario.descripcion_horario}}</td>
                    <td>{{horario.hora_entrada}}</td>
                    <td>{{horario.hora_salida}}</td>
                    <td>{{horario.inicio_descanso}}</td>
                    <td>{{horario.fin_descanso}}</td>
                    <td>
                        <a href="#" @click="editModal(horario)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        -
                        <a href="#" @click="deleteHorario(horario.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="horarios" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo horario</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar horario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateHorario() : createHorario()">
                <div class="modal-body">
                    <div role="tabpanel">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#pestaña_descripcion">Descripción y Dias laborables</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pestaña_horarios">Horarios y Tolerancias</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-xl-2">

                            <!-- INICIO PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->
                            <div role="tabpanel" class="tab-pane active" id="pestaña_descripcion">
                                <div class="form-group">
                                    <label class="typo__label pt-4">Descripción</label>
                                    <input v-model="form.descripcion_horario" type="text" name="descripcion_horario" placeholder="Descripción del horario"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion_horario') }">
                                    <has-error :form="form" field="descripcion_horario"></has-error>
                                </div>
                                <div>
                                  <label class="typo__label">Dias laborables</label>
                                  <multiselect id="dias_laborables" v-model="form.dias_laborables" :options="dias" @close="DiasRestantes" @remove="DiasRemovidos" :searchable="false" :multiple="true" :close-on-select="false" :show-labels="true" placeholder="Dias laborables" :class="{ 'is-invalid': form.errors.has('dias_laborables') }"></multiselect>
                                  <has-error :form="form" field="dias_laborables"></has-error>
                                </div>
                                <div>
                                  <label class="typo__label">Dias no laborables</label>
                                  <multiselect v-model="form.dias_no_laborables" :options="dias" @close="DiasRestantes" @remove="DiasRemovidos" :searchable="false" :multiple="true" :close-on-select="false" :show-labels="true" placeholder="Dias no laborables" :class="{ 'is-invalid': form.errors.has('dias_no_laborables') }"></multiselect>
                                  <has-error :form="form" field="dias_no_laborables"></has-error>
                                </div>
                                <div>
                                  <label class="typo__label">OP</label>
                                  <multiselect v-model="form.dias_operativos" :options="dias" @close="DiasRestantes" @remove="DiasRemovidos" :searchable="false" :multiple="true" :close-on-select="false" :show-labels="true" placeholder="Dias operativos" :class="{ 'is-invalid': form.errors.has('dias_operativos') }"></multiselect>
                                  <has-error :form="form" field="dias_operativos"></has-error>
                                </div>
                            </div>
                            <!-- FINAL PESTAÑA DE DESCRIPCION Y DIAS LABORABLES-->

                            <!-- INICIO PESTAÑA DE SELECCIÓN DE HORARIOS -->
                            <div role="tabpanel" class="tab-pane" id="pestaña_horarios">
                             <div class="container">
                                <div class="row">
                                  <div class="col">
                                    <label class="text-primary pt-3">Horarios</label>
                                    <div class="form-group">
                                        <label>Hora de Entrada Laboral:</label>
                                        <vue-timepicker v-model="form.hora_entrada" :minute-interval="5" format="HH:mm:ss" @change="CambioHoraToleranciaEntradaAntes" close-on-complete :class="{ 'is-invalid': form.errors.has('hora_entrada') }"></vue-timepicker>
                                        <has-error :form="form" field="hora_entrada"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Hora de Salida Laboral:</label>
                                        <div></div>
                                        <vue-timepicker v-model="form.hora_salida" :minute-interval="5" format="HH:mm:ss" @change="CambioHoraToleranciaHorasExtra" close-on-complete :class="{ 'is-invalid': form.errors.has('hora_salida') }"></vue-timepicker>
                                        <has-error :form="form" field="hora_salida"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Hora de Inicio de Descanso:</label>
                                        <vue-timepicker v-model="form.inicio_descanso" :minute-interval="5" format="HH:mm:ss" close-on-complete :class="{ 'is-invalid': form.errors.has('inicio_descanso') }"></vue-timepicker>
                                        <has-error :form="form" field="inicio_descanso"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Hora de Fin de Descanso:</label>
                                        <vue-timepicker v-model="form.fin_descanso" :minute-interval="5" format="HH:mm:ss"@change="CambioHoraToleranciaDescansoFin" close-on-complete :class="{ 'is-invalid': form.errors.has('fin_descanso') }"></vue-timepicker>
                                        <has-error :form="form" field="fin_descanso"></has-error>
                                    </div>
                                  </div>
                                  <div class="col">
                                    <label class="text-primary pt-3">Tolerancias laborables</label>
                                    <div class="form-group">
                                        <label>Entrada antes de la hora:</label>
                                        <vue-timepicker v-model="form.tolerancia_entrada_antes" :minute-interval="5" format="HH:mm:ss" close-on-complete :class="{ 'is-invalid': form.errors.has('tolerancia_entrada_antes') }"></vue-timepicker>
                                        <has-error :form="form" field="tolerancia_entrada_antes"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Entrada despues de la hora:</label>
                                        <vue-timepicker v-model="form.tolerancia_entrada_despues" :minute-interval="5" format="HH:mm:ss" close-on-complete :class="{ 'is-invalid': form.errors.has('tolerancia_entrada_despues') }"></vue-timepicker>
                                        <has-error :form="form" field="tolerancia_entrada_despues"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Margen para horas extra:</label>
                                        <vue-timepicker v-model="form.tolerancia_salida_horas_extra" :minute-interval="5" format="HH:mm:ss" close-on-complete :class="{ 'is-invalid': form.errors.has('tolerancia_salida_horas_extra') }"></vue-timepicker>
                                        <has-error :form="form" field="tolerancia_salida_horas_extra"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Entrada despues: hora de descanso</label>
                                        <vue-timepicker v-model="form.tolerancia_descanso_fin" :minute-interval="5" format="HH:mm:ss" close-on-complete :class="{ 'is-invalid': form.errors.has('tolerancia_descanso_fin') }"></vue-timepicker>
                                        <has-error :form="form" field="tolerancia_descanso_fin"></has-error>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                            <!-- FINAL PESTAÑA DE SELECCIÓN DE HORARIOS -->
                        </div>
                    </div>                           
                </div> 
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
                editmode: false,
                horarios : {},
                dias:["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"],
                dias_removidos: [],
                aux_tolerancia_ea:'HH:mm:00',
                aux_tolerancia_ed:'HH:mm:00',
                aux_tolerancia_she:'HH:mm:00',
                aux_tolerancia_df:'HH:mm:00',

                form: new Form({
                    id:'',
                    descripcion_horario: '',
                    dias_laborables: null,
                    dias_no_laborables: null,
                    dias_operativos: null,
                    dias_hrs: null,
                    hora_entrada: 'HH:mm:00',
                    hora_salida: 'HH:mm:00',
                    inicio_descanso: 'HH:mm:00',
                    fin_descanso: 'HH:mm:00',
                    tolerancia_entrada_antes: 'HH:45:00',
                    tolerancia_entrada_despues: 'HH:15:00',
                    tolerancia_salida_horas_extra: 'HH:30:00',
                    tolerancia_descanso_fin: 'HH:15:00'
                })
            }
        },
        methods: {
            DiasRestantes(dias){
                this.dias = this.dias.filter( function ( el ){
                    return dias.indexOf( el ) < 0;
                });
            },

            DiasRemovidos(dias){
                if (this.dias.indexOf(dias) === -1) {
                    this.dias.unshift(dias)
                }
            },

            CambioHoraToleranciaEntradaAntes(){
                // Todo lo que hace desde AQUI 
                var tolerancia_str_to_int = this.form.hora_entrada.substring(1,2)
                var auxiliar_entrada_despues = tolerancia_str_to_int - 1
                var tolerancia_int_to_str = '0' + parseInt(auxiliar_entrada_despues) + ':'
                // Hasta AQUI es pasar el formato de hora_entrada de string a int, restarle 1, y volver a pasarlo a string

                this.form.tolerancia_entrada_antes = this.form.tolerancia_entrada_antes.replace('HH:', tolerancia_int_to_str)
                this.form.tolerancia_entrada_despues= this.form.tolerancia_entrada_despues.replace('HH', this.form.hora_entrada.substring(0,2))

            },

            CambioHoraToleranciaHorasExtra(){

                this.form.tolerancia_salida_horas_extra= this.form.tolerancia_salida_horas_extra.replace('HH', this.form.hora_salida.substring(0,2))

            },
            CambioHoraToleranciaDescansoFin(){

                this.form.tolerancia_descanso_fin= this.form.tolerancia_descanso_fin.replace('HH', this.form.fin_descanso.substring(0,2))

            },
            getResults(page = 1) {
                        axios.get('api/horario?page=' + page)
                            .then(response => {
                                this.horarios = response.data;
                            });
                },
            updateHorario(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/horario/'+this.form.id)
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
            editModal(horario){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(horario);
                this.loadDiasSerialized(this.form.id);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteHorario(id){
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
                                this.form.delete('api/horario/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El horario ha sido eliminado exitosamente ',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal. !", " Por favor contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },

            loadDiasSerialized(id){
                axios.get("api/horario_serialize/"+id)
                .then(({ data }) => {
                    this.form.dias_laborables = data['dias_laborables'];
                    this.form.dias_no_laborables = data['dias_no_laborables'];
                    this.form.dias_operativos = data['dias_operativos'];
                });
            },

            loadHorario(){
                axios.get("api/horario").then(({ data }) => (this.horarios = data));
            },

            createHorario(){
                this.$Progress.start();
                this.form.post('api/horario')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El dia festivo ha sido registrado exitosamente'
                        })
                    this.$Progress.finish();
                    this.form.reset();


                })
                .catch(()=>{

                })
            }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/buscar_horario?q=' + query)
                .then((data) => {
                    this.horarios = data.data
                })
                .catch(() => {

                })
            })
           this.loadHorario();
           Fire.$on('AfterCreate',() => {
               this.loadHorario();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
