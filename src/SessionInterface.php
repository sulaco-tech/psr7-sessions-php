<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

/**
 * Describe the interface for access to session's data.
 */
interface SessionInterface {

	/**
	 * Get token to save it on client's side.
	 *
	 * @return string Access token.
	 */
	public function getAccessToken(): string;
	
	/**
	 * Finds an entry of the session's data by its name and returns it.
	 *
	 * @param string $name Name of entry.
	 * @param mixed $value Default value.
	 *
	 * @return mixed If entry is exists, then returns it's value, elsewere returns a specified default value.
	 */	
	public function get(string $name, $value = null);

	/**
	 * Save value into session's data using specified name.
	 *
	 * @param string $name Name of value.
	 * @param string $value Value to save.
	 */
	public function set(string $name, $value): void;

	/**
	 * Remove an entry of the session's data by its name.
	 *
	 * @param string $name Name of value.
	 */
	public function remove(string $name): void;

	/**
	 * Checks whether the session's data contains the given name.
	 *
	 * @param string $name Name of value.
	 *
	 * @return bool True is the given name is exists, otherwise - false.
	 */
	public function has(string $name): bool;

	/**
	 * Get all entries of the session's data and returns it as array.
	 *
	 * @return array Returns array of entries.
	 */	
	public function all(): array;

	/**
	 * Checks whether the session has changed.
	 *
	 * @return bool True is session data has been changed, otherwise false.
	 */	
	public function hasChanges(): bool;

	/**
	 * Checks whether the session contains any data.
	 *
	 * @return bool True is session does not contains any data, otherwise false.
	 */
	public function isEmpty(): bool;
}
