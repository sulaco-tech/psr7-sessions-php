<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

use \SulacoTech\PSR7Sessions\SessionInterface;

/**
 * Describe the interface for access to sessions.
 */
interface SessionStorageInterface {

	/**
	 * Get name of cookie (key in cookies).
	 *
	 * @return string Name of cookie.
	 */
	public function getCookieName(): string;

	/**
	 * Get SessionInterface using name of session and access token from client's side.
	 *
	 * @param string $accessToken Access token (pass null to generate new token).
	 *
	 * @return \SulacoTech\PSR7Sessions\SessionInterface Returns instance of implementation of SessionInterface.
	 */
	public function getSession(string $accessToken = null): SessionInterface;

	/**
	 * Save session's data from SessionInterface into storage.
	 *
	 * @param \SulacoTech\PSR7Sessions\SessionInterface $session Instance of implementation of SessionInterface.
	 */
	public function saveSession(SessionInterface $session): void;

	/**
	 * Remove session from storage.
	 *
	 * @param \SulacoTech\PSR7Sessions\SessionInterface $session Instance of implementation of SessionInterface.
	 */
	public function removeSession(SessionInterface $session): void;
	
	/**
	 * Remove expired sessions.
	 */
	public function gc(): void;
}
