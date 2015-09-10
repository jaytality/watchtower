<?php
/**
 * class: Baseline
 *
 * A light-weight router for handling browser requests
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @since v1.0.2014.09.01
 * @version v1.0.2014.09.01
 */
class Baseline {
	/**
	 * the request_uri string from the route request
	 * @var string
	 */
	private $uri;

	/**
	 * the method (get, post or any other specified)
	 * @var string
	 */
	private $method;

	/**
	 * string replacement bool flag
	 * @var boolean
	 */
	private $match = false;
	private $m;

	/**
	 * Default Constructor
	 *
	 * Sets all the variables to whatever is parsed
	 */
	function __construct(){
		$this->uri    = $_SERVER['REQUEST_URI'];
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);

		// strip the trailing slash from any URLs
		if($this->uri != "/") {
			$this->uri = preg_replace('{/$}', '', $this->uri);
		}
	}

	/**
	 * calls the appropriate routing as needed
	 * @param  [string] $method
	 * @param  [string] $arguments
	 * @return
	 */
	public function __call($method, $arguments){
		if($method != $this->method)
			return;

			$match = preg_replace("/:([^\/.]*)/", "(.[^/]*)", $arguments[0]);
			$match = str_replace("/", "\/", $match);
			$total = preg_match("/^".$match."$/", $this->uri, $matches);

		if($total == 0)
			return;

		if($this->match)
			return;

		array_shift($matches);
		$this->match = true;
		call_user_func_array($arguments[1],$matches);
	}

	public function notFound($func){
		if(!$this->match)
			call_user_func_array($func,[]);
	}
}

?>