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
                        <a href="modulos" class="active"><i class="fa fa-th fa-fw"></i> Modulos</a>
                    </li>
                    <li>
                        <a href="otros"><i class="fa fa-tag fa-fw"></i> Otros</a>
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
                    <h1 class="page-header"><i class="fa fa-th fa-fw"></i> Módulos</h1>
                </div>
            </div>
            
            
            <div class="well well-sm">
                <p>En este apartado vas a poder añadir diferentes Módulos que posteriormente proporcionaran diferentes funcionalidades a tu bots.</p>
                <p>Estamos desarrollando mas módulos...</p>
            </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-th fa-fw"></i> BedyaStart
                    </div>
                    <div class="panel-body">
                        <div>
                            <img style="width: 80px; float: left;" src="/img/bedyastart.png" alt="bedyastart"  />
                        </div>
                        <div style="padding-left: 10%; padding-top: 1%;">
                            <p>El módulo BedyaStart te permitirá saber cuando se ha iniciado o reiniciado tu host.<br> Esté se encargará de enviarte una notificación cada vez que ocurrá dicha acción.</p>
                        </div>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-th fa-fw"></i> Latch
                    </div>
            
                    <div class="panel-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#home" data-toggle="tab" aria-expanded="true">Información</a>
                                    </li>
                                    <li class=""><a href="#anadir" data-toggle="tab" aria-expanded="false">Nuevo</a>
                                    </li>
                                    <li class=""><a href="#messages" data-toggle="tab" aria-expanded="false">Mis Latchs</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="home">
                                        <div>
                                            <img style="width: 80px; float: left;" src="/img/bedyalatch.png" alt="bedyastart"  />
                                        </div>
                                        <div style="padding-left: 10%; padding-top: 1%;">
                                            <p>El módulo Latch le proporcionará un punto más de seguridad al control de tu bot.<br> En caso de que este módulo y Latch desde su App estén activado, nadie podrá enviar ordenes de control al host.<br> Para el uso de esté módulo necesitaras tener una cuenta de Desarrollador en la página oficial de <a href="https://latch.elevenpaths.com/www/" target="_blank">Latch</a>.<br>En la pestaña <strong>Nuevo</strong> puedes dar de alta tantos pestillos como necesites y más adelante podras asociar cada uno a un bot.</p>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="anadir">
                                        <h4>Nuevo Latch</h4>
                                        
                                    <form action="nucleo/newlatch" method="POST">
                                            <div class="row">
                                                <div class="col-lg-6" >
                                                    <div class="form-group input-group">
                                                        <span class="input-group-addon"><i class="fa fa-th fa-fw"></i></span>
                                                        <input class="form-control" placeholder="Nombre" name="inombre" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6" >
                                                    <div class="form-group input-group">
                                                        <span class="input-group-addon"><i class="fa fa-slack fa-fw"></i></span>
                                                        <input class="form-control" placeholder="ID de aplicación"  name="iid" required>
                                                    </div>    
                                                </div>
                                                <div class="col-lg-6" >
                                                    <div class="form-group input-group">
                                                        <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                                                        <input class="form-control" placeholder="Secreto"  name="isecreto" required>
                                                    </div>    
                                                </div>
                                                <div class="col-lg-6" >
                                                    <div class="form-group input-group">
                                                        <span class="input-group-addon"><i class="fa fa-link fa-fw"></i></span>
                                                        <input class="form-control" placeholder="Código de pareado"  name="ipareo" required>
                                                    </div>    
                                                </div>

                                            </div>
                                                <div style="text-align:right;"><button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button></div>

                                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title" id="myModalLabel">Nuevo Latch</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>¿Seguro que quieres añadir un nuevo Latch?</p>
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
                                    <div class="tab-pane fade" id="messages">
                                        <h4>Mis Latchs</h4>
                                        <div class="row">
                                        <?php
                                            $idusuario = $_SESSION["usuario"];
                                            $conectMongo = $mongodbatlas;
                                            $manager = new MongoDB\Driver\Manager($conectMongo);
                                            $filter = ["_id" => $idusuario];
                                            $options = ['projection' => ['_id'=>0,'modules'=>1]];
                                            $query = new MongoDB\Driver\Query($filter, $options);
                                            $cursor = $manager->executeQuery('bedya.propiedades', $query);
                                            foreach ($cursor as $document) {
                                                $modules = $document->{'modules'};
                                                foreach($modules as $values){
                                                    $name = $values->name;
                                                    if ($name == "Latch") {
                                                        $id = $values->id;
                                                        $appid = $values->appid;
                                                        $secret = $values->key;
                                                        $accid = $values->accid;
                                                    
                                        ?>
                                               <div class="col-lg-6">
                                                <div class="well well-sm" style="word-wrap: break-word;">
                                                    <p><i class="fa fa-th fa-fw"></i> <?=$id?></p>
                                                    <p><i class="fa fa-slack fa-fw"></i> <?=$appid?></p>
                                                    <p><i class="fa fa-lock fa-fw"></i> <?=$secret?></p>
                                                    <form action="nucleo/codigo" method="POST">
                                                        <input type="hidden" name="codigo" value="3">
                                                        <input type="hidden" name="appid" value="<?=$appid?>">
                                                        <input type="hidden" name="secret" value="<?=$secret?>">
                                                        <input type="hidden" name="accid" value="<?=$accid?>">
                                                        <div style="text-align:right;"><button type="submit" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button></div>     
                                                    </form>
                                                </div>
                                                </div>
                                        <?php
                                                    }
                                                }
                                            }
                                        ?>
                                        </div>
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
