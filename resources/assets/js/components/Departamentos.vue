<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Departamentos</h3>

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
                        <th>Descripción del Departamento</th>
                        <th>Acciones</th>
                  </tr>


                  <tr v-for="departamento in departamentos.data" :key="departamento.id">

                    <td>{{departamento.id}}</td>
                    <td>{{departamento.descripcion_departamento}}</td>
                    <td>
                        <a href="#" @click="editModal(departamento)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        -
                        <a href="#" @click="deleteDepartamento(departamento.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="departamentos" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo departamento</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar descripción de departamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateDepartamento() : createDepartamento()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.descripcion_departamento" type="text" name="descripcion_departamento"
                            placeholder="Descripción"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion_departamento') }">
                        <has-error :form="form" field="descripcion_departamento"></has-error>
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
                departamentos : {},
                form: new Form({
                    id:'',
                    descripcion_departamento : ''
                })
            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/departamento?page=' + page)
                            .then(response => {
                                this.departamentos = response.data;
                            });
                },
            updateDepartamento(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/departamento/'+this.form.id)
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
            editModal(departamento){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(departamento);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteDepartamento(id){
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
                                this.form.delete('api/departamento/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El departamento ha sido eliminado exitosamente',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal. !", " Por favor contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },
            loadDepartamento(){
                axios.get("api/departamento").then(({ data }) => (this.departamentos = data));

            },

            createDepartamento(){
                this.$Progress.start();

                this.form.post('api/departamento')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El departamento ha sido registrado exitosamente'
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
                axios.get('api/buscar_departamento?q=' + query)
                .then((data) => {
                    this.departamentos = data.data
                })
                .catch(() => {

                })
            })
           this.loadDepartamento();
           Fire.$on('AfterCreate',() => {
               this.loadDepartamento();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
