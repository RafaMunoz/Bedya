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

    if (isset($_POST["inombre"]) && isset($_POST["iid"])  && isset($_POST["isecreto"])  && isset($_POST["ipareo"])) {
    	$nombre=limpiarString($_POST["inombre"]);
        $appid=limpiarString($_POST["iid"]);
        $secreto=limpiarString($_POST["isecreto"]);
        $pareo=limpiarString($_POST["ipareo"]);

        if ((strlen($nombre) != 0)&&(strlen($appid) != 0)&&(strlen($secreto) != 0)&&(strlen($pareo) != 0)) {
            $comando = "python /var/www/html/control/nucleo/pareo.py ".$appid." ".$secreto." ".$pareo;
            $accid = exec($comando);

            if ($accid != "") {

                $conectMongo = $mongodbatlas;
                $manager = new MongoDB\Driver\Manager($conectMongo);
                $filter = ["_id"=>$_SESSION["usuario"],"modules.appid" => $appid];
                $options = [];
                $query = new MongoDB\Driver\Query($filter, $options);
                $cursor = $manager->executeQuery('bedya.propiedades', $query);
                $existe = 0;
                foreach ($cursor as $document) {
                    $existe = 1;
                }
                if ($existe == 0) {
                    $bulk = new MongoDB\Driver\BulkWrite;
                    $bulk->update(["_id" => $_SESSION["usuario"]],['$push' => ["modules"=>["name"=>"Latch","id"=>$nombre,"appid"=>$appid,"key"=>$secreto,"accid"=>$accid]]]);
                    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
                    $result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

                    header("Location:/control/modulos?code=2"); //Codigo 2 latch añadido correctamente
                }
                else{
                    header("Location:/control/modulos?code=3"); //Codigo 3 ya existe el latch
                }

            }
            else{
                header("Location:/control/modulos"); //Latch no se ha podido parear
            }
        }
        else{
        	header("Location:/control/modulos");
        }
    }
    else{
    	header("Location: /login/");
    }
?>
