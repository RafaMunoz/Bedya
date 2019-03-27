<?php
    include '/var/www/html/conexion.php';
    session_name ("logged_in");
    session_start();
    
    if (!isset($_SESSION["usuario"])) {
        header("Location:../login/");
    }

    $x = 0;

    $idusuario = $_SESSION["usuario"];
    $conectMongo = $mongodbatlas;
    $manager = new MongoDB\Driver\Manager($conectMongo);
    $filter = ["_id" => $idusuario];
    $options = ['projection' => ['_id'=>0]];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('bedya.propiedades', $query);

    if (isset($_GET["token"])) {
        $x=1;
        $tokenGet = $_GET["token"];
        $filter2 = ["_id" => $tokenGet];
        $options2 = [];
        $query2 = new MongoDB\Driver\Query($filter2, $options2);
        $cursor2 = $manager->executeQuery('bedya.bots', $query2);
        foreach ($cursor2 as $document2) {
            $token2 = $document2->_id;
            $idioma2 = $document2->language;
            $admins2 = $document2->{'admin'};
            $idadminarray= array();
            foreach($admins2 as $values){
                $idadmin2 =  $values->id;
                $idadminarray[]=$idadmin2;
            }

            $services2 = $document2->{'services'};
            $idservicearray= array();
            foreach($services2 as $values){
                $idservice2 =  $values->name;
                $idservicearray[]=$idservice2;
            }

            $modules2 = $document2->{'modules'};
            foreach($modules2 as $values){
                $tipo2 = $values->name;
                if ($tipo2 == "BedyaStart") {
                    $activadoBS =  $values->activated;
                }
                if ($tipo2 == "Latch") {
                    $appid2 =  $values->appid;
                    $activadoL = $values->activated;
                }
            }

            $interfaces2 = $document2->{'interfaces'};
            $infodenied2 = $document2->{'infodenied'};
            
        }
    }
    
    foreach ($cursor as $document) {
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
                        <a href="otros"><i class="fa fa-tag fa-fw"></i> Otros</a>
                    </li>
                    <li>
                        <a href="configuraciones" class="active"><i class="fa fa-wrench fa-fw"></i> Configuraciones</a>
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
                    <h1 class="page-header"><i class="fa fa-wrench fa-fw"></i> Configuraciones</h1>
                </div>
            </div>

            <div class="well well-sm">
                <p>Este es el final de la cadena!! Aquí vas a poder crear las configuraciones con las funcionalidades que más se ajusten a las necesidades de cada Bot.</p>
            </div>
            
            <form role="form" action="nucleo/codigo" method="POST">
                <h4 class="page-header">1 - Seleciona tu Bot <i class="fa fa-send fa-fw"></i></h4>
                <div class="row">
                    <div class="col-lg-4">
                        <select class="form-control" name="bot">
                        <?php  
                            $bots = $document->{'bots'};
                            foreach($bots as $values){
                                $name = $values->name;
                                $token = $values->token;
                                $idioma = $values->language;

                                if ($x==1 && $token == $token2) {
                                    echo "<option value=".$token." selected='selected'>".$name."</option>";
                                }else{
                                    echo "<option value=".$token.">".$name."</option>";
                                }
                            }
                        ?>
                        </select>
                        <br>
                        <label>Idioma <i class="fa fa-language fa-fw"></i></label><br>
                        <label class="radio-inline">
                            <input type="radio" name="idioma" id="latchon" value="ES" checked>Español
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="idioma" id="latchoff" value="EN" <?php if ($x==1 && $idioma2=="EN") {echo "checked";}?>>Ingles
                        </label>
                    </div>
                </div>
                <h4 class="page-header">2 - Elige los Administradores <i class="fa fa-users fa-fw"></i></h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php  
                                $admins = $document->{'admin'};
                                foreach($admins as $values){
                                    $admin = $values->name;
                                    $idadmin =  $values->id;

                                    if ($idadmin == $idusuario) {
                                    ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="admins[]" value="<?=$idadmin?>" checked disabled><?=$admin?>
                                        <input type="hidden" name="admins[]" value="<?=$idadmin?>">
                                    </label>
                                    <?php
                                       }
                                       else{
                                    ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="admins[]" value="<?=$idadmin?>" <?php if ($x==1 && in_array($idadmin, $idadminarray)) {echo "checked";}?>><?=$admin?>
                                    </label>
                                    <?php
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <h4 class="page-header">3 - ¿Qué Servicios va a controlar? <i class="fa fa-cogs fa-fw"></i></h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php  
                                $services = $document->{'services'};
                                foreach($services as $values){
                                    $service = $values->nb;
                                    $idservice =  $values->name;
                                    ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="services[]" value="<?=$idservice?>" <?php if ($x==1 && in_array($idservice, $idservicearray)) {echo "checked";}?>><?=$service?>
                                    </label>
                                    <?php 
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <h4 class="page-header">4 - ¿Quieres algún Módulo? <i class="fa fa-th fa-fw"></i></h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="bedyastart" value="1" <?php if ($x==1 && $activadoBS==1) {echo "checked";}?>>BedyaStart
                            </label><br><br>
                            <label>Latch</label><br>
                            <div class="row">
                                <div class="col-lg-4" style="display: block;">
                                    <select class="form-control" name="latch">
                                    <?php  
                                        $modules = $document->{'modules'};
                                        foreach($modules as $values){
                                            $name = $values->id;
                                            $tipo = $values->name;
                                            if ($tipo == "Latch") {
                                                $appid =  $values->appid;
                                                if ($appid == $appid2) {
                                                    echo "<option value=".$appid." selected='selected'>".$name."</option>";
                                                }
                                                else{
                                                    echo "<option value=".$appid.">".$name."</option>";
                                                }
                                            }
                                        }
                                    ?>
                                    </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="radio-inline">
                                            <input type="radio" name="latchActive" id="latchon" value="1" <?php if ($x==1 && $activadoL==1) {echo "checked";}?>>ON
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="latchActive" id="latchoff" value="0" checked>OFF
                                        </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="page-header">5 - Completa tu Bot <i class="fa fa-tag fa-fw"></i></h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="infodenied" value="1" <?php if ($x==1 && $infodenied2==1) {echo "checked";}?>>InfoDenied
                            </label><br><br>
                            <label>Interfaces <i class="fa fa-wifi fa-fw"></i></label><br>
                            <?php  
                                $interfaces = $document->{'interfaces'};
                                foreach($interfaces as $values){
                                    if ($x==1 && in_array($values, $interfaces2)){
                                    ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="interfaces[]" value="<?=$values?>" checked><?=$values?>
                                        </label>
                                    <?php
                                    }
                                    else{
                                        ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="interfaces[]" value="<?=$values?>" ><?=$values?>
                                        </label>
                                        <?php
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <h4 class="page-header">6 - Acepta tu nueva configuración <i class="fa fa-check-circle fa-fw"></i></h4>
                <input type="hidden" name="codigo" value="5">
                <button type="submit" class="btn btn-lg btn-success">Generar</button>
                <?php if ($x==0){?>
                    <button type="reset" class="btn btn-lg btn-default">Reset</button>
                <?php } ?>
            </form>
        </div>
        <?php  
            }
        ?>
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
