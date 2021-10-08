
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import { Form, HasError, AlertError } from 'vform';

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);


import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.toast = toast;


window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('laravel-vue-pagination'));

import jsPDF from 'jspdf'
Vue.use(jsPDF)

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import FileSelector from 'vue-file-selector';
Vue.use(FileSelector);

import  Datetime  from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'

Vue.use(Datetime)

import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
Vue.use(Multiselect)

import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
Vue.use(VueTimepicker)

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);

import Vue from 'vue'
import { BootstrapVue, IconsPlugin, BootstrapVueIcons } from 'bootstrap-vue'

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

Vue.use(BootstrapVueIcons)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
  })

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue') },
    { path: '/overview', component: require('./components/Dashboard.vue') },
    { path: '/home', component: require('./components/Dashboard.vue') },
    { path: '/developer', component: require('./components/Developer.vue') },
    { path: '/reportes', component: require('./components/Reportes.vue') },
    { path: '/estadisticos', component: require('./components/Estadisticos.vue') },
    { path: '/registro', component: require('./components/Registro.vue') },
    { path: '/permiso', component: require('./components/Permisos.vue') },
    { path: '/tipo_permiso', component: require('./components/TiposPermisos.vue') },
    { path: '/cargar_registro', component: require('./components/CargarRegistro.vue') },
    { path: '/users', component: require('./components/Users.vue') },
    { path: '/departamentos', component: require('./components/Departamentos.vue') },
    { path: '/cargos', component: require('./components/Cargos.vue') },
    { path: '/dia_festivo', component: require('./components/Dia_festivo.vue') },
    { path: '/nomina', component: require('./components/Nominas.vue') },
    { path: '/empleado', component: require('./components/Empleados.vue') },
    { path: '/tipos_empleado', component: require('./components/TipoEmpleados.vue') },
    { path: '/empresa', component: require('./components/Empresas.vue') },
    { path: '/horario', component: require('./components/Horarios.vue') },
    { path: '/profile', component: require('./components/Profile.vue') },
    { path: '*', component: require('./components/NotFound.vue') }
  ]

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
  })



Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate',function(created){
    return moment(created).format('MMMM Do YYYY');
});


window.Fire =  new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue')
);


Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    router,
    data:{
        search: ''
    },
    methods:{
        searchit: _.debounce(() => {
            Fire.$emit('searching');
        },1000),

        printme() {
            window.print();
        }
    }
});
