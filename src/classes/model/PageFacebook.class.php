<?php

class PageFacebook extends Page {
	function parseRequest() {
		if (isset($_REQUEST['signed_request']) && $this->properties->get('secret_key')) {
			return $this->parseSignedRequest($_REQUEST['signed_request'], $this->properties->get('secret_key'));
		}
		else {
			return array();
		}
	}

	private function parseSignedRequest($signed_request, $secret) {
		//TODO remove this hardcoded crap
		//$signed_request = 'sXUdyth_ygOFaBzxGPqWTHLh2llz8KJRbEQpQ-ueVVw.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImV4cGlyZXMiOjEzMjQzMzU2MDAsImlzc3VlZF9hdCI6MTMyNDMzMTY5Mywib2F1dGhfdG9rZW4iOiJBQUFBQUg2VE1EUHdCQUpaQ24weFZlNkRIWkJNdjB6cXkxeHoxNFhDbkI1V3BaQnpKR3ZjWkJ0czJRZzFiaEs4eWJ6akxGRlF6VlhOSlJnZ0c5TjRaQ3NkWkFZa1Vwb25MMGt2YnNaQjViakZ2QVpEWkQiLCJ1c2VyIjp7ImNvdW50cnkiOiJjYSIsImxvY2FsZSI6ImVuX1VTIiwiYWdlIjp7Im1pbiI6MjF9fSwidXNlcl9pZCI6IjU4MDM2MTcyMSJ9';
		//$secret = 'c60723f61d3aa8400df70eace38732f5';
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

		// decode the data
		$sig = self::base64UrlDecode($encoded_sig);
		$data = json_decode(self::base64UrlDecode($payload), true);

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

	private static function base64UrlDecode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
}

?>
