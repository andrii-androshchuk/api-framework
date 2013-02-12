<?php

/**
 *  Model
 *  This class is parent class for all of models.
 *  It provides access to input parameters & cookies. 
 *  
 *  Public methods:
 *  @method get (string : name of GET or POST parameter);
 *  @method setCookie (string : key, string : value, integer : expire time [not required, by default 86400]);
 *  @method getCookie (string : name);
 *
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 *  @copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */

abstract class Model {

	/**
     *  @name get
     *  Function return value of passed parameter POST or GET
     *
     *	@param string : name of parameter
     */
	protected function get($name) {

		foreach(Registry::get("api_framework-paremeters") as $key => $value) {

			if($key == $name) return $value;
		}
	}


	/**
     *  @name setCookie
     *  The function sets new cookie
	 *
     *	@param string : cookie's name
     *	@param string : cookie's value
     *	@param string : cookie's expire time [not required]
     */
    public function setCookie($_key, $_value, $seconds = 86400) {
        
        setcookie($_key, $_value, time() + $seconds, strval(Configuration::get("cookie_path") ? Configuration::get("cookie_path") : "/"));
    }


	/**
     *  @name getCookie
     *  Function returns value of cookie
	 *
     *	@param string : cookie's name
     */
    public function getCookie($_key) {

    	if(!isset($_COOKIE[$_key])) Log::notice("The '$_key' cookie doesn't exists", "Model");

    	return $_COOKIE[$_key];
    }
}