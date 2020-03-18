<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

use \SulacoTech\PSR7Sessions\SessionFactoryInterface;
use \SulacoTech\PSR7Sessions\SessionInterface;
use \SulacoTech\PSR7Sessions\Session;

/**
 * Describe the interface which represents a factory of session objects.
 */
interface SessionFactoryInterface {

	/**
	 * Create new instance of implementation of SessionInterface.
	 *
	 * @param string $accessToken Access token (usually from client's side).
	 * @param array $sessionData Session's data (loaded from storage by implementation of SessionManagerInterface).
	 *
	 * @return \SulacoTech\PSR7Sessions\SessionInterface Returns an instance of session's object.
	 */
	public function createSession(string $accessToken, array $sessionData = []): SessionInterface;
}
