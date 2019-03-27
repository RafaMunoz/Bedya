<?php
    include '/var/www/html/conexion.php';
    
    session_name ("logged_in");
	session_start();
    
    if (!isset($_SESSION["usuario"])) {
        header("Location:/login/");
    }
    if (!isset($_POST["codigo"])) {
		header("Location:/login/");
	}

    function codigoconf(){
        $numero = "";
        for ($i=0; $i <4 ; $i++) { 
            $numero = $numero.rand(0,9);
        }
        return $numero;
    }

	function sendMessage($idusu,$codigo){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot".$bottoken."/sendMessage?chat_id=".$idusu."&text=Código de confirmación: ".$codigo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error al enviar código de confirmación';
        }
        curl_close ($ch);
    }
    $generado = codigoconf();
    sendMessage($_SESSION["usuario"],$generado);

    $conectMongo = $mongodbatlas;
    $manager = new MongoDB\Driver\Manager($conectMongo);

    $bulk = new MongoDB\Driver\BulkWrite;
	$bulk->update(["_id" => $_SESSION["usuario"]],['$set' => ['codigo' => $generado]]);
	$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
	$result = $manager->executeBulkWrite('bedya.usuarios', $bulk,$writeConcern);

    if (isset($_GET["new"]) == False) {
        setcookie('Bedya',serialize($_POST),0,'/');
    }

	header("Location:../confirmacion");
	
    
?>
