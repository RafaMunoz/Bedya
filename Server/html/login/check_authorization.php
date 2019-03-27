<?php
include '/var/www/html/conexion.php';
$conectMongo = $mongodbatlas;

define('BOT_TOKEN', $bottoken); // place bot token of your bot here
function checkTelegramAuthorization($auth_data) {
  $check_hash = $auth_data['hash'];
  unset($auth_data['hash']);
  $data_check_arr = [];
  foreach ($auth_data as $key => $value) {
    $data_check_arr[] = $key . '=' . $value;
  }
  sort($data_check_arr);
  $data_check_string = implode("\n", $data_check_arr);
  $secret_key = hash('sha256', BOT_TOKEN, true);
  $hash = hash_hmac('sha256', $data_check_string, $secret_key);
  if (strcmp($hash, $check_hash) !== 0) {
    throw new Exception('Data is NOT from Telegram');
  }
  if ((time() - $auth_data['auth_date']) > 86400) {
    throw new Exception('Data is outdated');
  }
  return $auth_data;
}
function saveTelegramUserData($auth_data) {

  $id = $auth_data['id'];
  $nombre = $auth_data['first_name'];
  $username = $auth_data['username'];
  $photo_url = $auth_data['photo_url'];

  $fecha = date("Y-m-d H:i:s");

  global $conectMongo;
  $manager = new MongoDB\Driver\Manager($conectMongo);
  $filter = ["_id" => $id];
  $options = [];
  $query = new MongoDB\Driver\Query($filter, $options);
  $cursor = $manager->executeQuery('bedya.usuarios', $query);

  $i = 0;
  foreach ($cursor as $document) {
    $i++;
    $json = json_encode($document);

    $idbd = $document->{'_id'};

    if($id == $idbd){
      session_name ("logged_in");
      session_start();
      $_SESSION['usuario']=$id;
      $_SESSION['nombre']=$nombre;

      $bulk = new MongoDB\Driver\BulkWrite;
      $bulk->update(["_id" => $id],['$set' => ['ultimo_inicio' => $fecha, 'nombre' => $nombre, 'username' => $username, 'photo_url' => $photo_url]]);
      $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
      $result = $manager->executeBulkWrite('bedya.usuarios', $bulk,$writeConcern);
    }
    print("aqui");
  }
  if ($i==0) {
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert(["_id" =>$id]);
    $bulk->update(["_id" => $id],['$set' => ['nombre' => $nombre, 'apellido' => null, 'username' => $username, 'registrado' => 2, 'idioma'=> 'ES', 'password' => null, 'salt'=> null, 'fecha_registro' => $fecha, 'ultimo_inicio' => $fecha, 'photo_url' => $photo_url, 'codigo' => null, 'tipo' => 0]]);

    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $manager->executeBulkWrite('bedya.usuarios', $bulk,$writeConcern);

    $comando = "python crear_propiedades.py ".$id." ".$nombre;
    exec($comando);

    session_name ("logged_in");
    session_start();
    $_SESSION['usuario']=$id;
    $_SESSION['nombre']=$nombre;

  }
}
try {
  $auth_data = checkTelegramAuthorization($_GET);
  saveTelegramUserData($auth_data);
} catch (Exception $e) {
  die ($e->getMessage());
}
header("Location:/control/dashboard");
?>
