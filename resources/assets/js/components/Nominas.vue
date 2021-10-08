<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Nóminas</h3>

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
                        <th>Descripción de nómina</th>
                        <th>Acciones</th>
                  </tr>


                  <tr v-for="nomina in nominas.data" :key="nomina.id">

                    <td v-if="$gate.isAdminOrAuthor()">{{nomina.id}}</td>
                    <td>{{nomina.descripcion_nomina}}</td>
                    <td>
                        <a href="#" @click="editModal(nomina)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        -
                        <a href="#" @click="deleteNomina(nomina.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="nominas" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nueva nómina</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar descripción de nómina</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateNomina() : createNomina()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.descripcion_nomina" type="text" name="descripcion_nomina"
                            placeholder="Descripcion de la nómina"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion_nomina') }">
                        <has-error :form="form" field="descripcion_nomina"></has-error>
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
                nominas : {},
                form: new Form({
                    id:'',
                    descripcion_nomina : ''
                })
            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/nomina?page=' + page)
                            .then(response => {
                                this.nominas = response.data;
                            });
                },
            updateNomina(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/nomina/'+this.form.id)
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
            editModal(nomina){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(nomina);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteNomina(id){
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
                                this.form.delete('api/nomina/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'La nómina ha sido eliminada exitosamente',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal. !", " Por favor contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },
            loadNomina(){
                axios.get("api/nomina").then(({ data }) => (this.nominas = data));
            },

            createNomina(){
                this.$Progress.start();

                this.form.post('api/nomina')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'La nómina ha sido registrada exitosamente'
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
                    this.nominas = data.data
                })
                .catch(() => {

                })
            })
           this.loadNomina();
           Fire.$on('AfterCreate',() => {
               this.loadNomina();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
