<?php

class Request {
	protected $controller;

	public function __construct($controller) {
		if (!is_string($controller)) {
			throw new Exception('Controller name argument must be string');
		}

		$this->controller = $controller;
	}

	public function getController() {
		return $this->controller;
	}

	protected static function base64UrlDecode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
}

class RenderRequest extends Request {
	protected $object_slug;
	protected $parts;

	public function __construct(array $parts) {
		parent::__construct('layout');

		if (count($parts) < 1) {
			throw new NotFoundException('Unable to route request');
		}

		$this->object_slug = $parts[0];
		$this->parts = array_slice($parts, 1);
	}

	public function getSlug() {
		return $this->object_slug;
	}

	private function parseSignedRequest($signed_request, $secret) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

		// decode the data
		$sig = Request::base64UrlDecode($encoded_sig);
		$data = json_decode(base64_url_decode($payload), true);

		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
			throw new Exception('Unknown algorithm. Expected HMAC-SHA256');
		}

		// check sig
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			throw new Exception('Bad Signed JSON signature!');
		}

		return $data;

	}

}

?>
