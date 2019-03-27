<?php
    include '/var/www/html/conexion.php';

    session_name ("logged_in");
	session_start();
    
    if (!isset($_SESSION["usuario"])) {
        header("Location: /login/");
    }

    function limpiarString($texto){
        $textoLimpio = str_replace ( '\'' ,'', $texto);
        $textoLimpio = str_replace ( 'select','', $texto);
        $textoLimpio = str_replace ( ' ','', $texto);
        $textoLimpio = str_replace ( '=','', $texto);
        $textoLimpio = str_replace ( 'union','', $texto);
        $textoLimpio = str_replace ( 'from','', $texto);
        return $textoLimpio;
    }

    if (isset($_POST["inombre"]) && isset($_POST["icomando"])) {
    	$nombre=limpiarString($_POST["inombre"]);
        $comando=limpiarString($_POST["icomando"]);

        if ((strlen($nombre) != 0)&&(strlen($comando) != 0)) {

        	$conectMongo = $mongodbatlas;
            $manager = new MongoDB\Driver\Manager($conectMongo);
        	$filter = ["_id"=>$_SESSION["usuario"],"services.name" => $comando];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $manager->executeQuery('bedya.propiedades', $query);
            $existe = 0;
            foreach ($cursor as $document) {
                $existe = 1;
            }
            if ($existe == 0) {
            	$bulk = new MongoDB\Driver\BulkWrite;
				$bulk->update(["_id" => $_SESSION["usuario"]],['$push' => ["services"=>["nb"=>$nombre,"name"=>$comando]]]);
				$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
				$result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

        		header("Location:/control/servicios?code=2");//Codigo 2 servicio añadido correctamente
            }
            else{
            	setcookie('Code',3,0,'/');
        		header("Location:/control/servicios?code=1");//Codigo 1 ya existe el servicio
            }

        }
        else{
        	header("Location:/control/servicios");
        }
    }
    else{
    	header("Location: /login/");
    }
?>
