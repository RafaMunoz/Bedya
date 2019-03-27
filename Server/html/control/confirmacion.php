<?php
    include '/var/www/html/conexion.php';
    session_name ("logged_in");
    session_start();
    
    if (!isset($_SESSION["usuario"])) {
        header("Location:../login/");
    }

    if (!isset($_COOKIE['Bedya'])) {
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
                        <a href="dashboard" ><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
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
                    <h1 class="page-header"><i class="fa fa-check-circle fa-fw"></i> Confirmación</h1>
                </div>
            </div>
            <div class="well well-sm">
                <p>Si has llegado a este apartado es porque estas intentando realizar alguna acción que requiere de confirmación.</p>
                <ol>
                    <li>Inicia sesión en Telegram y abre una conversación con <a href="https://telegram.me/Bedya_bot">@Bedya_bot</a>.</li>
                    <li>Introduce en nuestra página el código que has recibido.</li>
                    <li>Pulsa el botón Comprobar para completar la acción.</li>
                </ol>
            </div>
            
            
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        Introduce tu código
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
	                            <form action="confirmacion" method="POST">
                                
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                                    <input class="form-control" placeholder="Ejemplo:1234" name="comprobar" max="6" required>
                                </div>
	                                
                            </div>
                            <div id="resultado" style="text-align: center;">
                            	<?php 
                            		if (isset($_POST["comprobar"])) {
                            			$idusuario = $_SESSION["usuario"];
						                $conectMongo = $mongodbatlas;
                                        $manager = new MongoDB\Driver\Manager($conectMongo);
						                $filter = ["_id" => $idusuario];
						                $options = ['projection' => ['_id'=>0,'codigo'=>1]];
						                $query = new MongoDB\Driver\Query($filter, $options);
						                $cursor = $manager->executeQuery('bedya.usuarios', $query);
						                $codigobd="";
						                foreach ($cursor as $document) {
						                	$codigobd = $document->{'codigo'};
						                }

						                if ($codigobd == $_POST["comprobar"]){
											echo '<script type="text/javascript">
											window.location.assign("nucleo/realizar");
											</script>';
						                }
						                else{
						                	echo "Código Incorrecto";
						                }
                            		}
                            	?>
                            </div>
                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-primary" style="width: 120px; margin: 2px;">Comprobar</button>
                                </form>
                                <a href="/control/dashboard"><button type="button" class="btn btn-primary" style="width: 120px; margin: 2px;">Cancelar</button></a>
                                <a href="nucleo/codigo?new=1"><button type="button" id="newcode" class="btn btn-primary" style="width: 120px; margin: 2px;">Nuevo Código</button></a>
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
