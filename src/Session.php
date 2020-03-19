<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

use \SulacoTech\PSR7Sessions\SessionInterface;
use \Traversable;
use \ArrayIterator;

/**
 * Generic implementation of SessionInterface.
 */
class Session implements SessionInterface {

	private $sessionToken;
	private $sessionData;
	private $isChanged;

	/**
	 * @param string $accessToken Access token (usually from client's side).
	 * @param array $sessionData Session's data (loaded from storage by implementation of SessionManagerInterface).
	 */	
	public function __construct(string $accessToken, array $sessionData = []) {
		$this->accessToken = $accessToken;
		$this->sessionData = $sessionData;
		$this->isChanged = false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAccessToken(): string {
		return $this->accessToken;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get(string $name, $value = null) {
		return $this->sessionData[$name] ?? $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set(string $name, $value = null): void {
		if ($value === null) {
			$this->remove($name);
		} else {
			$this->sessionData[$name] = $value;
		}
		$this->isChanged = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function remove(string $name): void {
		unset($this->sessionData[$name]);
		$this->isChanged = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has(string $name): bool {
		return isset($this->sessionData[$name]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function all(): array {
		return $this->sessionData;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasChanges(): bool {
		return $this->isChanged;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isEmpty(): bool {
		return count($this->sessionData) <= 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetGet($offset) {
		return $this->get($offset);
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetUnset($offset) {
		$this->remove($offset);
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetExists($offset) {
		return $this->has($offset);
	}

	/**
	 * {@inheritdoc}
	 */
	public function count(): int {
		return count($this->sessionData);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator(): Traversable {
		return new ArrayIterator($this->sessionData);
	}
}
