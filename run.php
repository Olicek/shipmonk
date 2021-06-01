<?php

use App\Application;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

/** @var EntityManager $entityManager */
$entityManager = require __DIR__ . '/src/bootstrap.php';

$loader = new Nette\DI\ContainerLoader(__DIR__ . '/temp');
$class = $loader->load(function($compiler) {
  $compiler->loadConfig(__DIR__ . '/config.neon');
});
/** @var \Nette\DI\Container $container */
$container = new $class;

$request = new Request('POST', new Uri('http://localhost/pack'), ['Content-Type' => 'application/json'], $argv[1]);


$application = $container->getByType(Application::class);

$response = $application->run($request);

echo "<<< In:\n" . Message::toString($request) . "\n\n";
echo ">>> Out:\n" . Message::toString($response) . "\n\n";
