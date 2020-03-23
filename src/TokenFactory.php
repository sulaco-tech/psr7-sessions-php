<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

use \SulacoTech\PSR7Sessions\TokenFactoryInterface;
use \SulacoTech\Base58;

use function \openssl_random_pseudo_bytes;
use function \preg_match;

/**
 * Generic implementation of interface TokenFactoryInterface.
 */
class TokenFactory implements TokenFactoryInterface {
	
	private $base58;
	private $length;
	
	/**
	 * @param int $length Length of token.
	 */
	public function __construct(int $length = 32) {
		if ($length <= 0) {
			 throw new InvalidArgumentException(sprintf(
				"Invalid length of token: %d",
				$length
			));
		}
		$this->length = $length;
		$this->base58 = new Base58();
	}

	/**
	 * {@inheritdoc}
	 */
	public function createToken(): string {
		return $this->base58->encode(openssl_random_pseudo_bytes($this->length));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function isValidToken(string $token): bool {
		return preg_match('/^[' . Base58::CHARSET_IPFS . ']{' . $this->length . ',}$/', $token) ? true : false;
	}
}
