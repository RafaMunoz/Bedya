<?php
    include '/var/www/html/conexion.php';

    session_name ("logged_in");
	session_start();
    
    if (!isset($_SESSION["usuario"])) {
        header("Location:/login/");
    }

    if (!isset($_COOKIE['Bedya'])) {
        header("Location:/login/");
    }

    $arraycookie = unserialize($_COOKIE['Bedya']);
    $conectMongo = $mongodbatlas;
    $manager = new MongoDB\Driver\Manager($conectMongo);

    //Borramos bot
    if ($arraycookie["codigo"] == 0) {
    	
	    $bulk = new MongoDB\Driver\BulkWrite;
		$bulk->update(["_id" => $_SESSION["usuario"],"bots.token"=>$arraycookie["token"]],['$unset' => ["bots.$"=>1]]);
		$bulk->update(["_id" => $_SESSION["usuario"]],['$pull' => ["bots"=>null]]);
		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		$result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(["_id" => $arraycookie["token"]]);
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('bedya.bots', $bulk,$writeConcern);

        header("Location:/control/bots?code=3"); //codigo 3 eliminado bot
    }

    //Borramos admin
    elseif($arraycookie["codigo"] == 1){
        
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(["_id" => $_SESSION["usuario"],"admin.id"=>$arraycookie["id"]],['$unset' => ["admin.$"=>1]]);
        $bulk->update(["_id" => $_SESSION["usuario"]],['$pull' => ["admin"=>null]]);
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

        header("Location:/control/administradores?code=3");
    }

    //Borramos servicio
    elseif($arraycookie["codigo"] == 2){
        
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(["_id" => $_SESSION["usuario"],"services.name"=>$arraycookie["id"]],['$unset' => ["services.$"=>1]]);
        $bulk->update(["_id" => $_SESSION["usuario"]],['$pull' => ["services"=>null]]);
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

        header("Location:/control/servicios?code=3");
    }

    //Borramos latch
    elseif($arraycookie["codigo"] == 3){
        
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(["_id" => $_SESSION["usuario"],"modules.appid"=>$arraycookie["appid"]],['$unset' => ["modules.$"=>1]]);
        $bulk->update(["_id" => $_SESSION["usuario"]],['$pull' => ["modules"=>null]]);
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

        $comando = "python /var/www/html/control/nucleo/despareo.py ".$arraycookie["appid"]." ".$arraycookie["secret"]." ".$arraycookie["accid"];
        exec($comando);

        header("Location:/control/modulos?code=3");

    }

    //borramos interfaz
    elseif($arraycookie["codigo"] == 4){
        
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(["_id" => $_SESSION["usuario"],"interfaces"=>$arraycookie["id"]],['$unset' => ["interfaces.$"=>1]]);
        $bulk->update(["_id" => $_SESSION["usuario"]],['$pull' => ["interfaces"=>null]]);
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('bedya.propiedades', $bulk,$writeConcern);

        header("Location:/control/otros?code=3");
    }

    //Actualizamos configuracion
    elseif($arraycookie["codigo"] == 5){
        $token = $arraycookie["bot"];
        $usuario = $_SESSION["usuario"];
        
        
        $filter = ["_id"=>$token,"usuario" => $usuario];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('bedya.bots', $query);
        $existe = 0;
        foreach ($cursor as $document) {
            $existe = 1;
        }
        if ($existe == 1) {

            $infodenied = 0;
            if (isset($arraycookie["infodenied"])) {
                $infodenied = 1;
            }

            $newconfig = array();
            $newconfig["_id"] = $document->_id;
            $newconfig["usuario"] = $document->usuario;
            $newconfig["name"] = $document->name;
            $newconfig["language"] = $arraycookie["idioma"];
            $newconfig["infobot"] = ($document->infobot)+1;
            $newconfig["downloads"] = $document->downloads;
            $newconfig["infodenied"] = $infodenied;
            $newconfig["control"] = $document->control;
            $newconfig["ultimapeticion"] = $document->ultimapeticion;
            $newconfig["admin"] = [];

        
            $filter = ["_id"=>$_SESSION["usuario"]];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $manager->executeQuery('bedya.propiedades', $query);
            foreach ($cursor as $propiedades) {

                $admins = $propiedades->{'admin'};
                foreach($admins as $values){
                    $nameadmins = $values->name;
                    $idadmin = $values->id;

                    if (in_array($idadmin, $arraycookie["admins"])) {
                        $newarray = array();
                        $newarray["name"] = $nameadmins;
                        $newarray["id"] = $idadmin;
                        $newconfig["admin"][] = $newarray;
                    }
                }

                $services = $propiedades->{'services'};
                foreach($services as $values){
                    $nb = $values->nb;
                    $nameS = $values->name;

                    if (in_array($nameS, $arraycookie["services"])) {
                        $newarray = array();
                        $newarray["nb"] = $nb;
                        $newarray["name"] = $nameS;
                        $newconfig["services"][] = $newarray;
                    }
                }

                $newconfig["interfaces"] = [];
                if (isset($arraycookie["interfaces"])) {
                    $interfaces = $arraycookie["interfaces"];
                    foreach($interfaces as $values){
                        $newconfig["interfaces"][] = $values;
                    }
                }

                if (isset($arraycookie["bedyastart"])) {
                    $newarray = array();
                    $newarray["name"] = "BedyaStart";
                    $newarray["activated"] = 1;
                    $newconfig["modules"][]= $newarray;
                }
                else{
                    $newarray = array();
                    $newarray["name"] = "BedyaStart";
                    $newarray["activated"] = 0;
                    $newconfig["modules"][]= $newarray;
                }

                $haylatch = 0;
                if (isset($arraycookie["latch"])) {
                    $modules = $propiedades->{'modules'};
                    foreach($modules as $values){
                        $nameL = $values->name;
                        
                        if ($nameL == "Latch") {
                            $idL = $values->id;
                            $accid = $values->accid;
                            $appid = $values->appid;
                            $key = $values->key;

                            if ($appid == $arraycookie["latch"]) {
                                $newarray = array();
                                $newarray["id"] = $idL;
                                $newarray["name"] = "Latch";
                                $newarray["appid"] = $appid;
                                $newarray["key"] = $key;
                                $newarray["accid"] = $accid;
                                $newarray["activated"] = (int)$arraycookie["latchActive"];
                                $newconfig["modules"][] = $newarray;
                                $haylatch = 1;
                            }
                        }
                    }
                }
                if ($haylatch == 0) {
                    $newarray = array();
                    $newarray["id"] = "0";
                    $newarray["name"] = "Latch";
                    $newarray["appid"] = "0";
                    $newarray["key"] = "0";
                    $newarray["accid"] = "0";
                    $newarray["activated"] = 0;
                    $newconfig["modules"][] = $newarray;
                }

            }
            
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(["_id" => $token],$newconfig);
            $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);

            try {
                $result = $manager->executeBulkWrite('bedya.bots', $bulk, $writeConcern);
                header("Location:/control/"); 
            } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
                $result = $e->getWriteResult();
                header("Location:/control/"); 
            }
        }
        header("Location:/login/");
    } 
    else{
        header("Location:/login/");
    }
    

?>
