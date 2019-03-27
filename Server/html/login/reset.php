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

    function sendMessage($idusu){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot".$bottoken."/sendMessage?chat_id=".$idusu."&text=You have requested a password change type /pass followed by your password. For example: /pass Ra1234");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error al enviar código de confirmación';
        }
        curl_close ($ch);
    }
    
    if(isset($_POST["id"])){
        $id=limpiarString($_POST["id"]);

        $conectMongo = $mongodbatlas;
        $manager = new MongoDB\Driver\Manager($conectMongo);
        $filter = ["_id" => $id];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('bedya.usuarios', $query);
        $i = 0;
        foreach ($cursor as $document) {
            $i++;
        }
        if ($i==0) {
            header("Location:password_reset?code=1"); //Codigo 1 no existe el usuario
        }
        else{
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(["_id" => $id],['$set' => ['registrado' => 3]]);
            $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
            $result = $manager->executeBulkWrite('bedya.usuarios', $bulk,$writeConcern);
            sendMessage($id);
            header("Location:password_reset?code=2"); //Codigo 2 se ha puesto en modo cambio de contraseña
        }
    }
    else{
        header("Location:/login/");
    }
    
?>     
