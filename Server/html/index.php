<?php

include '/var/www/html/conexion.php';
$conectMongo = $mongodbatlas;
$manager = new MongoDB\Driver\Manager($conectMongo);

$command = new MongoDB\Driver\Command(array("collStats" =>"bots","scale"=>1024));

try {
    $cursor = $manager->executeCommand("bedya", $command);
    foreach ($cursor as $document) {
        $nbots = $document->{"count"};
    }

} catch(MongoDB\Driver\Exception $e) {
    $nbots = "?";
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

    <title>Bedya - IT Management</title>

    <link rel="shortcut icon" href="img/favicon.ico" />
    
    <!-- Bootstrap Core CSS -->
    <link href="bt1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="bt1/css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bt2/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Bedya</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                	<li>
                        <a href="/">Home</a>
                    </li>
                    <li>
                        <a href="#configuraciones">Configuraciones</a>
                    </li>
                    <li>
                        <a href="#modulos">Módulos</a>
                    </li>
                    <li>
                        <a href="#started">Getting Started</a>
                    </li>
                	<li>
                        <a href="contacto">Contacto</a>
                    </li>
                    <li>
                        <a href="login/">Login</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Header Carousel -->
    <header id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" style="background-image:url('img/fondo.png');">
            <div class="item active">
                <div class="fill" style="background-image:url('img/bedya.png');"></div>
            </div>
            <div class="item">
                <div class="fill" style="background-image:url('img/bedya-telegram.png');"></div>
                <div class="carousel-caption">
                </div>
            </div>
            
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>
    </header>

    <!-- Page Content -->
    <div class="container">

        <!-- Marketing Icons Section -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Bienvenido a Bedya
                </h1>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-check"></i> ¿Qué es Bedya?</h4>
                    </div>
                    <div class="panel-body">
                        <p>Bedya es la primera aplicación para la administración de dispositivos IT con distribución Linux a través de Telegram como Servidores, Raspberrys... De esta manera podrás conocer el estado de tus dispositivos desde cualquier lugar con conexión a Internet, ya sea un móvil o un PC.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-compass"></i> Facil de usar</h4>
                    </div>
                    <div class="panel-body">
                        <p>A través de un sencillo e intuitivo Panel de Control que hemos desarrollado, vas a poder dar de alta diferentes elementos y crear configuraciones totalmente personalizadas para que la administración de tus dispositivos se adapte de la mejor forma posible a tus necesidades.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-gift"></i> Free &amp; Open Source</h4>
                    </div>
                    <div class="panel-body">
                        <div class="panel-heading">

                            <div class="panel panel-default text-center" style="margin: auto;">
                                <div class="panel-body">
                                    <h1 style="margin-top: auto;"><i class="fa fa-fw fa-server"></i> <?=$nbots;?></h1>
                                    <h4>Dispositivos Administrados</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
		<div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">¿Qué me va a permitir Bedya?</h2>
                <p>A continuación te dejamos una lista de la información y acciones que vas a poder realizar en cada dispositivo:</p>

            </div>
            <div class="col-md-4">
                <ul>
                    <li>Consultar la dirección IP Pública</li>
                    <li>Consultar la dirección IP Privada</li>
                    <li>Nombre del equipo</li>
                    <li>Hora y fecha actual </li>
                    <li>Uptime</li>
                    
                </ul>
            </div>
            <div class="col-md-4">
                <ul>
                	<li>Consumo de RAM</li>
                    <li>Estado del Disco Duro</li>
                	<li>Transferencias de las Interfaces de Red</li>
                	<li>Restringir la administración</li>
                	<li>Integración con Latch</li>
                </ul>
                <br>
            </div>
            <div class="col-md-4">
                <ul>
                	<li>Saber el estado de los servicios</li>
                    <li>Iniciar/Detener servicios</li>
                    <li>Reiniciar el equipo</li>
                    <li>Apagar el equipo</li>
                    <li>Recibir avisos de reinicio</li>
                </ul>
                <br>
            </div>
           
            <div class="col-lg-12">
             <br>
            	<h5>¿Qué pasa?¿Te parece poco?</h5>
            	<p>A primera vista puede que te parezca poco, pero recuerda que es una aplicación nueva y lo primero que hemos querido desarrollar son las funciones y necesidades más básicas que puede tener un administrador de sistemas.</p>
            	<p>Actualmente estamos trabajando en desarrollar diferentes módulos para en un futuro no muy lejano poder ofreceros más funcionalidades como por ejemplo integraciones con <a href="https://www.nagios.org/" target="_blank">Nagios</a> o subida de ficheros <strong>Logs</strong> para que puedas consultarles estés donde estés.</p>
            </div>
        </div>
        <a name="configuraciones"></a>
		<div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Configuraciones</h2>
            </div>
            <div class="col-lg-12">
            	<p>Para cada dispositivo que quieras administrar necesitaras una configuración que puedes modificar en cualquier momento desde nuestro Panel de Control y ellas solas se descargaran en los dispositivos para ofrecerte una mayor facilidad de gestión.</p><br>
            </div>
            <div class="col-md-6">
	            <div class="media">
	                <div class="pull-left">
	                    <span class="fa-stack fa-2x">
	                          <i class="fa fa-circle fa-stack-2x text-primary"></i>
	                          <i class="fa fa-send fa-stack-1x fa-inverse"></i>
	                    </span> 
	                </div>
	                <div class="media-body">
	                    <h4 class="media-heading">Bots</h4>
	                    <p>En informática cuando hablamos de bots nos referimos a un software que imita un comportamiento humano. En este caso hemos utilizado los Bots de Telegram para crear una aplicación que nos permita administrar y gestionar nuestros equipos a distancia de una forma sencilla.</p>
	                </div>
	            </div>
		        <div class="media">
	                <div class="pull-left">
	                    <span class="fa-stack fa-2x">
	                          <i class="fa fa-circle fa-stack-2x text-primary"></i>
	                          <i class="fa fa-users fa-stack-1x fa-inverse"></i>
	                    </span> 
	                </div>
	                <div class="media-body">
	                    <h4 class="media-heading">Administradores</h4>
	                    <p>Los Administradores van a jugar un papel importante en nuestras configuraciones, estos son el primer punto de seguridad para el control de nuestros equipos, ya que solo podrán tener acceso a la gestión del dispositivo los administradores asociados a cada elemento IT.</p>
	                </div>
            	</div>
	            <div class="media">
		            <div class="pull-left">
		                <span class="fa-stack fa-2x">
		                      <i class="fa fa-circle fa-stack-2x text-primary"></i>
		                      <i class="fa fa-cogs fa-stack-1x fa-inverse"></i>
		                </span> 
		            </div>
		            <div class="media-body">
		                <h4 class="media-heading">Servicios</h4>
		                <p>Vas a tener la posibilidad de saber en todo momento el estado de los servicios que tengas instalados en tus dispositivos y dados de alta en nuestro sistema. Además, pulsando un solo botón vas a poder Iniciar o Detener el servicio sin necesidad de tener que acordarte y escribir comandos largos y engorrosos.</p>
		            </div>
		        </div>
            </div>
             <div class="col-md-6">
                <div class="media">
                    <div class="pull-left">
                        <span class="fa-stack fa-2x">
                              <i class="fa fa-circle fa-stack-2x text-primary"></i>
                              <i class="fa fa-th fa-stack-1x fa-inverse"></i>
                        </span> 
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Módulos</h4>
                        <p>Los módulos son funcionalidades a mayores que vamos a ir desarrollando con el tiempo y vas a poder activar o desactivar de tus configuraciones según tus necesidades. Actualmente contamos con un módulo de <a href="https://latch.elevenpaths.com/" target="_blank">Latch</a> para otorgar a Bedya de una mayor seguridad pero ya tenemos en mente muchos más.</p>
                    </div>
                </div>
                <div class="media">
                    <div class="pull-left">
                        <span class="fa-stack fa-2x">
                              <i class="fa fa-circle fa-stack-2x text-primary"></i>
                              <i class="fa fa-tag fa-stack-1x fa-inverse"></i>
                        </span> 
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Otros</h4>
                        <p>En este apartado de nuestro panel de control vas a poder dar de alta diferentes elementos de los cuales luego vas a poder obtener diferente información como por ejemplo las interfaces de red que tengas instaladas en tus equipos (lo,eth0,wlan1...)</p>
                    </div>
                </div>
                <div class="media">
                    <div class="pull-left">
                        <span class="fa-stack fa-2x">
                              <i class="fa fa-circle fa-stack-2x text-primary"></i>
                              <i class="fa fa-wrench fa-stack-1x fa-inverse"></i>
                        </span> 
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Configuraciones</h4>
                        <p>Finalmente tendrás que crear una configuración personalizada para cada dispositivo, seleccionando los elementos que hemos visto anteriormente y más se ajustan a la administración que vas a realizar.</p>
                    </div>
                </div>
            </div>
        </div>
        <a name="modulos"></a>
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Módulos</h2>
                <p>Anteriormente hemos mencionado que los módulos van a otorgar de más funcionalidades a tus equipos y que vamos a ir desarrollando con el tiempo. Estos módulos les ibas a poder activar o desactivar de tus configuraciones según tus necesidades.</p><br>
                <h4>Es hora de ver todos nuestros módulos!!</h4>

                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div>
                            <img style="width: 80px; float: left;" src="img/bedyastart.png" alt="bedyastart"  />
                        </div>
                        <div style="padding-left: 10%;">
                        	<h5>BedyaStart</h5>
                            <p>El módulo BedyaStart te permitirá saber cuando se ha iniciado o reiniciado tu host.<br> Esté se encargará de enviarte una notificación cada vez que ocurrá dicha acción.</p>
                        </div>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div>
                            <img style="width: 80px; float: left;" src="img/bedyalatch.png" alt="bedyastart"  />
                        </div>
                        <div style="padding-left: 10%;">
                        	<h5>Latch</h5>
                            <p>El módulo Latch le proporcionará un punto más de seguridad al control de tu bot.<br>En caso de que este módulo y Latch desde su App estén activado, nadie podrá enviar ordenes de control al host.<br><strong>Nota: </strong>Para el uso de esté módulo necesitaras tener una cuenta de Desarrollador en la página oficial de <a href="https://latch.elevenpaths.com/www/" target="_blank">Latch</a>.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <a name="started"></a>
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Getting Started</h2>
                <p>Es hora de dejarnos de rollos y empezar con lo que realmente nos interesa!!<br>A continuación vamos a ver en tres apartados los pasos que tenemos que seguir para empezar a utilizar Bedya.</p>
            </div>
            <div class="col-lg-12">
                <h3 class="page-header">Registrate</h3>
                <ol>
                    <li>Necesitaras crear una cuenta en <a href="https://web.telegram.org/#/login" target="_blank">Telegram</a> si todavía no la tienes.</li>
                    <li>Encuentra nuestro bot <a href="https://telegram.me/bedya_bot" target="_blank">@Bedya_bot</a> en Telegram y escribe <strong class="text-primary">/register</strong>, esto iniciará el proceso para crear tu cuenta de Bedya.</li>
                    <li>Nuestro bot te pedirá que introduzcas una contraseña con el siguiente formato <strong class="text-primary">/pass + contraseña</strong>. Por ejemplo <em>"/pass Ra1234"</em>.</li>
                    <li>Ahora podrás ir a <a href="http://bedya.es/login/">Bedya</a> y con tu ID de Telegram y la contraseña que has introducido anteriormente podrás acceder al Panel de Control.</li>

                </ol>
            </div>
            <div class="col-lg-12">
                <h3 class="page-header">Crea tus configuraciónes</h3>
                <p>A continuación daremos de alta todos los elementos necesarios y crearemos las configuraciones que nos ayudarán a administrar nuestros dispositivos IT.</p>
                <ol>
                    <li>Necesitaras crear un Bot para ello encuentra y habla con <a href="https://telegram.me/botfather" target="_blank">BotFather</a>, tendrás que seguir unos sencillos pasos.</li>
                    <blockquote>
                        <small>Si necesitas ayuda para crear tu bot puedes hacer <a href="https://core.telegram.org/bots#6-botfather" target="_blank">click aquí</a>.</small>
                    </blockquote>
                    <li>Una vez que hayas creado tu bot y tengas el token de autorización, vete a la sección <strong class="text-primary">Bots</strong> en nuestro Panel de Control y da de alta uno nuevo con el Token que has obtenido anteriormente.</li>
                    <blockquote>
                        <small>Será necesario dar de alta tantos bots como dispositivos IT vayamos a administrar.</small>
                    </blockquote>
                    <li>Entra en el apartado <strong class="text-primary">Administradores</strong> y da de alta los usuarios que posteriormente podrán gestionar tus equipos. Para ello necesitaras su ID de Telegram.</li>
                    <blockquote>
                        <small>Para saber el id de cada administrador será necesario que vayan a <a href="https://telegram.me/bedya_bot" target="_blank">@Bedya_bot</a> y pulsen el botón <strong>ID Telegram</strong>.</small>
                    </blockquote>
                    <li>En la ventana <strong class="text-primary">Servicios</strong> introduce todos los servicios que quieras gestionar (Apache, MySQL, FTP...).</li><br>
                    <li>Lo siguiente es completar los <strong class="text-primary">Módulos</strong> que van a otorgar a los bots de diferentes funcionalidades.</li>
                    <blockquote>
                        <small>Si no te interesa ningún módulo no es necesario rellenar este apartado, siempre vas a poder darles de alta más tarde.</small>
                    </blockquote>
                    <li>En la sección <strong class="text-primary">Otros</strong> podrás dar de alta las últimas características para tus dispositivos. Por ejemplo, las interfaces de red (eth0, eth1, wlan0 ...)</li><br>
                    <li>Finalmente en <strong class="text-primary">Configuraciones</strong> crear una configuración personalizada para el bot que has dado de alta y asocia los elementos que más se ajusten a la administración que vas a realizar.</li>
                </ol>
            </div>
            <div class="col-lg-12">
                <h3 class="page-header">Instalación</h3>
                <p>Puedes instalar Bedya en tus dispositivos de diferentes formas, pero antes es importante tener como mínimo la versión de <a href="https://www.python.org/">Python 2.7</a>.</p>
                
                <h5><i class="fa fa-cloud-download fa-fw"></i> WGET</h5>
                <code>$ wget https://github.com/RafaMunoz/Bedya/archive/master.zip</code><br>
                <code>$ unzip master.zip</code><br>
                <code>$ cd Bedya-master</code><br>
                <code>$ sudo python setup.py install</code>
                <br><br>

                <h5><i class="fa fa-github fa-fw"></i> GITHUB</h5>
                <code>$ git clone https://github.com/RafaMunoz/Bedya.git</code><br>
                <code>$ cd Bedya</code><br>
                <code>$ sudo python setup.py install</code>
                <br><br>
                
                <h4>Modifica la configuración por defecto</h4>
                <p>La primera configuración hay que realizarla manualmente, después todos los cambios que se realicen desde el Panel de Control se aplicarán automáticamente en los dispositivos.</p>
                <code>$ sudo nano /etc/bedya/infobot.json</code>
                <br><br>
                <p>Rellena los campos <strong>token</strong>, <strong>id</strong> y <strong>name</strong> y guarda los cambios usando <kbd>Ctrl+O</kbd> y <kbd>Ctrl+X</kbd>.</p>
<pre>
"token": "INTRODUCE AQUI TU TOKEN",
"admin": [
    {
        "id": "INTRODUCE AQUI TU ID",
        "name": "INTRODUCE AQUI TU NOMBRE"
    }
],
</pre>
                <p>Una vez modificado el archivo de configuración, puede iniciar el servicio utilizando el siguiente comando.</p>
                <code>$ sudo systemctl start bedya</code>
                <br>
                <br>
                <p>Automáticamente después de unos minutos se descargará la configuración que ha creado desde el Panel de Control.</p>
                <blockquote>
                        <small><strong>Tip:</strong>  Si no quieres esperar o necesitas forzar la descarga, puede enviar el comando <strong>/update</strong> a tu Bot.</small>
                    </blockquote>
            </div>
        </div>

        <hr>

        <!-- Call to Action Section -->
        <div class="well">
            <div class="row">
                <div class="col-md-12">
                    <h5>¿Tienes alguna idea?¿Te gustaría tener alguna funcionalidad en concreto?</h5>
            		<p>Nadie mejor que tu para decirnos lo que realmente necesitas.<br>Si crees que alguna funcionalidad puede ser interesante, no dudes en ponerte en contacto con nosotros y nuestro equipo evaluará tu propuesta.</p>
                    <a href="contacto" type="button" class="btn btn-default">Contactar</a>
                </div>
            </div>
        </div>

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Bedya 2017</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="bt1/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bt1/js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>

</body>

</html>
