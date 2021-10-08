<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div v-if="$gate.isAdminOrAuthor()" class="card">
              <div class="card-header">
                <h3 class="card-title">Registros (Cargar)</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Nuevo<i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <b-table 
                    id="tabla_registros_cargados"
                    :items="registros_cargados"
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
                      aria-controls="tabla_registros_cargados"
                    ></b-pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="$gate.isUser()" class="card">
              <div class="card-header">
                <h3 class="card-title">Registros (Cargar)</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <form @submit.prevent="editmode ? updateRegistro() : createRegistro()">
                <div class="modal-body">
                    <div class="form-group">
                        <file-selector
                        v-if="this.form.texto_registro === ''"
                          accept-extensions=".txt"
                          :max-file-size="5 * 1024 * 1024"
                          @validated="handleFilesValidated"
                          @changed="handleFilesChanged"
                        >
                          Seleccionar Archivo

                          <div slot="top" class="section-top">
                            <p>
                              Puedes hacer click al botón o colocar el archivo de texto en el área.
                            </p>
                            Tamaño máximo permitido: 5 MB.<br/>
                            Extension de archivo: .TXT
                          </div>

                          <div slot="loader" class="section-loader">
                            Procesando<br/>
                            Espere por favor...
                          </div>
                        </file-selector>
                        <div class="gallery" v-if="gallery.length">
                          <div
                            v-for="(img, index) in gallery"
                            class="gallery-item"
                            :key="index"
                          >
                              <div class="img-info">
                                <div class="img-name" :title="img.name">{{ img.name }}</div>
                                <div class="img-size">{{ formatNumber(img.size) }} bytes</div>
                                <div class="pt-3">
                                <button type="button" class="btn btn-danger" @click="reiniciarValores" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click="reiniciarValores" data-dismiss="modal">Cerrar</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                    <button v-show="!editmode" @click="cargarTexto" class="btn btn-primary">Cargar Registros</button>
                </div>

                </form>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
              </div>
            </div>
    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo Registro</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Modificar Registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateRegistro() : createRegistro()">
                <div class="modal-body">
                    <div class="form-group">
                        <file-selector
                        v-if="this.form.texto_registro === ''"
                          accept-extensions=".txt"
                          :max-file-size="5 * 1024 * 1024"
                          @validated="handleFilesValidated"
                          @changed="handleFilesChanged"
                        >
                          Seleccionar Archivo

                          <div slot="top" class="section-top">
                            <p>
                              Puedes hacer click al botón o colocar el archivo de texto en el área.
                            </p>
                            Tamaño máximo permitido: 5 MB.<br/>
                            Extension de archivo: .TXT
                          </div>

                          <div slot="loader" class="section-loader">
                            Procesando<br/>
                            Espere por favor...
                          </div>
                        </file-selector>
                        <div class="gallery" v-if="gallery.length">
                          <div
                            v-for="(img, index) in gallery"
                            class="gallery-item"
                            :key="index"
                          >
                              <div class="img-info">
                                <div class="img-name" :title="img.name">{{ img.name }}</div>
                                <div class="img-size">{{ formatNumber(img.size) }} bytes</div>
                                <div class="pt-3">
                                <button type="button" class="btn btn-danger" @click="reiniciarValores" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click="reiniciarValores" data-dismiss="modal">Cerrar</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                    <button v-show="!editmode" @click="cargarTexto" class="btn btn-primary">Cargar Registros</button>
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
                perPage: 10,
                currentPage: 1,
                campos: [
                  {
                    key: 'nombre_texto',
                    label: 'Nombre del archivo de texto',
                    sortable: true
                  },
                  {
                    key: 'registrado_por',
                    label: 'Registrado por',
                    sortable: true,
                  },
                  {
                    key: 'fecha_inicial',
                    label: 'Desde',
                    sortable: true
                  },
                  {
                    key: 'fecha_final',
                    label: 'Hasta',
                    sortable: true,
                  },
                ],
                editmode: false,
                registros_cargados : [],
                registro_cargado: {},
                texto:[],
                gallery: [],
                file: null,
                form: new Form({
                    id:'',
                    texto_registro : '',
                    nombre_texto: '',
                    registrado_por: '',
                })
            }
        },

        computed: {
          rows() {
            return this.registros_cargados.length
          }
        },

        methods: {

            reiniciarValores()
            {
                this.form.reset();
                this.gallery = [];
                this.file = null;

            },
            calculoAutomaticoEstadistico(fecha_inicio, fecha_fin)
            {   
                swal(
                    'Paciencia',
                    'Por favor espere a que los estadisticos sean calculados',
                    'info'
                    )
                this.$Progress.start();   
                axios.get("api/calcular/" + 'estadistico' + '/' + fecha_inicio + '/' + fecha_fin)
                .then(()=>{
                swal(
                    'Listo!',
                    'Los estadisticos han sido calculados exitosamente',
                    'success'
                    )
                this.$Progress.finish();
                Fire.$emit('AfterCreate');
                })
                .catch(()=>{
                    swal("Algo ha salido mal", "Por favor, contacta al soporte técnico", "warning");
                })
            },

            preguntaCalculoAutomatico()
            {
                $('#addNew').modal('hide')
                swal({
                        title: 'Registro completado',
                        text: "¿Quieres calcular los estadísticos automaticamente?",
                        type: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No, lo haré manualmente'
                        }).then((result) => 
                            {                           
                                if (result.value) {
                                    axios.get('api/ultimo_registro')
                                    .then(({ data }) => { 
                                        this.calculoAutomaticoEstadistico(data[0], data[1]);
                                    })
                                    .catch(()=>{
                                        swal("Algo ha salido mal", "Por favor, contacta al soporte técnico", "warning");
                                    })
                                }
                                Fire.$emit('AfterCreate');
                            })
            },

            cargarTexto()
            {
                const datos = new FormData();
                //var EncodedString = Buffer.from(this.form.texto_registro, 'utf-8');
                datos.append('texto_registro', this.form.texto_registro);
                datos.append('nombre_texto', this.form.nombre_texto);
                datos.append('registrado_por', this.form.registrado_por);
                this.$Progress.start();
                axios.post('api/cargar_texto', datos)
                .then(()=>{
                    this.$Progress.finish();
                    this.reiniciarValores();
                    this.$Progress.finish();
                    this.preguntaCalculoAutomatico();
                })
                .catch(()=>{
                    swal("Algo ha salido mal", "Por favor, contacta al soporte técnico", "warning");
                })
            },

            handleFilesValidated(result, e) {
              console.log('Validation result: ', result);
            },

            async handleFilesChanged(e) {
              this.isLoading = true;    
              const list = Array.from(e);
              this.form.texto_registro = list[0];
              for (const file of list) {
                this.gallery.push({
                  name: file.name,
                  size: file.size,
                });
              }
              this.form.nombre_texto = this.gallery[0].name
              axios.get("api/usuario").then(({ data }) => (this.form.registrado_por = data));
            },
            formatNumber(num) {
              return new Intl.NumberFormat('en', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
              }).format(num);
            },
            getResults(page = 1) {
                        axios.get('api/cargar_registro?page=' + page)
                            .then(response => {
                                this.registros_cargados = response.data;
                            });
            },  
            updateRegistro(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/cargar_registro/'+this.form.id)
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
                    swal("Algo ha salido mal", "Por favor, contacta al soporte técnico", "warning");
                });

            },
            editModal(registro_cargado){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(registro_cargado);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteRegistro(id){
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
                                this.form.delete('api/cargar_registro/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El registro_cargado ha sido eliminado de la lista',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal", "Por favor, contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },
            loadRegistro(){
              if(this.$gate.isAdminOrAuthor()){
                axios.get("api/cargar_registro").then(({ data }) => (this.registros_cargados = data.data));
              }
        
            },

            createRegistro(){
                /*this.$Progress.start();

                this.form.post('api/cargar_registro')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El registro_cargado ha sido registrado exitosamente'
                        })
                    this.$Progress.finish();

                })
                .catch(()=>{

                })*/
            }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/buscar_nomina?q=' + query)
                .then((data) => {
                    this.registros_cargados = data.data
                })
                .catch(() => {

                })
            })
           this.loadRegistro();
           Fire.$on('AfterCreate',() => {
               this.loadRegistro();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
<style lang="scss">
$primColor: #dc3545;
$secTextColor: #6f6f6f;
html, body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  font-size: 1rem;
  box-sizing: border-box;
  line-height: normal;
}
body {
  margin: 0;
  padding: 2rem;
  padding-top: 0;
  p {
    margin-top: 0;
  }
  a, a:visited {
    color: $primColor;
  }
}
.fs-file-selector {
  margin-top: 1rem;
  user-select: none;
  position: sticky !important;
  top: -2px;
  text-align: center;
  background-color: rgba($primColor, 0.05);
  backdrop-filter: blur(35px) saturate(200%);
  border-top: 2px solid $primColor;
  border-bottom: 2px solid $primColor;
  transition: all ease 300ms;
  .fs-droppable {
    padding: 2rem 0;
    transition: all ease 200ms;
  }
  .fs-btn-select {
    background-color: $primColor;
    padding: 0.75rem 2rem;
    color: #fff;
    border-radius: 20px;
    transition: all ease 200ms;
    box-shadow: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24) !important;
    &:hover {
      cursor: pointer;
      background-color: lighten($primColor, 5);
    }
    &:active {
      background-color: darken($primColor, 5);
      transform: scale(0.95);
      transition: all ease 60ms;
    }
  }
  .fs-loader {
    background-color: transparent !important;
  }
  &.fs-drag-enter {
    background-color: rgba($primColor, 0.1);
    .fs-droppable {
      transition: all ease 100ms;
      transform: scale(0.98);
    }
  }
}
.btn-back {
  display: inline-block;
  padding: 1rem 0;
  position: sticky;
  top: 1rem;
  z-index: 10;
  font-weight: 600;
}
.section-top {
  margin-bottom: 2rem;
  color: $secTextColor;
  font-size: 0.875rem;
}
.section-bottom {
  margin-top: 2rem;
  color: $secTextColor;
  font-size: 0.875rem;
}
.section-loader {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all ease 300ms;
  background-color: rgba(#fff, 0.9);
  backdrop-filter: blur(20px);
}
.gallery {
  margin-top: 2rem;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  grid-column-gap: 1rem;
  grid-row-gap: 1rem;
  .gallery-item {
    height: 300px;
    overflow: hidden;
    display: grid;
    grid-template-rows: 1fr min-content;
    align-items: center;
    justify-content: center;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
    background-color: rgba($primColor, 0.05);
    .img {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      img {
        max-width: 100%;
        max-height: 100%;
      }
    }
    .img-info {
      margin: 1rem 0;
      overflow: hidden;
      text-align: center;
      .img-name {
        white-space: nowrap;
        text-overflow: ellipsis;
        font-size: 0.875rem;
        max-width: 100%;
        overflow: hidden;
        padding: 0 1rem;
      }
      .img-size {
        font-size: 0.75rem;
        color: $secTextColor;
        text-align: center;
        padding: 0 1rem;
      }
    }
  }
}
</style>