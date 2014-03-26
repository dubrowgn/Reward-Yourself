<?php

class HttpClient {
	protected $cookies = [];

	public $userAgent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0';

	// =========================================================================

	protected function getCookieString() {
		// init with empty string
		$cookieString = "";

		// append each cookie in $key=$value format
		foreach ($this->cookies as $name => $value)
			$cookieString .= "$name=$value; ";

		// return trimmed cookie string
		return rtrim($cookieString, "; ");
	} // getCookieString( )

	protected function parseCookies($header) {
		$count = preg_match_all('/set-cookie: (.*?)=(.*?);/i', $header, $matches);
		for ($i = 0; $i < $count; $i++)
			$this->cookies[$matches[1][$i]] = $matches[2][$i];
	} // parseCookies( )

	// =========================================================================

	public function getCookie($key) {
		return isset($this->cookies[$key]) ? $this->cookies[$key] : null;
	} // getCookie( )

	/**
	 * request(string, array(string), string, bool)
	 * @returns array('error' => string, 'header' => string, 'body' => string)
	 */
	public function request($url, $customHeads = null, $content = null, $usePost = false) {
		// ensure URL not empty
		if (empty($url))
			return ['error' => "Bad http request: empty url"];

		// break up url into parts
		$urlParts = parse_url($url);
		if ($urlParts === false)
			return ['error' => "Bad http request: malformed url"];

		// build headers
		$headers = [
			'Host: ' . $urlParts['host'],
			'Accept: text/html,application/json,application/xhtml+xml,application/xml',
			'User-Agent: ' . $this->userAgent
		];

		// set cookies
		$cookieString = $this->getCookieString();
		if (!empty($cookieString))
			$headers[] = 'Cookie: ' . $cookieString;

		// set content-length if there is content
		if (!empty($content))
			$headers[] = 'Content-length: ' . strlen($content);

		// inject custom headers
		if (!empty($customHeads))
			foreach ($customHeads as $head)
				$headers[] = $head;

		// declare curl variable
		$ch = null;

		// try request
		try {
			// create curl object
			$ch = curl_init();
			
			// set curl options
			curl_setopt($ch, CURLOPT_URL, $url); // set url
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set headers
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return response body
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ???
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // ???
			curl_setopt($ch, CURLOPT_ENCODING , "gzip"); // allow gzip encoding
			curl_setopt($ch, CURLOPT_HEADER, true); // return response header

			// use POST or GET?
			if ($usePost === true)
				curl_setopt($ch, CURLOPT_POST, true); // use POST method, not GET

			// set request body?
			if (!empty($content))
				curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // fill request body

			// execute request and store reponse in $output
			$rawResponse = curl_exec($ch);
			if ($rawResponse === false)
				return ['error' => 'HTTP request failed: ' . curl_error($ch)];

			// split response into header and body
			$size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$response = ['header' => substr($rawResponse, 0, $size), 'body' => substr($rawResponse, $size)];

			// parse and update client's cookies
			$this->parseCookies($response['header']);

			// close channel
			curl_close($ch);

			// return response array
			return $response;
		} // try
		catch(Exception $ex) {
			// close channel
			if ($ch !== null)
				curl_close($ch);

			// return error
			return ['error' => 'HTTP request failed: ' . $ex->getMessage()];
		} // catch
	} // request( )

	public function setCookie($key, $value) {
		$cookies[$key] = $value;
	} // setCookie( )
} // class

?>
