<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>MIA | Alcaldia de Carirubana</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini sidebar-collapse"> <!-- Si quieres que el sidebar se mantenga siempre desplegado, quita el "sidebar-collapse" -->
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>

    </ul>
  
    <!-- SEARCH FORM 
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" @keyup="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
        -->

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <span style="font-family: Testing-Font;">MIA</span>
      <!--<img src="./img/LOGO_APLICACION_ALCALDIA_BLANCO.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8"> -->
      <span class="brand-text font-weight-light text-capitalize">{{Auth::user()->type}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./img/profile.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
              {{Auth::user()->name}}
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <!--<li class="nav-item">
            <router-link to="/dashboard" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt blue"></i>
                <p>
                Dashboard

                </p>
            </router-link>
            </li> -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cog white"></i>
              <p>
                Administración
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            @can('isAdminOrAuthor')
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="fas fa-users nav-icon white"></i>
                  <p>Usuarios</p>
                </router-link>
              </li>
            </ul>
            @endcan
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/departamentos" class="nav-link">
                  <i class="fas fa-building nav-icon white"></i>
                  <p>Departamentos</p>
                </router-link>
              </li>

            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/cargos" class="nav-link">
                  <i class="fas fa-briefcase nav-icon white"></i>
                  <p>Cargos</p>
                </router-link>
              </li>

            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/dia_festivo" class="nav-link">
                  <i class="fas fa-birthday-cake nav-icon white"></i>
                  <p>Dias Festivos</p>
                </router-link>
              </li>

            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/nomina" class="nav-link">
                  <i class="fas fa-money-check-alt nav-icon white"></i>
                  <p>Nominas</p>
                </router-link>
              </li>

            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/tipos_empleado" class="nav-link">
                  <i class="fas fa-people-carry nav-icon white"></i>
                  <p>Tipos de Empleados</p>
                </router-link>
              </li>

            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/empresa" class="nav-link">
                  <i class="fas fa-building nav-icon white"></i>
                  <p>Empresas</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item">
                <router-link to="/horario" class="nav-link">
                    <i class="nav-icon fas fa-clock white"></i>
                    <p>
                        Horarios
                    </p>
                </router-link>
          </li>
          <li class="nav-item">
                <router-link to="/empleado" class="nav-link">
                    <i class="nav-icon fas fa-address-card white"></i>
                    <p>
                        Empleados
                    </p>
                </router-link>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-check white"></i>
              <p>
                Permisos
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/tipo_permiso" class="nav-link">
                  <i class="nav-icon fas fa-clipboard-list white"></i>
                  <p>Tipos de Permisos</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/permiso" class="nav-link">
                  <i class="nav-icon fas fa-list-ul white"></i>
                  <p>Lista de Permisos</p>
                </router-link>
              </li>
            </ul>
          </li> 
          <li class="nav-item">
                <router-link to="/estadisticos" class="nav-link">
                    <svg width="2em" height="1.9em" viewBox="0 0 15 15" class="bi bi-file-earmark-check" fill="lightgrey" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 1H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h5v-1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h5v2.5A1.5 1.5 0 0 0 10.5 6H13v2h1V6L9 1z"/>
                    <path fill-rule="evenodd" d="M15.854 10.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708l1.146 1.147 2.646-2.647a.5.5 0 0 1 .708 0z"/>
                  </svg>
                    <p>
                        Estadístico
                    </p>
                </router-link>
          </li>
          <li class="nav-item">
                <router-link to="/cargar_registro" class="nav-link">
                  <svg width="2em" height="2em" viewBox="0 0 17 17" class="bi bi-cloud-upload" fill="lightgrey" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                    <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                  </svg>
                  <p>Cargar Registros</p>
                </router-link>
              </li>
          @can('isAdminOrAuthor')
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <svg width="2em" height="2em" viewBox="0 0 17 17" class="bi bi-cloud-upload" fill="lightgrey" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                    <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                  </svg>
                <p>
                Registros
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/registro" class="nav-link">
                  <i class="fas fa-list-ul nav-icon white"></i>
                  <p>Lista de Registros</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/cargar_registro" class="nav-link">
                  <i class="fas fa-file-upload nav-icon white"></i>
                  <p>Cargar Registros</p>
                </router-link>
              </li>
            </ul>
          </li> 
          @endcan 
          <li class="nav-item">
                <router-link to="/reportes" class="nav-link">
                    <svg width="2em" height="2em" viewBox="0 0 17 17" class="bi bi-printer" fill="lightgrey" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 2H5a1 1 0 0 0-1 1v2H3V3a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h-1V3a1 1 0 0 0-1-1zm3 4H2a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1v1H2a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1z"/>
                    <path fill-rule="evenodd" d="M11 9H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zM5 8a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H5z"/>
                    <path d="M3 7.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                  </svg>
                    <p>
                        Reportes
                    </p>
                </router-link>
          </li>
          @can('isAdminOrAuthor')
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cog white"></i>
              <p>
                Admin Técnica
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
          </li>
          @endcan
          <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                    <i class="nav-icon fa fa-power-off red"></i>
                    <p>
                        {{ __('Cerrar Sesión') }}
                    </p>
                 </a>

             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
             </form>
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>

        <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
    </div>
    <!-- Default to the left -->
  </footer>
</div>
<!-- ./wrapper -->

@auth
<script>
    window.user = @json(auth()->user())
</script>
@endauth

<script src="/js/app.js"></script>
</body>
</html>
