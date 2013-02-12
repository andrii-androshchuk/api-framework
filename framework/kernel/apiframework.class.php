<?php

/** 
 *  APIFramework
 *
 *  This class is main class for application, it runs all of it.
 * 
 *  Public methods:
 * 	@method __construct(string : sql, string : connection name);
 *  @method execute(string : sql);
 * 	@method clear();
 * 	@method isNext();
 * 	@method getInsertId();
 * 	@method getNumRows();
 * 	@method operator[](); < offsetGet >
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */
 

abstract class APIFramework {

	private $application_name;

	public static $available_controllers;
	
	protected $controller;

	protected $action;

	private static $outs;
	private $output_type;

	public static $version = "0.1";

	public function __construct() {

		// To begin with :)
		ob_end_clean();

		$this->outs = array();
		$this->output_type = "JSON";

		// Welcome
		$this->name = strval(APPLICATION_NAME);

		Log::message("Application is running", $this->name);

		// Getting request string (controller & action)
		if(empty($_GET["__api_framework_request_string"])) die();

		$request_string = mysql_escape_string($_GET["__api_framework_request_string"]);

		$request_string = explode(".", $request_string);

		if(count($request_string) != 2) die();

		$this->controller = $request_string[0];

		$this->action = $request_string[1];


			$parameters = array();

			// Accept GET paramters
			if(count($_GET) > 1) {

				foreach($_GET as $key => $get) {

					if($key == "__api_framework_request_string") continue;
					
					$parameters[$key] = $get;
				}
			}

			// Accept POST paramters
			if(count($_POST) > 0) {

				foreach($_POST as $key => $get) {

					$parameters[$key] = $get;
				}
			}

			Registry::set("api_framework-paremeters", $parameters, true);

			unset($parameters);


		// Load configuration	
		Configuration::instance()->initialize();


		// Engine

		$this->initialize();

		$this->execute();

		$this->finalize();

		// ------------------

		$this->main();
	}

	
	/**
	 * 	@name initialize
	 * 	@name execute
	 * 	@name finalize
	 * 
	 * 	There of user's functions to work with.
	 * 	In these ones you may set available controllers, load your plugins,
	 * 	manipulate with cookies and passed parameters and finaly after that
	 * 	run the controller.
	 */
	public abstract function initialize();
	public abstract function execute();
	public abstract function finalize();

	
	/**
	 * 	@name getController
	 * 	Function returns name of active controller's name
	 */
	public function getController() {

		return $this->controller;
	}

	
	/**
	 * 	@name getAction
	 * 	Function returns name of active action
	 */
	public function getAction() {

		return $this->action;
	}

	
	/**
	 * 	@name main
	 * 	The main function. It includes controllers files and runs it.
	 */
	private function main() {

		// include controller's file
		$controller_path = APPLICATION_CONTROLLERS_PATH . $this->controller . "/" . $this->controller . ".controller.php";

		if(!file_exists($controller_path)) {

			Log::error("Can't find controller's file in '$controller_path' for '$this->controller' controller", $this->name);

			return;
		}

		require_once $controller_path;

		// Create instance of controller & run it
		$controller_name = $this->controller . "Controller";

		$c = new $controller_name($this->controller, $this);

		$c->initialize();

		if(!method_exists($c, $this->action)) {

			Log::error("Can't find action '$this->action' in '$this->controller' controller", $this->name);

			return;
		}

		call_user_func(array($c, $this->action));

		$this->printOut();
	}


	/**
	 *	@name printOut
	 *	This function makes out of application and prints all of stored out-objects
	 */
	private function printOut() {

		ob_start();

		$output_type = "as" . strtoupper($this->output_type ? $this->output_type : "JSON");

		foreach(self::$outs as $obj) {

			if(is_string($obj)) {
				
				echo $obj;

			} else { 				

				if(method_exists($obj, $output_type)) echo call_user_func(array($obj, $output_type));

				else Log::error("The passed object to output doesn't have '$output_type' method", APPLICATION_NAME);
			}

			
		}
		
		ob_end_flush();

		exit;
	}

	
	/**
	 * 	@name out
	 *	Function adds new object to output list 	
	 *
	 * 	@param mixed 	: object to output [View class or string]
	 */
	public static function out($obj) {
		
		self::$outs[] = $obj;
	}


	/**
	 *	@name setOutputType
	 *	Function sets output type
	 *	
	 *	@param string : name
	 */
	public function setOutputType($name) {

		$this->output_type = $name;
	}

	
	/**
	 * 	@name redirect
	 * 	Function make internal redirect (not in $_SERVER variable).
	 * 	This just replace controller and action on passed ones.
	 * 
	 * 	@param url	: string
	 * 
	 * 	The url must be:
	 * 	<controoller_name.action>, example: error.unknow_action
	 */
	public function redirect($_w) {

		$w = explode(".", $_w);

		if(count($w) != 2) {

			Log::error("Can't redirect to '$_w'", $this->name);

			return;
		}

		$this->controller = $w[0];

		$this->action = $w[1];

		Log::success("Redirected to '$_w'", $this->name);
	}

	
	/**
	 * 	@name isValidateController
	 * 	Function just makes check, is validate current controller or not.
	 * 	
	 * 	@return boolean : is validate
	 */
	public function isValidateController() {

		return in_array($this->controller, self::$available_controllers);
	}
}