<?php

/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	PRE-INITIALIZATIONS
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

// start execution timer
$php_start_time = microtime(true);

// load server config options
require("config.php");
require('lib/password.php');

/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	STATIC CLASSES
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */


// _______________________________________________________________________(auth)
class auth {
	public static function create_user($mysqli, $username, $password) {
		// validate username
		if (empty($username))
			return "Username cannot be empty";
		if (strlen($username) > 255)
			return "Username cannot be longer than 255 characters";
		
		// validate password
		if (empty($password))
			return "Password cannot be empty";
		if (strlen($password) > 255)
			return "Password cannot be longer than 255 characters";
		
		// password_hash() returns false on failure
		$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
		if (!$hash)
			return "Failed to hash password";
		
		// insert user into database
		$error = null;
		$query = "INSERT INTO user (username, hash) VALUES(?, ?)";
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("ss", $username, $hash);
			if (!$stmt->execute())
				$error = $stmt->error;
			$stmt->close();
		} // if
		else {
			$error = $mysqli->error;
		} // else
		
		// return any errors
		return $error;
	} // create_user( )
	
	public static function change_password($mysqli, $user_id, $password) {
		return "change_password() not implemented!";
	} // change_password( )
	
	public static function login($mysqli, $username, $password) {
		// validate username
		if (empty($username))
			return "Username cannot be empty";
		if (strlen($username) > 255)
			return "Username cannot be longer than 255 characters";
		
		// validate password
		if (empty($password))
			return "Password cannot be empty";
		if (strlen($password) > 255)
			return "Password cannot be longer than 255 characters";
			
		// get user from database
		$error = null;
		$query = "SELECT id, hash FROM user WHERE username = ? LIMIT 1";
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("s", $username);
			if (!$stmt->execute())
				$error = $stmt->error;

			$stmt->bind_result($id, $hash);
			if ($stmt->fetch() && password_verify($password, $hash)) {
				$_SESSION['user'] = array(
					'id' => $id,
					'name' => $username,
					'hash' => $hash
				);
			} // if
			else {
				$error = "Invalid username/password";
			} // else

			$stmt->close();
		} // if
		else {
			$error = $mysqli->error;
		} // else
		
		return $error;
	} // login( )
	
	public static function logout() {
		unset($_SESSION['user']);
	} // logout( )
	
	public static function revalidate() {
		return isset($_SESSION['user']);
	} // revalidate( )
} // staic class 'auth'

// ________________________________________________________________________(url)
class url {
	public static function redirect($page) {
		$scheme = $_SERVER['HTTPS'] === "on" ? "https" : "http";
		$host = $_SERVER['HTTP_HOST'];
		$dir = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		header("Location: {$scheme}://{$host}{$dir}/{$page}");
		
		//exit();
	} // top( void )
} // static class 'linkBar'


/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	CLASSES
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */


class BasicHead {
	private $_css = "";
	private $_js = "";
	private $_jss = [];
	private $_links = [ '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />' ];
	private $_metas = [ '<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />' ];
	private $_title = "";
	
	public function linkJs($src, $attributes = "") {
		// normalize attributes
		$attributes = empty($attributes) ? "" : " " . trim($attributes);

		// append js tag
		$this->_jss[] = "<script type=\"text/javascript\" src=\"$src\"$attributes></script>";
	} // linkJs( )
	
	public function linkCss($href) {
		$this->_links[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$href\" />";
	} // linkCss( )

	public function embedJs($js) {
		$js = trim($js, "\r\n");
		$this->_jss[] = "<script>\n$js\n\t\t</script>";
	} // embedJs( )

	public function embedJsAsyncHandler($name, $count, $command) {
		$this->_jss[] = "<script>var $name = (function() { var count = $count; return function() { if (--count == 0) $command; }; })();</script>";
	} // embedJsAsyncHandler( )

	public function embedJsFile($url) {
		// normalize url
		$url = rtrim($_SERVER['DOCUMENT_ROOT'], "/") . "/" . ltrim($url, "/");

		// embed js
		$this->embedJs(file_get_contents($url));
	} // embedJsFile( )

	public function embedCss($css) {
		// minimize css and append
		$this->_css .= preg_replace("/(\/\*.*?\*\/|\n|\t|\r)/s", "", $css);
	} // embedCss( )

	public function embedCssFile($url) {
		// normalize url
		$url = rtrim($_SERVER['DOCUMENT_ROOT'], "/") . "/" . ltrim($url, "/");

		// embed css
		$this->embedCss(file_get_contents($url));
	} // embedCssFile( )

	public function title($title) {
		$this->_title = $title;
	} // title( )

	public function description($description) {
		$this->metadata("description", $description);
	} // description( )

	public function keywords($keywords) {
		$this->metadata("keywords", $keywords);
	} // keywords( string )

	public function canonicalUrl($href) {
		$this->_links[] = "<link rel=\"canonical\" href=\"$href\"/>";
	} // canonicalUrl( )

	public function link($rel, $type, $href) {
		$this->_links[] = "<link rel=\"$rel\" type=\"$type\" href=\"$href\"/>";
	} // link( )

	public function metadata($name, $content) {
		$this->_metas[] = "<meta name=\"$name\" content=\"$content\" />";
	} // metadata( )

	public function output() {
		// open
		echo '<!DOCTYPE html>', "\n";
		echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">', "\n";
		echo '	<head>', "\n";

		// title
		if (!empty($this->_title))
			echo "\t\t<title>{$this->_title}</title>\n";

		// metadata tags
		foreach ($this->_metas as $m) {
			echo "\t\t$m\n";
		} // foreach( $m )

		// link tags
		foreach ($this->_links as $l) {
			echo "\t\t$l\n";
		} // foreach( $l )

		// embeded minified css
		if (!empty($this->_css))
			echo "\t\t<style type=\"text/css\">{$this->_css}</style>\n";

		// javascript tags
		foreach ($this->_jss as $j) {
			echo "\t\t$j\n";
		} // foreach( $j )

		// embeded minified javascript
		if (!empty($this->_js))
			echo "\t\t<sctript>{$this->_js}</style>\n";

		// close
		echo "\t</head>\n";
	} // echo( )
} // class


/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	GLOBAL FUNCTIONS
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

// _________________________________________________________________(db_connect)
function db_connect() {
	// CONNECT TO DATABASE
	$mysqli = new mysqli(\config\sql_host, \config\sql_username, \config\sql_password, \config\sql_database);

	// CHECK FOR CONNECTION FAILURE
	if ($mysqli->connect_error) {
		$error = 'Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
	 	email_error(__FILE__, __LINE__ - 1, $error);
	 	die("<p>There was an error while connecting to the database, and the site admin has been notified. Please try again later.</p>");
	} // if

	return $mysqli;
} // db_connect( void )

// ________________________________________________________________(email_error)
function email_error($file, $line, $_error) {
	// print error message if in development mode
	if (\config\isDev)
		echo "<p>ERROR (debug): File - $file (line $line)</p><p>Error Message: $_error</p>";

	// send email if webmaster email is configured
	if (\config\webmaster_email !== null) {
		// create email headers
		$headers = "MIME-Version: 1.0\n" ;
		$headers .= "Content-type: text/plain; charset=UTF-8\n";
		$headers .= "From: Error Dispatcher <noreply@dubrowgn.com>\n";
		$headers .= "X-Priority: 1 (Highest)\n";
		$headers .= "X-MSMail-Priority: High\n";
		$headers .= "Importance: High\n"; 

		// email the error details to the site admin (dbrown@dubrowgn.com)
		if (mail(\config\webmaster_email,
			"An error has occurred on DuBrowgn.com",
			"The following error has occurred on DuBrowgn.com:\n\nFile -\n$file (line $line)\n\nError -\n$_error",
			$headers))
			return true;
	} // if

	return false;
} // email_error( )

// ___________________________________________________________(get_execution_ms)
function get_execution_ms() {
	global $php_start_time;
	
	return number_format((microtime(true) - $php_start_time) * 1000, 2);
} // get_execution_ms( void )

// ________________________________________________________________(include_php)
function include_php($_url) {
	$url = rtrim($_SERVER['DOCUMENT_ROOT'], "/") . "/" . ltrim($_url, "/");

	if (!include($url))
		email_error(__FILE__, __LINE__, "Failed to open '$url' for inclusion.");
} // include_php( string )


/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	POST-INITIALIZATIONS
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

?>
