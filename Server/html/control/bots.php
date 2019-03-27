<?php
    include '/var/www/html/conexion.php';
    session_name ("logged_in");
    session_start();
    
    if (!isset($_SESSION["usuario"])) {
        header("Location: /login/");
    }
    $nbots = 0;
    $idusuario = $_SESSION["usuario"];
    $conectMongo = $mongodbatlas;
    $manager = new MongoDB\Driver\Manager($conectMongo);
    $filter = ["_id" => $idusuario];
    $options = ['projection' => ['_id'=>0,'bots'=>1]];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('bedya.propiedades', $query);
    foreach ($cursor as $document) {
        $bots = $document->{'bots'};
        $nbots = count($bots);
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
                        <a href="bots" class="active"><i class="fa fa-send fa-fw"></i> Bots</a>
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
                    <h1 class="page-header"><i class="fa fa-send fa-fw"></i> Bots</h1>
                </div>
            </div>
            
            
            <div class="well well-sm">
                <p>En este apartado vas a poder añadir al sistema los Bots que hayas creado anteriormente con tu cuenta de Telegram y que posteriormente te van a ayudar en la administración y gestión tus hosts. Para que el funcionamiento sea correcto necesitaras un bot para cada dispositivo.</p>
                <p>Si necesitas ayuda para crear tu bot puedes hacer <a href="https://core.telegram.org/bots#6-botfather">click aquí</a>.</p>
            </div>
                    
            <div class="row">
                <div class="col-lg-6" >
                    <div class="panel panel-green" >
                    <form action="nucleo/newbot" method="POST">
                        <div class="panel-heading">Nuevo Bot</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6" >
                                    <div class="form-group input-group">
                                        <span class="input-group-addon"><i class="fa fa-send fa-fw"></i></span>
                                        <input class="form-control" placeholder="Nombre" name="inombre" required>
                                    </div>
                                </div>
                                <div class="col-lg-6" >
                                    <div class="form-group input-group">
                                        <span class="input-group-addon"><i class="fa fa-qrcode fa-fw"></i></span>
                                        <input class="form-control" placeholder="Token"  name="itoken" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="text-align:right;"><button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button></div>

                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="myModalLabel">Nuevo Bot</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Seguro que quieres añadir un nuevo bot?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        </form> 
                    </div>
                </div>
                            
                <div class="col-sm-6">
                    <div class="hero-widget well well-sm">
                        <span class="value"><i class="fa fa-send fa-fw"></i> <?=$nbots?></span>
                    </div>
                
                <?php  
                    if(isset($_GET['code'])){
                        if ($_GET['code']==1) {
                            ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error al añadir nuevo bot, es posible que ya exista.
                            </div>
                            <?php
                        }
                        if ($_GET['code']==2) {
                            ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Nuevo bot añadido correctamente.
                            </div>
                            <?php
                        }
                        if ($_GET['code']==3) {
                            ?>
                            <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Bot eliminado correctamente.
                            </div>
                            <?php
                        }
                    }
                ?>
                </div>

        </div>
			<div class="row">
			<?php
                    foreach($bots as $values){
              			$name = $values->name;
              			$token = $values->token;
            ?>
           <div class="col-lg-6">
            <div class="well well-sm" style="word-wrap: break-word;">
                <p><i class="fa fa-send fa-fw"></i> <?=$name?></p>
                <p><i class="fa fa-qrcode fa-fw"></i> <?=$token?></p>
                <form action="nucleo/codigo" method="POST">
                    <input type="hidden" name="codigo" value="0">
                    <input type="hidden" name="token" value="<?=$token?>">
                    <div style="text-align:right;"><button type="submit" class="btn btn-danger btn-circle" ><i class="fa fa-times"></i></button></div>
                </form>
            </div>
            </div>
            <?php
        				}
                }
            ?>
                
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
