<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['view'] = new \Slim\Views\PhpRenderer("../templates/");

$app->get('/listPersonne',function(Request $request, Response $response)
{
	$this->logger->addInfo("Liste de personne");
	$responseJson = file_get_contents('https://www.wavemakeronline.com/run-39kysdsr0k/Service/services/ServiceBDD/Personne');
	$listPersonne = json_decode($responseJson);
	$response = $this->view->render($response,"listePersonne.phtml",["listPersonne" => $listPersonne]);
	return $response;
});



$app->run();