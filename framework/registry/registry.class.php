<?php

/** 
 *  Registry
 *
 *	This is (static) class allows you to storate data in memory 
 * 	and all of it is available by full of scope.
 * 
 *  Public methods:
 *	@method get(string : name);
 *	@method set(string : name, mixed : value, bool : is ready only);
 *  @method del(string : name);
 *  @method clear();
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */

class Registry {
	
	private static $items = array();


	/**
	 * 	@var get
	 * 	This function returns value from registry
	 *	
	 *	@param string : name of paramter
	 */
	public static function get($name) {

		if(!isset(self::$items[$name])) {

			Log::error("The '$name' is not exists", "Registry");

			return;
		}

		return self::$items[$name]["value"];
	}


	/**
	 * 	@var set
	 * 	This function sets new paramter to registry.
	 *	
	 *	@param string 	: name
	 *	@param mixed 	: value
	 *	@param bool 	: is ready only
	 */
	public static function set($name, $value, $read_only = true) {

		if(isset(self::$items[$name])) {

			if(self::$items[$name]["readonly"]) {

				Log::error("The '$name' is not allowed to overwrite", "Registry");

				return;
			}

			Log::notice("The '$name' already exists.", "Registry");
		}

		self::$items[$name] = array(

			"value" => $value,
			"readonly" => $read_only
		);

		Log::success("Added '$name'.", "Registry");
	}


	/**
	 *	@name del
	 *	Function deletes value from registry
	 *	
	 *	@param string : name
	 */
	public static function del($name) {

		if(!isset(self::$items[$name])) {

			Log::notice("The '$name' is already deleted", "Registry");

			return;
		}

		unset(self::$items[$name]);

		Log::error("The '$name' is deleted", "Registry");
	}


	/**
	 *	@name clear
	 *	Function makes clear all of registry.
	 */
	public static function clear() {

		self::$items = array();

		Log::error("The registry is cleared up.", "Registry");
	}
}