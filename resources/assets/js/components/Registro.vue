<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Registros (Lista)</h3>

                <div class="card-tools">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <b-table 
                    id="tabla_registros"
                    :items="registros"
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
                      aria-controls="tabla_registros"
                    ></b-pagination>
              </div>
            </div>
            <!-- /.card -->
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
                    key: 'description',
                    label: 'Piso',
                    sortable: true
                  },
                  {
                    key: 'empleado_id',
                    label: 'ID Empleado',
                    sortable: true,
                  },
                  {
                    key: 'username',
                    label: 'Nombre',
                    sortable: true,
                  },
                  {
                    key: 'date',
                    label: 'Fecha',
                    sortable: true,
                  },
                  {
                    key: 'time',
                    label: 'Hora',
                    sortable: true,
                  },
                  {
                    key: 'in_out',
                    label: 'Entra/Sale',
                    sortable: true,
                  },
                ],
                editmode: false,
                registros : [],
                registro: {},
                texto:[],
                isLoading: false,
                gallery: [],
                logo_src : {},
                file: null,
                form: new Form({
                    id:'',
                    texto_registro : ''
                })
            }
        },

        computed: {
          rows() {
            return this.registros.length
          }
        },

        methods: {

            cargarTexto()
            {
                const datos = new FormData();
                data.append('texto_registro', this.form.texto_registro);
                axios.post('api/cargar_texto', datos); 
            },

            handleFilesValidated(result, e) {
              console.log('Validation result: ', result);
            },

            async handleFilesChanged(e) {
              this.isLoading = true;    
              const list = Array.from(e);
              this.gallery = {
                name: list.name,
                size: list.size
              }
              this.form.texto_registro = list[0];
              this.cargar_texto();
              /*for (const file of list) {
                const img = await this.loadImgAsDataUrl(file);
                this.gallery.push({
                  name: file.name,
                  size: file.size,
                  src: img,
                });
              }*/
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
                        axios.get('api/registro?page=' + page)
                            .then(response => {
                                this.registros = response.data;
                            });
            },  
            updateRegistro(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/registro/'+this.form.id)
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
            editModal(registro){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(registro);
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
                                this.form.delete('api/registro/'+id).then(()=>{
                                        swal(
                                        'Eliminado!',
                                        'El registro ha sido eliminado de la lista',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Error!", "Algo ha salido mal", "warning");
                                });
                         }
                    })
            },
            loadRegistro(){
                axios.get("api/registro").then(({ data }) => (this.registros = data));
        
            },

            createRegistro(){
                this.$Progress.start();

                this.form.post('api/registro')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'El registro ha sido registrado exitosamente'
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
                    this.registros = data.data
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