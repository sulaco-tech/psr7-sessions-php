<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

use \SulacoTech\PSR7Sessions\SessionStorageInterface;
use \SulacoTech\PSR7Sessions\TokenFactoryInterface;
use \SulacoTech\PSR7Sessions\SessionFactory;
use \SulacoTech\PSR7Sessions\TokenFactory;
use \InvalidArgumentException;

use function sprintf;
use function is_dir;

/**
 * Class represents configuration for class SessionFileStorage.
 */
class SessionFileStorageConfiguration {
	
	private $sessionDirectory;
	private $sessionName;
	private $sessionExpirationTime;
	private $sessionFactory;
	private $tokenFactory;

	/**
	 * @param string $directoryName Path to directory wich contains session's files.
	 * @param string $sessionName Name of session's key.
	 * @param int|null $sessionExpirationTime Expiration time in seconds or null if it is unlimited.
	 * @param \SulacoTech\PSR7Sessions\SessionFactoryInterface|null $sessionFactory Implementation of factory of session objects.
	 * @param \SulacoTech\PSR7Sessions\TokenFactoryInterface|null $tokenFactory Implementation of factory of access tokens.
	 * 
	 * @throws \InvalidArgumentException If some of given arguments is invalid.
	 */
	public function __construct(
		string $directoryName,
		string $sessionName,
		int $sessionExpirationTime = null,
		SessionFactoryInterface $sessionFactory = null,
		TokenFactoryInterface $tokenFactory = null
	) {

		$this->setDirectoryName($directoryName);
		$this->setSessionName($sessionName);
		$this->setSessionExpirationTime($sessionExpirationTime);
		$this->setSessionFactory($sessionFactory);
		$this->setTokenFactory($tokenFactory);
	}

	/**
	 * Get name of directory.
	 *
	 * @return string Returns a name of directory (path to directory).
	 */
	public function getDirectoryName(): string {

		return $this->directoryName;
	}

	/**
	 * @param string $directoryName Path to directory wich contains session's files.
	 * 
	 * @throws \InvalidArgumentException If the given path to directory is not exists or not a directory.
	 */
	public function setDirectoryName(string $directoryName): void {

		if (!is_dir($directoryName)) {
			throw new InvalidArgumentException(sprintf(
				"Directory not found: %s",
				$directoryName
			));
		}

		$this->directoryName = $directoryName;
	}

	/**
	 * Get name of session.
	 *
	 * @return string Returns a name of session.
	 */
	public function getSessionName(): string {

		return $this->sessionName;
	}

	/**
	 * @param string $sessionName Name of session's key.
	 *
	 * @return string Returns a name of session.
	 */
	public function setSessionName(string $sessionName): void {

		$this->sessionName = $sessionName;
	}

	/**
	 * Get expiration time of sessions.
	 *
	 * @return int|null Returns an expiration time in seconds or null if it is unlimited.
	 */
	public function getSessionExpirationTime(): ?int {

		return $this->sessionExpirationTime;
	}

	/**
	 * @param int|null $sessionExpirationTime Expiration time in seconds or null if it is unlimited.
	 * 
	 * @throws \InvalidArgumentException If the given expiration time is invalid.
	 */
	public function setSessionExpirationTime(int $sessionExpirationTime = null): void {

		if ($sessionExpirationTime !== null && $sessionExpirationTime < 0) {
			throw new InvalidArgumentException(sprintf(
				"Invalid expiration time: %s",
				$sessionExpirationTime
			));
		}

		$this->sessionExpirationTime = $sessionExpirationTime;
	}

	/**
	 * Get factory of sessions.
	 *
	 * @return \SulacoTech\PSR7Sessions\SessionFactoryInterface Returns an instance of factory.
	 */
	public function getSessionFactory(): SessionFactoryInterface {

		return $this->sessionFactory;
	}

	/**
	 * @param \SulacoTech\PSR7Sessions\SessionFactoryInterface|null $sessionFactory Implementation of factory of session objects.
	 */
	public function setSessionFactory(SessionFactoryInterface $sessionFactory = null): void {

		if ($sessionFactory === null) {
			$sessionFactory = new SessionFactory();
		}

		$this->sessionFactory = $sessionFactory;
	}

	/**
	 * Get factory of tokens.
	 *
	 * @return \SulacoTech\PSR7Sessions\TokenFactoryInterface Returns an instance of factory.
	 */
	public function getTokenFactory(): TokenFactoryInterface {

		return $this->tokenFactory;
	}

	/**
	 * @param \SulacoTech\PSR7Sessions\TokenFactoryInterface|null $tokenFactory Implementation of factory of access tokens.
	 */
	public function setTokenFactory(TokenFactoryInterface $tokenFactory = null): void {

		if ($tokenFactory === null) {
			$tokenFactory = new TokenFactory();
		}

		$this->tokenFactory = $tokenFactory;
	}
}
