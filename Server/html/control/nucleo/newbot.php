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

    if (isset($_POST["inombre"]) && isset($_POST["itoken"])) {
    	$nombre=limpiarString($_POST["inombre"]);
        $token=limpiarString($_POST["itoken"]);

        if ((strlen($nombre) != 0)&&(strlen($token) != 0)) {

        	$conectMongo = $mongodbatlas;
            $manager = new MongoDB\Driver\Manager($conectMongo);
        	$stats = new MongoDB\Driver\Command(["count" => "bots"]);
    		$res = $manager->executeCommand("bedya", $stats);
    
    		$stats = current($res->toArray());
			$count = ($stats->n)%600;

        	$filter = ["_id"=>$_SESSION["usuario"],"bots.token" => $token];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $manager->executeQuery('bedya.propiedades', $query);
            $existe = 0;
            foreach ($cursor as $document) {
                $existe = 1;
            }
            if ($existe == 0) {
            	$bulk = new MongoDB\Driver\BulkWrite;
				$bulk->update(["_id" => $_SESSION["usuario"]],['$push' => ["bots"=>["name"=>$nombre,"token"=>$token]]]);
				$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
				$result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

				$bulk = new MongoDB\Driver\BulkWrite;
				$bulk->insert(["_id" =>$token]);
				$bulk->update(["_id" => $token],['$set' => ["usuario"=>$_SESSION["usuario"],"name"=>$nombre,"language"=>"ES","infobot"=>0,"downloads"=>"0","infodenied"=>1,"ultimapeticion"=>0,"control"=>$count]]);
				$bulk->update(["_id" => $token],['$push' => ["admin"=>["name"=>$_SESSION["nombre"],"id"=>$_SESSION["usuario"]],"services"=>null,"interfaces"=>null,"modules"=>["name"=>"BedyaStart","activated"=>0]]]);
				$bulk->update(["_id" => $token],['$push' => ["modules"=>["name"=>"Latch","id"=>"","activated"=>0,"accid"=>"","key"=>"","appid"=>""]]]);
				$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
				$result = $manager->executeBulkWrite('bedya.bots', $bulk,$writeConcern);
        		header("Location:/control/bots?code=2");//Codigo 2 bot añadido correctamente
            }
            else{
                echo $existe;
        		header("Location:/control/bots?code=1");//Codigo 1 ya existe el bot
            }

        }
        else{
        	header("Location:/control/bots");
        }
    }
    else{
    	header("Location: /login/");
    }
?>
