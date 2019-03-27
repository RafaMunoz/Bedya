<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Contacto - Bedya</title>

    <link rel="shortcut icon" href="img/favicon.ico" />

    <!-- Bootstrap Core CSS -->
    <link href="bt1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="bt1/css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bt1/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
                        <a href="/#configuraciones">Configuraciones</a>
                    </li>
                    <li>
                        <a href="/#modulos">Módulos</a>
                    </li>
                    <li>
                        <a href="/#started">Getting Started</a>
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

    <!-- Page Content -->
    <div class="container">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Contacta
                    <small>con nosotros</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="/">Home</a>
                    </li>
                    <li class="active">Contacto</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                 <?php  
                    if(isset($_GET['code'])){
                        if ($_GET['code']==1) {
                            ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error al enviar el mensaje.
                            </div>
                            <?php
                        }
                        if ($_GET['code']==2) {
                            ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Mensaje enviado correctamente. Gracias por contactar con nosotros!!.
                            </div>
                            <?php
                        }
                    }
                ?>
                <form name="sentMessage" id="contactForm" action="mail/email" method="POST">
                    <div class="control-group form-group">
                        <div class="row">
                            <div class="col-lg-6" >
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                    <input  class="form-control" type="text" name="nombre" placeholder="Tu nombre *" required>
                                </div>
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-slack fa-fw"></i></span>
                                    <input class="form-control" type="text" placeholder="¿Estas registrado? Facilitanos tu ID Telegram" name="id">
                                </div>
                            </div>
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                            <input type="email" class="form-control" name="email" placeholder="Tu dirección de correo electrónico *" required>
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-comment fa-fw"></i></span>
                            <input type="text" class="form-control" name="asunto" placeholder="Asunto *" required>
                        </div>
                        <div class="control-group form-group">
                            <div class="controls">
                                <textarea placeholder="Tu mensaje..." rows="10" cols="100" class="form-control" name="mensaje" required  maxlength="999" style="resize:none"></textarea>
                            </div>
                        </div>

                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </form>
            </div>
             <div class="col-md-4" style="padding-top: 0px;">
                <h3 class="page-header"><small style="color: #343434;">¿Tienes alguna pregunta o sugerencia? ¿Te gustaría tener alguna funcionalidad en concreto?</small></h3>
                <p style="color: #777;">Nadie mejor que tu para decirnos lo que realmente necesitas.<br>Si crees que alguna funcionalidad puede ser interesante, no dudes en contactar con nosotros y nuestro equipo evaluará tu propuesta.</p><br>
                <i class="fa fa-envelope fa-fw"></i> Email: <a href="mailto:Soporte@bedya.es">Soporte@bedya.es</a>
            </div>

        </div>
        <!-- /.row -->

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

</body>

</html>
