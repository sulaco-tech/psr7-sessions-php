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
	 * Load session's data from the storage using access token.
	 *
	 * @param string $accessToken Access token (pass null to generate new token).
	 *
	 * @return \SulacoTech\PSR7Sessions\SessionInterface Returns instance of implementation of SessionInterface.
	 */
	public function load(string $accessToken = null): SessionInterface;

	/**
	 * Save session's data into the storage.
	 *
	 * @param \SulacoTech\PSR7Sessions\SessionInterface $session Instance of implementation of SessionInterface.
	 */
	public function save(SessionInterface $session): void;

	/**
	 * Remove expired sessions.
	 */
	public function gc(): void;
}
