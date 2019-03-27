<?php
    include '/var/www/html/conexion.php';
    session_name ("logged_in");
    session_start();
    
    if (!isset($_SESSION["usuario"])) {
        header("Location:../login/");
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
                        <a href="dashboard" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="bots"><i class="fa fa-send fa-fw"></i> Bots</a>
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
                    <h1 class="page-header"><i class="fa fa-dashboard fa-fw"></i> Dashboard</h1>
                </div>
            </div>
            
            
            <?php 
                $idusuario = $_SESSION["usuario"];
                
                $conectMongo = $mongodbatlas;
                $manager = new MongoDB\Driver\Manager($conectMongo);
                $nbots=0;
                $filter = ["_id" => $idusuario];
                $options = [];
                $query = new MongoDB\Driver\Query($filter, $options);
                $cursor = $manager->executeQuery('bedya.propiedades', $query);
                
                foreach ($cursor as $document) {
                    $bots = $document->{'bots'};
                    $nbots = count($bots);
                    $admins = $document->{'admin'};
                    $nadmin = count($admins);
                    $services = $document->{'services'};
                    $nservices = count($services);
                }
            ?>
            <div class="row">
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-send fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$nbots?></div>
                                    <div>Bots!</div>
                                </div>
                            </div>
                        </div>
                        <a href="bots">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$nadmin?></div>
                                    <div>Administradores!</div>
                                </div>
                            </div>
                        </div>
                        <a href="administradores">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-cogs fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$nservices?></div>
                                    <div>Servicios!</div>
                                </div>
                            </div>
                        </div>
                        <a href="servicios">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
            <?php
                $filter = ["usuario" => $idusuario];
                $options = [];
                $query = new MongoDB\Driver\Query($filter, $options);
                $cursor = $manager->executeQuery('bedya.bots', $query);

                foreach ($cursor as $document) {
                    $obj = $document;
                    $namebot = $obj->{'name'};
                    $token = $obj->{'_id'};
                    $idioma = $obj->{'language'};

                    $admins = $obj->{'admin'};
                    $nameadmins = " ";
                    foreach($admins as $values){
                        if ($nameadmins == " ") {
                            $nameadmins = $values->name;
                        }
                        else{
                            $nameadmins = $nameadmins." | ".$values->name;
                        }
                      }

                    $services = $obj->{'services'};
                    $nameservices = " ";
                    foreach($services as $values){
                        if ($nameservices == " ") {
                            $nameservices = $values->nb;
                        }
                        else{
                            $nameservices = $nameservices." | ".$values->nb;
                        }
                      }

                    $modules = $obj->{'modules'};
                    $namemodules = " ";
                    foreach($modules as $values){
                        if ($values->activated == 1) {
                        
                            if ($namemodules == " ") {
                                $namemodules = $values->name;
                            }
                            else{
                                $namemodules = $namemodules." | ".$values->name;
                            }
                            if ($values->name == "Latch") {
                                $nl = $values->id;
                                $namemodules = $namemodules."(".$nl.")";
                            }
                        } 
                    }

                    $interfaces = $obj->{'interfaces'};
                    $nameinterfaces = " "; 
                    foreach($interfaces as $values){
                        if ($nameinterfaces == " ") {
                            $nameinterfaces = $values;
                        }
                        else{
                            $nameinterfaces = $nameinterfaces." | ".$values;
                        }
                      } 

                    $otros = " ";
                    $infodenied = $obj->{'infodenied'};
                    if($infodenied == 1){
                        $otros = $otros."InfoDenied";
                    }

                    $downloads = $obj->{'downloads'};
                    $version = " V ".$downloads;

                    $ultimapet = $obj->{'ultimapeticion'};

                    $start = strtotime($ultimapet);
                    $end   = strtotime(date("Y-m-d H:i:s"));
                    $diff  = $end - $start;
                    $diferencia = $diff/60;
                    if ($diferencia <= 10) {
                        $icono = '<i class="fa fa-check fa-fw pull-right" style="color: #5cb85c;"></i>';
                    }
                    elseif ($diferencia <= 20) {
                        $icono =  '<i class="fa fa-warning fa-fw pull-right" style="color: #f0ad4e;"></i>';
                    }
                    else{
                        $icono = '<i class="fa fa-close fa-fw pull-right" style="color: #d9534f;"></i> ';
                    }
                              
                    ?>
                    <div class="col-lg-4">
                        <div class="panel panel-default"> 
                            <div class="panel-heading">
                                <i class="fa fa-send fa-fw"></i> 
                                <?=$namebot?>
                                <?=$icono?>
                            </div>
                            <div class="panel-body">
                                <p><i class="fa fa-language fa-fw"></i> <?=$idioma?></p>
                                <p><i class="fa fa-users fa-fw"></i> <?=$nameadmins?></p>
                                <p><i class="fa fa-cogs fa-fw"></i> <?=$nameservices?></p>
                                <p><i class="fa fa-th fa-fw"></i> <?=$namemodules?></p>
                                <p><i class="fa fa-wifi fa-fw"></i> <?=$nameinterfaces?></p>
                                <p><i class="fa fa-tag fa-fw"></i> <?=$otros?></p><br>
                                <a href="configuraciones?token=<?=$token?>" class="btn btn-block btn-primary btn-circle" ><i class="fa fa-pencil"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>  
            </div>
            
            <!-- ... Your content goes here ... -->

        </div>
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
