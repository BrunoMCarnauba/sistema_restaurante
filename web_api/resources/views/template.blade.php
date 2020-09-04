<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title>@yield('titulo')</title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('others/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('others/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('others/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{asset('others/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">

    <!-- others CSS-->
    <link href="{{asset('others/animsition/animsition.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('others/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('others/wow/animate.css" rel="stylesheet')}}" media="all">
    <link href="{{asset('others/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('others/slick/slick.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('others/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('others/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('css/theme.css')}}" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- MENU NAVEGAÇÃO CELULAR -->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            <img src="{{asset('images/icon/restaurant_gerenciador.png')}}" alt="Restaurant" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <!-- DASHBOARD -->
                        <li class="has-sub">
                            <a href="{{route('dashboard')}}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        <!-- USUARIOS -->
                        <li>
                            <a href="{{route('funcionarios.listar')}}">
                                <i class="fas fa-user"></i>Usuários</a>
                                <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{route('funcionarios.novo')}}">Novo</a>
                                </li>
                                <li>
                                    <a href="{{route('funcionarios.listar')}}">Listar</a>
                                </li>
                            </ul>
                        </li>
                        <!-- PEDIDOS -->
                        <li>
                            <a href="{{route('pedidos.listar')}}">
                                <i class="fas fa-check"></i>Pedidos</a>
                        </li>
                      <!-- MESAS -->
                        <li>
                            <a href="{{route('mesas.listar')}}">
                                <i class="fas fa-table"></i>Mesas</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- [FIM] MENU NAVEGAÇÃO CELULAR -->

        <!-- MENU NAVEGAÇÃO DESKTOP -->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <img src="{{asset('images/icon/restaurant_gerenciador.png')}}" alt="Restaurant" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="@if(isset($menu) && $menu == 'dashboard') active @endif">
                            <a href="{{route('dashboard')}}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        <li class="has-sub @if(isset($menu) && $menu == 'funcionarios') active @endif">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-user"></i>Usuários</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{route('funcionarios.novo')}}">Novo</a>
                                </li>
                                <li>
                                    <a href="{{route('funcionarios.listar')}}">Listar</a>
                                </li>
                            </ul>
                        </li>
                        <li class="@if(isset($menu) && $menu == 'mesas') active @endif">
                            <a href="{{route('mesas.listar')}}">
                                <i class="fas fa-table"></i>Mesas</a>
                        </li>
                        <li class="@if(isset($menu) && $menu == 'pedidos') active @endif">
                            <a href="{{route('pedidos.listar')}}">
                                <i class="fas fa-clipboard-list"></i>Pedidos</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- MENU NAVEGAÇÃO DESKTOP [FIM] -->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            
                            <div class="header-button">
                                <h1 style="width:700px">@yield('titulo')</h1>         
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="content">
                                        <a class="js-acc-btn" href="#">{{session('usuario')->nome}}</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
        
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">{{session('usuario')->nome}}</a>
                                                    </h5>
                                                    <span class="email">{{session('usuario')->email}}</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="{{route('funcionarios.edicao', ['id' => session('usuario')->id_funcionario])}}">
                                                        <i class="zmdi zmdi-account"></i>Editar Conta</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="{{route('logout')}}">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <!-- CONTEUDO PRINCIPAL -->
                        @yield('conteudo')
                        <!-- CONTEUDO PRINCIPAL [FIM] -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="copyright">
                                    <p>Restaurant {{date('Y')}}. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="{{asset('others/jquery-3.2.1.min.js')}}"></script>
    <!-- Bootstrap JS-->
    <script src="{{asset('others/bootstrap-4.1/popper.min.js')}}"></script>
    <script src="{{asset('others/bootstrap-4.1/bootstrap.min.js')}}"></script>
    <!-- others JS       -->
    <script src="{{asset('others/slick/slick.min.js')}}">
    </script>
    <script src="{{asset('others/wow/wow.min.js')}}"></script>
    <script src="{{asset('others/animsition/animsition.min.js')}}"></script>
    <script src="{{asset('others/bootstrap-progressbar/bootstrap-progressbar.min.js')}}">
    </script>
    <script src="{{asset('others/counter-up/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('others/counter-up/jquery.counterup.min.js')}}">
    </script>
    <script src="{{asset('others/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{asset('others/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('others/chartjs/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('others/select2/select2.min.js')}}">
    </script>

    <!-- Main JS-->
    <script src="{{asset('js/main.js')}}"></script>

    @stack('javascript')

</body>

</html>
<!-- end document-->
