<?php
    session_name ("logged_in");
    session_start();

    if (isset($_SESSION["usuario"])) {
        header("Location:../control/dashboard");
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

        <title>Login</title>

        <link rel="shortcut icon" href="../img/favicon.ico" />

        <!-- Bootstrap Core CSS -->
        <link href="../bt2/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="../bt2/css/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="../bt2/css/startmin.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="../bt2/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->


        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <a href="/"><img style="display: block;width: 70%;margin: 0 auto;" src="../img/mini-bedya.png" alt="bedya logo"  /></a>
                        </div>
                        <div class="panel-body">
                            <form role="form" action="login" method="post">
                                <fieldset>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon"><i class="fa fa-slack fa-fw"></i></span>
                                        <input class="form-control" placeholder="ID Telegram" name="id" type="text" autofocus="">
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <button type="submit" id="acceder" class="btn btn-lg btn-primary btn-block">Login</button>
                                </fieldset><br>
                                <?php  
                                    if(isset($_GET['code'])){
                                        if ($_GET['code']==1) {
                                            ?>
                                            <div class="alert alert-danger alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                Usuario/Contraseña Incorrectos.
                                            </div>
                                            <?php
                                        }
                                    }
                                ?>
                            </form>
                            
                            <p class="create-account-callout mt-3" style="text-align: center;">
                                <a href="https://telegram.me/bedya_bot" data-ga-click="Sign in, switch to sign up">Create an account | </a>
                                <a href="password_reset" class="label-link">Forgot password?</a>
                            </p>
			    <div style="text-align: center; margin-top: 30px;"><script async src="https://telegram.org/js/telegram-widget.js?4" data-telegram-login="Bedya_bot" data-size="large" data-auth-url="https://www.bedya.es/login/check_authorization" data-request-access="write"></script></div>
                        </div>
                    </div>
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
