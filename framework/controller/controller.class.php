<?php

/** 
 *  Controller
 *
 *	This is a parrent class for all of controllers.	
 * 
 *  Public methods:
 *	@method __construct(string : name of controller, APIFramework : app);
 * 	@method initialize();
 * 
 * 	Protected methods:
 * 	@method get (string : name of POST or GET parameter);
 * 	@method out (object : object that will out, string : output type (xml, json, array) [not required]);
 * 
 *
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */

 
abstract class Controller {

	protected $app;

	/**
     *  __construct
	 * 	This function just makes logging about successful creation of controller	
	 * 
	 * 	@param string : name of controller
	 *	@param APIFramework : app
     */	
	public function __construct($name, $app) {
		
		$this->app = $app;

		Log::success("Controller '$name' is started.", $name . "Controller");
	}


	/**
     *  @name initialize
     *  This method may be oberloaded by you to make some initialization.
     */
	public function initialize() {

	}


	/**
     *  @name get
     *  Function return value of passed parameter POST or GET
     *
     *	@param string : name of POST or GET parameter
     */
	protected function get($name) {

		foreach(Registry::get("api_framework-paremeters") as $key => $value) {

			if($key == $name) return $value;
		}
	}


	/**
     *  @name out
     *  Function return value of passed parameter POST or GET
     *
     *	@param object : object to output
     *	@param string : output type
     */
	protected function out($obj, $ot = "") {

		APIFramework::out($obj, $ot);
	}
}