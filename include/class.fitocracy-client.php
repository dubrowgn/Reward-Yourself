<?php

require("class.http-client.php");

class FitocracyClient {
	protected $client = null;
	protected $username = null;
	protected $password = null;
	protected $loginUrl = null;
	protected $feedUrl = null;
	protected $loggedIn = false;

	public function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;

		$this->client = new HttpClient();

		$this->loginUrl = 'https://www.fitocracy.com/accounts/login/';
		$this->feedUrl = "https://www.fitocracy.com/profile/$username/?feed";
	} // __construct( )

	public function login() {
		// update logged-in flag
		$this->loggedIn = false;

		// check username
		if (empty($this->username))
			return ['error' => 'Username cannot be empty'];

		// check password
		if (empty($this->password))
			return ['error' => 'Password cannot be empty'];

		// make GET request to /accounts/login/ to get CSRF token
		$response = $this->client->request($this->loginUrl);
		if (!empty($response['error']))
			return ['error' => "Failed to get CSRF token:\n\t" . $response['error']];

		// ensure we have csrf token
		$csrf = $this->client->getCookie('csrftoken');
		if (empty($csrf))
			return ['error' => 'Failed to get CSRF token: token not found'];

		// init request parts
		$headers = ['Referer: https://www.fitocracy.com/accounts/login/'];
		$body = implode("&", [
			"csrfmiddlewaretoken=" . $csrf,
			"is_username=1",
			"json=1",
			"username=" . $this->username,
			"password=" . $this->password
		]);

		// make login request
		$response = $this->client->request($this->loginUrl, $headers, $body, true);
		if (!empty($response['error']))
			return ['error' => "Failed to login:\n\t" . $response['error']];

		// parse json
		$json = json_decode($response['body']);;

		// ensure login successful
		if ($json->success === false)
			return ['error' => 'Failed to login: ' . $json->error];

		// success
		$this->loggedIn = true;
		return [];
	} // login( )

	public function getTotalXp() {
		// auto login if not already
		if ($this->loggedIn !== true) {
			$r = $this->login();
			if (!empty($r['error']))
				return $r;
		} // if

		// make user feed request
		$response = $this->client->request($this->feedUrl);
		if (!empty($response['error']))
			return ['error' => "Feed request failed:\n\t" . $response['error']];

		// parse out total xp
		$match = preg_match('/<li id="stat-points">.*?<div class="stat-value" title="(\d+)">/s', $response['body'], $matches);
		if ($match === false || $match === 0)
			return ['error' => "Could not find total XP in feed response."];

		// return total xp as an int value
		return ['xp' => (int)$matches[1]];
	} // getTotalXp( )
} // class

?>
