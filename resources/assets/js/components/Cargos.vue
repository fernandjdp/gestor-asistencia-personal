<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Cargos</h3>

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
                        <th>Descripción del Cargo</th>
                        <th>Acciones</th>
                  </tr>


                  <tr v-for="cargo in cargos.data" :key="cargo.id">

                    <td v-if="$gate.isAdminOrAuthor()">{{cargo.id}}</td>
                    <td>{{cargo.descripcion_cargo}}</td>
                    <td>
                        <a href="#" @click="editModal(cargo)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        -
                        <a href="#" @click="deleteCargo(cargo.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="cargos" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo cargo</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar descripción del cargo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateCargo() : createCargo()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.descripcion_cargo" type="text" name="descripcion_cargo"
                            placeholder="Descripción del cargo"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion_cargo') }">
                        <has-error :form="form" field="descripcion_cargo"></has-error>
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
                cargos : {},
                form: new Form({
                    id:'',
                    descripcion_cargo : ''
                })
            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/cargo?page=' + page)
                            .then(response => {
                                this.cargos = response.data;
                            });
                },
            updateCargo(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/cargo/'+this.form.id)
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
            editModal(cargo){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(cargo);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteCargo(id){
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
                                this.form.delete('api/cargo/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El cargo ha sido eliminado exitosamente',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal. !", " Por favor contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },
            loadCargo(){
                axios.get("api/cargo").then(({ data }) => (this.cargos = data));

            },

            createCargo(){
                this.$Progress.start();

                this.form.post('api/cargo')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El cargo ha sido registrado exitosamente'
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
                axios.get('api/buscar_cargo?q=' + query)
                .then((data) => {
                    this.cargos = data.data
                })
                .catch(() => {

                })
            })
           this.loadCargo();
           Fire.$on('AfterCreate',() => {
               this.loadCargo();
           });
        }

    }
</script>
