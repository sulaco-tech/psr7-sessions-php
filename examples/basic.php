<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use \SulacoTech\PSR7Sessions\SessionFileStorageConfiguration;
use \SulacoTech\PSR7Sessions\SessionFileStorage;

$sessionsDirectory = __DIR__ . '/tmp';
$sessionName = 'psr7session';
$sessionsExpirationTime = 5 * 60; // in seconds
$config = new SessionFileStorageConfiguration($sessionsDirectory, $sessionName, $sessionsExpirationTime);

$sessionStorage = new SessionFileStorage($config);
$sessionStorage->gc();
$session = $sessionStorage->getSession();

$token = $session->getAccessToken();
$session->set('counter', 1);
$session->set('counter', intval($session->get('counter')) + 1);
$sessionStorage->saveSession($session);

$session = $sessionStorage->getSession($token);
var_dump($session->get('counter'));

//$sessionStorage->removeSession($session);