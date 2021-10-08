<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Permisos</h3>

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
                        <th>Empleado</th>
                        <th>Biometrico</th>
                        <th>Razón del Permiso</th>
                        <th>Fecha Inicial</th>
                        <th>Fecha Final</th>
                        <!--<th>Acciones</th>-->
                    </tr>


                  <tr v-for="permiso in permisos.data" :key="permiso.id">

                    <td v-if="$gate.isAdminOrAuthor()">{{permiso.id}}</td>
                    <td>{{permiso.nombre_empleado}}</td>
                    <td>{{permiso.empleado_id}}</td>
                    <td>{{permiso.razon}}</td>
                    <td>{{permiso.fecha_inicial}}</td>
                    <td>{{permiso.fecha_final}}</td>
                    <td>
                        <a href="#" @click="editModal(permiso)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        
                        <a href="#" @click="deletePermiso(permiso.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
    
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="permisos" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo Permiso</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar Permiso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updatePermiso() : createPermiso()">
                <div class="modal-body">
                    <div class="form-group pt-3">
                        <label>Empleado</label>
                        <select @click="loadOptions" v-model="form.empleado_id" class="form-control" id="empleado_id" name="empleado_id" :class="{ 'is-invalid': form.errors.has('empleado_id') }">
                            <option value="" selected disabled>Seleccione un empleado</option>
                            <option v-for="opcion in empleados" :value="opcion.biometrico_id" :key="opcion.biometrico_id">
                                <b>{{opcion.nombre_completo}}</b>
                            </option>
                        </select>
                                <has-error :form="form" field="empleado_id"></has-error>
                    </div>
                    <div class="form-group pt-3">
                        <label>Tipo de Permiso</label>
                        <select @click="loadOptions" v-model="form.razon" class="form-control" id="razon" name="razon" :class="{ 'is-invalid': form.errors.has('razon') }">
                            <option value="" selected disabled>Seleccione un empleado</option>
                            <option v-for="opcion in tipo_permisos" :value="opcion.descripcion_tipo_permiso" :key="opcion.id">
                                <b>{{opcion.descripcion_tipo_permiso}}</b>
                            </option>
                        </select>
                                <has-error :form="form" field="razon"></has-error>
                    </div>
                    <div class="form-group pt-3">
                        <label>Dias</label>
                        <select v-model="unico_dia" class="form-control" id="unico_dia" name="unico_dia" :class="{ 'is-invalid': form.errors.has('unico_dia') }">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option :value=true>Único día</option>
                            <option :value=false>Rango de días</option>
                        </select>
                                <has-error :form="form" field="unico_dia"></has-error>
                    </div>
                    <div v-if="unico_dia" class="form-group p-2">
                        <label>Fecha</label>
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
                    <div v-if="unico_dia == false || form.fecha_final">
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
                            label="Seleccione la fecha inicial"
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
                            label="Seleccione la fecha final"
                            />
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
    export default {
        components: {
        Multiselect,
      },
        data() {
            return {
                editmode: false,
                permisos : {},
                empleados: [],
                tipo_permisos: [],
                unico_dia: true,
                form: new Form({
                    id:'',
                    empleado_id : '',
                    razon:'',
                    fecha_inicial:'',
                    fecha_final:null,
                })
            }
        },
        methods: {
            test(){
                //
            },
            getResults(page = 1) {
                        axios.get('api/permiso?page=' + page)
                            .then(response => {
                                this.permisos = response.data;
                            });
                },
            updatePermiso(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/permiso/'+this.form.id)
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
            editModal(permiso){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(permiso);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deletePermiso(id){
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
                                this.form.delete('api/permiso/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El permiso ha sido eliminado de la lista',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Error!", "Algo ha salido mal", "warning");
                                });
                         }
                    })
            },
            loadPermiso(){
                axios.get("api/permiso").then(({ data }) => (this.permisos = data));

            },

            loadOptions(){  
                axios.get("api/empleado").then(({ data }) => (this.empleados = data.data));
                axios.get("api/tipo_permiso").then(({ data }) => (this.tipo_permisos = data.data));
            },


            calcular(tipo){
                this.$Progress.start();
                axios.get("api/calcular/" + tipo + '/' + this.form.fecha_inicial + '/' + this.form.fecha_final)
                .then(()=>{
                Fire.$emit('AfterCreate');
                $('#addNew').modal('hide')
                toast({
                    type: 'success',
                       title: 'El permiso ha sido registrado exitosamente'
                       })
                this.$Progress.finish();

                })
                .catch(()=>{

                })
            },

            createPermiso(){
                this.$Progress.start();

                this.form.post('api/permiso')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El permiso ha sido registrado exitosamente'
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
                axios.get('api/buscar_permiso?q=' + query)
                .then((data) => {
                    this.permisos = data.data
                })
                .catch(() => {

                })
            })
           this.loadOptions();
           this.loadPermiso();
           Fire.$on('AfterCreate',() => {
               this.loadOptions();
               this.loadPermiso();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
