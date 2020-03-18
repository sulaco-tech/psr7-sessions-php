<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

use \SulacoTech\PSR7Sessions\SessionFactoryInterface;
use \SulacoTech\PSR7Sessions\SessionInterface;
use \SulacoTech\PSR7Sessions\Session;

/**
 * Generic implementation of factory of session objects.
 */
class SessionFactory implements SessionFactoryInterface {

	/**
	 * {@inheritdoc}
	 */
	public function createSession(string $accessToken, array $sessionData = []): SessionInterface {
		return new Session($accessToken, $sessionData);
	}
}
