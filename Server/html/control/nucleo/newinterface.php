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

    if (isset($_POST["inombre"])) {
    	$nombre=limpiarString($_POST["inombre"]);

        if (strlen($nombre) != 0) {

        	$conectMongo = $mongodbatlas;
            $manager = new MongoDB\Driver\Manager($conectMongo);
        	$filter = ["_id"=>$_SESSION["usuario"],"interfaces" => $nombre];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $manager->executeQuery('bedya.propiedades', $query);
            $existe = 0;
            foreach ($cursor as $document) {
                $existe = 1;
            }
            if ($existe == 0) {
            	$bulk = new MongoDB\Driver\BulkWrite;
				$bulk->update(["_id" => $_SESSION["usuario"]],['$push' => ["interfaces"=>$nombre]]);
				$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
				$result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

        		header("Location:/control/otros?code=2");//Codigo 2 interfaz añadida correctamente
            }
            else{
            	setcookie('Code',3,0,'/');
        		header("Location:/control/otros?code=1");//Codigo 1 ya existe la interfaz
            }

        }
        else{
        	header("Location:/control/otros");
        }
    }
    else{
    	header("Location: /login/");
    }
?>
