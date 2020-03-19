<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

use \SulacoTech\PSR7Sessions\Session;
use \SulacoTech\PSR7Sessions\SessionFactoryInterface;
use \SulacoTech\PSR7Sessions\SessionInterface;
use \SulacoTech\PSR7Sessions\SessionStorageInterface;
use \SulacoTech\PSR7Sessions\TokenFactoryInterface;
use \SulacoTech\PSR7Sessions\SessionFileStorageConfiguration;
use \RuntimeException;
use \InvalidArgumentException;

use function sprintf;
use function file_get_contents;
use function file_put_contents;
use function json_decode;
use function json_encode;
use function stat;
use function is_file;
use function is_dir;
use function is_array;
use function time;
use function preg_match;

/**
 * Simple file storage implementation.
 */
class SessionFileStorage implements SessionStorageInterface {
	
	const FORMAT_VERSION = '1.0.0';

	private $configuration;

	/**
	 * @param \SulacoTech\PSR7Sessions\SessionFileStorageConfiguration $configuration The configuration for a storage of this type.
	 */
	public function __construct(SessionFileStorageConfiguration $configuration) {

		$this->configuration = $configuration;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCookieName(): string {

		return $this->getSessionName();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function load(string $accessToken = null): SessionInterface {

		$sessionFactory = $this->configuration->getSessionFactory();
		$tokenFactory = $this->configuration->getTokenFactory();

		if ($accessToken === null) {
			$accessToken = $tokenFactory->createToken();
		} else {
			if (!$tokenFactory->isValidToken($accessToken)) {
				$accessToken = $tokenFactory->createToken();
			}
		}

		$fileName = $this->getFileName($accessToken);

		if ($this->isFileExpired($fileName)) {
			return $sessionFactory->createSession($accessToken);
		}

		$data = file_get_contents($fileName);
		if ($data === false) {
			throw new RuntimeException(sprintf(
				"File not found: %s",
				$fileName
			));
		}

		$data = json_decode($data, true);
		if (!is_array($data) || ($data['version'] ?? null) !== self::FORMAT_VERSION) {
			return $sessionFactory->createSession($tokenFactory->createToken());
		}
		
		return $sessionFactory->createSession($accessToken, $data['data'] ?? []);
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(SessionInterface $session): void {

		$fileName = $this->getFileName($session->getAccessToken());
		$sessionData = $session->all();

		if (!is_file($fileName) && $session->isEmpty()) {
			return;
		}

		if (!$session->hasChanges()) {
throw new \Exception("SESSION IS NOT CHANGED");
			return;
		}

		$data = json_encode(
			[
				'version' => self::FORMAT_VERSION,
				'data' => $session->all()
			],
			JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
		);

		if (file_put_contents($fileName, $data) === false) {
			throw new RuntimeException(sprintf(
				"Permission denied: %s",
				$fileName
			));
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function remove(SessionInterface $session): void {

		$fileName = $this->getFileName($session->getAccessToken());

		if (is_file($fileName)) {
			unlink($fileName);
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function gc(): void {

		$sessionName = $this->configuration->getSessionName();
		$sessionDirectory = $this->configuration->getDirectoryName();

		$length = strlen($sessionName);
		$filesList = scandir($sessionDirectory);

		foreach ($filesList as $fileName) {

			if (strlen($fileName) < $length || substr($fileName, 0, $length) !== $sessionName) {
				continue;
			}

			$fileName = "{$sessionDirectory}/{$fileName}";
			if ($this->isFileExpired($fileName)) {
				unlink($fileName);
			}
		}
	}
	
	/**
	 * Detect is file is expired.
	 * 
	 * @param string $fileName Path to session's file.
	 * @return bool Returns true if file is expired, or false otherwise.
	 */
	protected function isFileExpired(string $fileName): bool {

		$fileStat = (is_file($fileName) ? stat($fileName) : false);

		if ($fileStat === false || ($fileStat['mtime'] + $this->configuration->getSessionExpirationTime()) < time()) {
			return true;
		}

		return false;
	}

	/**
	 * Find whether the name of a session is a valid name.
	 * 
	 * @param string $name Name of session.
	 * @return bool Returns true if the name is a valid name of a session, or false otherwise.
	 */
	protected function isValidSessionName(string $name): bool {

		return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name) ? true : false;
	}

	/**
	 * Get the name of a session.
	 * 
	 * @throws \RuntimeException If the name of session is invalid.
	 * 
	 * @return string Returns the name of a session.
	 */
	protected function getSessionName(): string {

		$sessionName = $this->configuration->getSessionName();

		if (!$this->isValidSessionName($sessionName)) {
			throw new RuntimeException(sprintf(
				"Invalid name of session: %s",
				$sessionName
			));
		}

		return $sessionName;
	}

	/**
	 * Get filename for specified access token.
	 * 
	 * @param string $accessToken Access token from client's side.
	 * @return string Returns filename.
	 */
	protected function getFileName(string $accessToken): string {

		return sprintf(
			"%s/%s_%s",
			$this->configuration->getDirectoryName(),
			$this->getSessionName(),
			$accessToken
		);
	}
}
