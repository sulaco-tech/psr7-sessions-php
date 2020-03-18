<?php declare(strict_types = 1);

namespace SulacoTech\PSR7Sessions;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseInterface;
use \SulacoTech\PSR7Sessions\SessionStorageInterface;

/**
 * The middleware implementation to support usage sessions with PSR-7 classes.
 */
class SessionMiddleware implements MiddlewareInterface {

	private $sessionStorage;

	/**
	 * @var string Attribute name for session reference.
	 */
	public const SESSION_ATTRIBUTE = 'session';

	/**
	 * @param SessionManagerInterface $sessionManager Instance of implementation of SessionManagerInterface.
	 */	
	public function __construct(SessionStorageInterface $sessionStorage) {
		$this->sessionStorage = $sessionStorage;
	}

	/**
	 * {@inheritdoc}
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		
		$cookies = $request->getCookieParams();

		$cookieName = $this->sessionStorage->getCookieName();
		$sessionToken = $cookies[$cookieName] ?? null;

		$session = $this->sessionStorage->getSession($sessionToken);
		$request = $request->withAttribute(SessionMiddleware::SESSION_ATTRIBUTE, $session);
		$response = $handler->handle($request);
		$this->sessionStorage->saveSession($session);

		$actualSessionToken = $session->getAccessToken();
		if ($sessionToken === null || $sessionToken !== $actualSessionToken) {
			$response = $response->withAddedHeader('Set-Cookie', "{$cookieName}={$actualSessionToken}");
		}

		return $response;
	}
}