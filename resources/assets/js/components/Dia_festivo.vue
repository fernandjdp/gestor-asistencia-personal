<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Dias Festivos</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Nuevo <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción del día</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                  </tr>


                  <tr v-for="dia_festivo in dia_festivos.data" :key="dia_festivo.id">

                    <td>{{dia_festivo.id}}</td>
                    <td>{{dia_festivo.descripcion_dia_festivo}}</td>
                    <td>{{dia_festivo.fecha}}</td>
                    <td>{{dia_festivo.tipo}}</td>
                    <td>
                        <a href="#" @click="editModal(dia_festivo)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        -
                        <a href="#" @click="deleteDiaFestivo(dia_festivo.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="dia_festivos" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo dia festivo</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar descripción de dia festivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateDiaFestivo() : createDiaFestivo()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.descripcion_dia_festivo" type="text" name="descripcion_dia_festivo"
                            placeholder="Descripcion del día"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion_dia_festivo') }">
                        <has-error :form="form" field="descripcion_dia_festivo"></has-error>
                    </div>
                    <div class="form-group">
                        <VueCtkDateTimePicker
                        v-model="form.fecha"
                        :only-date = true
                        :auto-close = true
                        :right = true
                        locale = "es"
                        format="MM-DD"
                        formatted="ll"
                        label="Seleccione la fecha"
                        :class="{ 'is-invalid': form.errors.has('fecha') }"
                        />
                        <has-error :form="form" field="fecha"></has-error>
                    </div>
                    <div class="form-group">
                        <select v-model="form.tipo" class="form-control" id="tipo" name="tipo" placeholder="Tipo" :class="{ 'is-invalid': form.errors.has('tipo') }">
                            <option value="" selected disabled>Seleccione el tipo de dia festivo</option>
                            <option value="POR LA EMPRESA">POR LA EMPRESA</option>
                            <option value="POR CONTRATO">POR CONTRATO</option>
                            <option value="OFICIAL">OFICIAL</option>
                        </select>
                        <has-error :form="form" field="tipo"></has-error>
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
    export default {
        data() {
            return {
                editmode: false,
                only_date: false,
                dia_festivos : {},
                form: new Form({
                    id:'',
                    descripcion_dia_festivo : '',
                    fecha : '',
                    tipo : ''
                })
            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/dia_festivo?page=' + page)
                            .then(response => {
                                this.dia_festivos = response.data;
                            });
                },
            updateDiaFestivo(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/dia_festivo/'+this.form.id)
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
            editModal(dia_festivo){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(dia_festivo);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteDiaFestivo(id){
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
                                this.form.delete('api/dia_festivo/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El dia festivo ha sido eliminado exitosamente',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal. !", " Por favor contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },

            loadDiaFestivo(){
                axios.get("api/dia_festivo").then(({ data }) => (this.dia_festivos = data));
            },

            createDiaFestivo(){
                this.$Progress.start();
                this.form.post('api/dia_festivo')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El dia festivo ha sido registrado exitosamente'
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
                axios.get('api/buscar_dia_festivo?q=' + query)
                .then((data) => {
                    this.dia_festivos = data.data
                })
                .catch(() => {

                })
            })
           this.loadDiaFestivo();
           Fire.$on('AfterCreate',() => {
               this.loadDiaFestivo();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
