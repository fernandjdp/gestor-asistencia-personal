<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tipo de permiso</h3>

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
                        <th>Descripción</th>
                        <th>Acciones</th>
                  </tr>


                  <tr v-for="tipo_permiso in tipo_permisos.data" :key="tipo_permiso.id">

                    <td v-if="$gate.isAdminOrAuthor()">{{tipo_permiso.id}}</td>
                    <td>{{tipo_permiso.descripcion_tipo_permiso}}</td>
                    <td>
                        <a href="#" @click="editModal(tipo_permiso)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        -
                        <a href="#" @click="deleteTipoPermiso(tipo_permiso.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="tipo_permisos" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo tipo de permiso</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar descripción de permiso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateTipoPermiso() : createTipoPermiso()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.descripcion_tipo_permiso" type="text" name="descripcion_tipo_permiso"
                            placeholder="Descripcion del permiso"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion_tipo_permiso') }">
                        <has-error :form="form" field="descripcion_tipo_permiso"></has-error>
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
                tipo_permisos : {},
                form: new Form({
                    id:'',
                    descripcion_tipo_permiso : ''
                })
            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/tipo_permiso?page=' + page)
                            .then(response => {
                                this.tipo_permisos = response.data;
                            });
                },
            updateTipoPermiso(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/tipo_permiso/'+this.form.id)
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
            editModal(tipo_permiso){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(tipo_permiso);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteTipoPermiso(id){
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
                                this.form.delete('api/tipo_permiso/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'La información ha sido eliminada exitosamente',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal. !", " Por favor contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },
            loadTipoPermiso(){
                axios.get("api/tipo_permiso").then(({ data }) => (this.tipo_permisos = data));
                
            },

            createTipoPermiso(){
                this.$Progress.start();

                this.form.post('api/tipo_permiso')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'La información ha sido registrada exitosamente'
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
                axios.get('api/buscar_tipo_permiso?q=' + query)
                .then((data) => {
                    this.tipo_permisos = data.data
                })
                .catch(() => {

                })
            })
           this.loadTipoPermiso();
           Fire.$on('AfterCreate',() => {
               this.loadTipoPermiso();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
