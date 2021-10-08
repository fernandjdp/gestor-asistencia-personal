<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Empresas</h3>

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
                        <th>Descripcion</th>
                        <th>Vista previa del logo</th>
                        <th>Subtítulo reporte</th>
                        <th>Acciones</th>
                  </tr>


                  <tr v-for="empresa in empresas.data" :key="empresa.id">

                    <td>{{empresa.id}}</td>
                    <td>{{empresa.descripcion_empresa}}</td>
                    <img :src="empresa.logo[0].src" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                    <td>{{empresa.subtitulo_reporte}}</td>
                    <td>
                        <a href="#" @click="editModal(empresa)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        -
                        <a href="#" @click="deleteEmpleado(empresa.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="empresas" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nueva empresa</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar descripción de empresa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateEmpresa() : createEmpresa()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.descripcion_empresa" type="text" name="descripcion"
                            placeholder="Descripcion"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion') }">
                        <has-error :form="form" field="descripcion_empresa"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.subtitulo_reporte" type="text" name="descripcion"
                            placeholder="Subtítulo reporte"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion') }">
                        <has-error :form="form" field="subtitulo_reporte"></has-error>
                    </div>
                    <div class="form-group">
                        <file-selector
                          accept-extensions=".jpg,.svg,.png"
                          :multiple = false
                          :max-file-size="5 * 1024 * 1024"
                          @validated="handleFilesValidated"
                          @changed="handleFilesChanged"
                        >
                          Seleccionar logo

                          <div slot="top" class="section-top">
                            <p>
                              Puedes hacer click al botón o colocar las imagenes en el área.
                            </p>
                            Tamaño máximo permitido: 5 MB.<br/>
                            Extensiones de imagen: JPG, SVG, PNG.
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
                            <div class="img"><img :src="img.src"></div>
                              <div class="img-info">
                                <div class="img-name" :title="img.name">{{ img.name }}</div>
                                <div class="img-size">{{ formatNumber(img.size) }} bytes</div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="resetLogo" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
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
                empresas : {},
                isLoading: false,
                gallery: [],
                logo_src : {},
                form: new Form({
                    id:'',
                    descripcion_empresa : '',
                    logo : [],
                    subtitulo_reporte : ''
                })
            }
        },
        methods: {

            resetLogo() {
              this.form.reset();
              this.form.logo = [];
              this.gallery = [];
              this.logo_src = {};
            },

            handleFilesValidated(result, files) {
              console.log('Validation result: ', result);
            },

            async handleFilesChanged(files) {
              this.isLoading = true;
              const list = Array.from(files);
              for (const file of list) {
                const img = await this.loadImgAsDataUrl(file);
                this.gallery.push({
                  name: file.name,
                  size: file.size,
                  src: img,
                });
              }
              this.form.logo = this.gallery;
              this.isLoading = false;
            },

            async loadImgAsDataUrl(file) {
              const url = await new Promise((resolve) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => resolve(e.target.result);
              });
              return url;
            },

            formatNumber(num) {
              return new Intl.NumberFormat('en', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
              }).format(num);
            },

            getResults(page = 1) {
                        axios.get('api/empresa?page=' + page)
                            .then(response => {
                                this.empresas = response.data;
                            });
            },
            updateEmpresa(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/empresa/'+this.form.id)
                .then(() => {
                    // success
                    $('#addNew').modal('hide');
                     swal(
                        'Listo!',
                        'La información ha sido actualizada existosamente',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(empresa){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(empresa);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
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
                                this.form.delete('api/empresa/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El empresa ha sido eliminado de la lista',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Algo ha salido mal. !", " Por favor contacta al soporte técnico", "warning");
                                });
                         }
                    })
            },
            loadEmpresa(){
                    axios.get("api/empresa").then(({ data }) => (this.empresas = data));
                    axios.get("api/empresa").then(({ data }) => (this.logo_src = data.data[0].logo));
            },

            createEmpresa(){
                this.$Progress.start();

                this.form.post('api/empresa')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'La empresa ha sido registrada exitosamente'
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
                axios.get('api/buscar_empresa?q=' + query)
                .then((data) => {
                    this.empresas = data.data
                })
                .catch(() => {

                })
            })
           this.loadEmpresa();
           Fire.$on('AfterCreate',() => {
               this.loadEmpresa();
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