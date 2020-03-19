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
$session = $sessionStorage->load();

$token = $session->getAccessToken();
$session->set('counter', 1);
$session->set('counter', intval($session->get('counter')) + 1);
$session['counter'] = $session['counter'] + 1;
foreach ($session as $name => $value) {
	var_dump($name);
	var_dump($value);
}
$sessionStorage->save($session);

$session = $sessionStorage->load($token);
var_dump($session->get('counter'));
var_dump($session['counter']);

$sessionStorage->remove($session);