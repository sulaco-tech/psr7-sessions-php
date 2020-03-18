<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

/**
 * Describe the interface which represent factory for access tokens.
 */
interface TokenFactoryInterface {

	/**
	 * Generate new access token (key for cookies).
	 *
	 * @return string Generated access token.
	 */
	public function createToken(): string;
	
	/**
	 * Find whether the $token is a valid access token.
	 * 
	 * @param string $token Access token from client's side.
	 * @return bool Returns true if the token is a valid access token, or false otherwise.
	 */
	public function isValidToken(string $token): bool;
}
