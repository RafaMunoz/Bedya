<?php
/* 
sudo composer require slim/slim "^3.0"

.htaccess

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]

*/

include '/var/www/html/conexion.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$c = new \Slim\Container(); //Create Your container

//Override the default Not Found Handler
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"error_code":"404","description":"Not Found"}');
    };
};

$bd_col = $bd2.".sensores";

$app = new \Slim\App($c);

$d = $app->getContainer();
$d['notAllowedHandler'] = function ($d) {
    return function ($request, $response, $methods) use ($d) {
        return $d['response']
            ->withStatus(405)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"error_code":"405","description":"Method Not Allowed"}');
    };
};

$e = $app->getContainer();
$c['phpErrorHandler'] = function ($e) {
    return function ($request, $response, $error) use ($e) {
        return $e['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"error_code":"500","description":"Internal Server Error"}');
    };
};


header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

$conectMongo = $mongodbatlas;
$manager = new MongoDB\Driver\Manager($conectMongo);


$app->get('/infobot/{usuario}/{token}', function (Request $request, Response $response) use ($manager,$bd_col) {
    $id = $request->getAttribute('usuario');
    $token = $request->getAttribute('token');

    $fecha = date("Y-m-d H:i:s");

    $filter = ["_id" => $token,"usuario" => $id];
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    
    try {
    	$cursor = $manager->executeQuery('bedya.bots', $query);
    	foreach ($cursor as $document) {
		    $json = json_encode($document);
		}

	    if ($document == NULL) {
	    	$data = array('error_code' => '404','description' => 'Not Found');
	    	$response = $response ->withJson($data, 404);
	    }
	    else{
	    	$obj = json_decode($json);
			$downloads =  $obj->{'downloads'};
			$infobot = $obj->{'infobot'};
			$tokenfile = array('token'=>$obj->{'_id'});
			$json2 = json_decode($json, true);
			unset($json2["ultimapeticion"]);
			unset($json2["codigo"]);
			unset($json2["_id"]);
			unset($json2["usuario"]);
			unset($json2["infobot"]);
			unset($json2["downloads"]);
			$json2=$tokenfile+$json2;

			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->update(["_id" => $token,"usuario" => $id],['$set' => ['ultimapeticion' => $fecha]]);
			$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
			$result = $manager->executeBulkWrite('bedya.bots', $bulk,$writeConcern);

	    	$data = array('ok'=>true,'infobot'=>$infobot,'downloads'=>$downloads,'configuration'=>$json2);
        	$response = $response ->withJson($data, 200);
        }

    } catch (MongoDB\Driver\Exception\Exception $e) {
        $data = array('error_code' => '500','description' => 'Internal Server Error');
	    $response = $response ->withJson($data, 500);
    }

    return $response;
});


$app->run();

?>
