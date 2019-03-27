<?php
    include '/var/www/html/conexion.php';
    session_name ("logged_in");
    session_start();
    
    if (!isset($_SESSION["usuario"])) {
        header("Location:/login/");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bedya - Control Panel</title>

    <link rel="shortcut icon" href="../img/favicon.ico" />

    <!-- Bootstrap Core CSS -->
    <link href="../bt2/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bt2/css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="../bt2/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../bt2/css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../bt2/css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bt2/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="dashboard">Control Panel</a>
        </div>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><a href="/"><i class="fa fa-home fa-fw"></i> Website</a></li>
        </ul>

        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <?=$_SESSION['nombre']?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li class="divider"></li>
                    <li><a href="../login/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">

                <ul class="nav" id="side-menu">
                    <li class="sidebar-search">
                        <div class="input-group custom-search-form">
                            <img style="width: 100%;margin: 0 auto;" src="/img/mini-bedya.png" alt="bedya logo"  />
                        </div>
                    </li>
                    <li>
                        <a href="dashboard" ><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="bots" ><i class="fa fa-send fa-fw"></i> Bots</a>
                    </li>
                    <li>
                        <a href="administradores"><i class="fa fa-users fa-fw"></i> Administradores</a>
                    </li>
                    <li>
                        <a href="servicios"><i class="fa fa-cogs fa-fw"></i> Servicios</a>
                    </li>
                    <li>
                        <a href="modulos"><i class="fa fa-th fa-fw"></i> Modulos</a>
                    </li>
                    <li>
                        <a href="otros" class="active"><i class="fa fa-tag fa-fw"></i> Otros</a>
                    </li>
                    <li>
                        <a href="configuraciones"><i class="fa fa-wrench fa-fw"></i> Configuraciones</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-tag fa-fw"></i> Otros</h1>
                </div>
            </div>
            
            
            <div class="well well-sm">
                <p>Aquí vas a encontrar los últimos elementos que podrás configurar para proporcionaran el máximo de funcionalidades a tu bots.</p>
            </div>


                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <i class="fa fa-tag fa-fw"></i> InfoDenied
                    </div>
                    <div class="panel-body">
                        <div>
                            <p>Con esta funcionalidad activada en la configuración de tu bot, además de recibir un Aviso con el <strong>ID Telegram</strong> y <strong>Nombre</strong> de la persona que esta intentando acceder a tu bot sin permiso, también la persona que no tiene autorización recibirá un mensaje de que esta intentado acceder a un sitio restringido.</p>
                        </div>
                    </div>
                </div>

                <div class="panel panel-green">
                    <div class="panel-heading">
                        <i class="fa fa-wifi fa-fw"></i> Interfaces
                    </div>
                    <div class="panel-body">
                        <div>
                            <p>Aquí vas a poder dar de alta todas las Interfaces de Red que posteriormente vas a poder monitorizar desde tu Bot.</p>
                        </div>
                        <div>
                            <h4>Nueva Interfaz</h4>
                            <form action="nucleo/newinterface" method="POST">
                                <div class="row">
                                    <div class="col-lg-4" >
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-wifi fa-fw"></i></span>
                                            <input class="form-control" placeholder="Nombre Interfaz" name="inombre" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-1" >
                                        <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-lg-6" >
                                        
                                        <?php  
                                            if(isset($_GET['code'])){
                                                if ($_GET['code']==1) {
                                                    ?>
                                                    <div class="alert alert-danger alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                        Error al añadir nuevo interfaz de red, es posible que ya exista.
                                                    </div>
                                                    <?php
                                                }
                                                if ($_GET['code']==2) {
                                                    ?>
                                                    <div class="alert alert-success alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                        Nuevo interfaz de red añadida correctamente.
                                                    </div>
                                                    <?php
                                                }
                                                if ($_GET['code']==3) {
                                                    ?>
                                                    <div class="alert alert-warning alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                        Interfaz de red eliminada correctamente.
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
    

                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h4 class="modal-title" id="myModalLabel">Nueva Interfaz</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Seguro que quieres añadir una nueva Interfaz?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Aceptar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div>
                            <h4>Mis Interfaces</h4>
                            <div class="row">
                                <?php
                                    $idusuario = $_SESSION["usuario"];
                                    $conectMongo = $mongodbatlas;
                                    $manager = new MongoDB\Driver\Manager($conectMongo);
                                    $filter = ["_id" => $idusuario];
                                    $options = ['projection' => ['_id'=>0,'interfaces'=>1]];
                                    $query = new MongoDB\Driver\Query($filter, $options);
                                    $cursor = $manager->executeQuery('bedya.propiedades', $query);
                                    foreach ($cursor as $document) {
                                        $modules = $document->{'interfaces'};
                                        foreach($modules as $values){
                                            
                                ?>
                                       <div class="col-lg-2">
                                        <div class="well well-sm" style="word-wrap: break-word;">
                                            <form action="nucleo/codigo" method="POST">
                                                <button type="submit" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <p><i class="fa fa-wifi fa-fw"></i> <?=$values?></p>
                                                <input type="hidden" name="codigo" value="4">
                                                <input type="hidden" name="id" value="<?=$values?>">     
                                            </form>
                                        </div>
                                        </div>
                                <?php
                
                                        }
                                    }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
            <!-- ... Your content goes here ... -->
    </div>
</div>

<!-- jQuery -->
<script src="../bt2/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../bt2/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="../bt2/js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="../bt2/js/startmin.js"></script>

</body>
</html>
