<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            @yield('title')
        </title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <!-- DataTables CSS -->
        <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    </head>
    <style>
        .container {
            max-width: 100%;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
    @yield('css')
    <body>
        <div class="container mt-2 mb-2">
            @auth
            <ul class="nav nav-tabs justify-content-center">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('usuarios.inicio') ? 'active' : '' }}" href="{{ route('usuarios.inicio') }}"> <i class="fa-solid fa-house"></i> Usuarios </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('usuarios.registro.modificacion') ? 'active' : '' }}" href="{{ route('usuarios.registro.modificacion') }}"><i class="fa-solid fa-chart-simple"></i> Registro </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        {{ Auth::user()->userable->NOMBRE }} <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('home') }}">
                                Menu
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Cerrar Sesión
                        </a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
            @endauth

            @yield('contenido')
        </div>
        
        <!-- Bootstrap JS and dependencies -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <!-- DataTables CSS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/plug-ins/1.13.6/sorting/natural.js"></script>

        @yield('js')
    </body>
</html>