<?php
include '/var/www/html/conexion.php';
function limpiarString($texto){
    $textoLimpio = str_replace ( '\'' ,'', $texto);
    $textoLimpio = str_replace ( 'select','', $texto);
    $textoLimpio = str_replace ( ' ','', $texto);
    $textoLimpio = str_replace ( '=','', $texto);
    $textoLimpio = str_replace ( 'union','', $texto);
    $textoLimpio = str_replace ( 'from','', $texto);
    return $textoLimpio;
}

if(isset($_POST["id"]) && isset($_POST["password"]) ){
    sleep(1);
    $id=limpiarString($_POST["id"]);
    $password=limpiarString($_POST["password"]);

    $conectMongo = $mongodbatlas;
    $manager = new MongoDB\Driver\Manager($conectMongo);

    $filter = ["_id" => $id];
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('bedya.usuarios', $query);
    $i = 0;
    foreach ($cursor as $document) {
        $i++;
        $json = json_encode($document);
        
        $passwordbd = $document->{'password'};
        $salt = $document->{'salt'};
        $nombre = $document->{'nombre'};
        $apellido = $document->{'apellido'};
        $idbd = $document->{'_id'};

        if(($id == $idbd) && (sha1($password.$salt) == $passwordbd)){
            session_name ("logged_in");
            session_start();
            $_SESSION['usuario']=$id;
            $_SESSION['nombre']=$nombre." ".$apellido;

            $fecha = date("Y-m-d H:i:s");

            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(["_id" => $id],['$set' => ['ultimo_inicio' => $fecha]]);
            $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
            $result = $manager->executeBulkWrite('bedya.usuarios', $bulk,$writeConcern);

            header("Location:/control/dashboard");
        }
        else{
            header("Location:/login/?code=1");
        }
        
    }
    if ($i==0) {
        header("Location:/login/?code=1");
    }
}
else{
    header("Location:/");
}

?>     
