<?php

/**
 * 	@name View
 *  This class allows you to create view object.
 *	
 *	Public methods:
 *	@method __construct();
 *	@method add(string : name, mixed : value)
 *	@method addEntity(string : name);
 * 	@method get(string : name);
 *	@method asArray();
 *	@method asJSON();
 *	@method asXML();
 * 
 *	This script is created by json-holder class	
 * 	@see https://github.com/androschukandriy/json-holder
 * 
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */

 
class View {

	/**
	 * 	@var root
	 * 	The one only variable contains all of structure inside.
	 */
	private $root;



	/**
	 * 	@method __construct
	 * 	This methods creates new root.
	 * 
	 */
	public function __construct() {

		$this->root = array();
	}


	/**
	 *	@method asXML
	 * 	This function returns the tree as XML string.
	 * 
	 */
	public function asXML() {

		$xml = strval("<?xml version=\"1.0\">") . strval($this->_asXML($this->root));

		return $xml;
	}


	private function _asXML($a) {

		$xml = "";

	    foreach($this->root as $key => $value) {

	    	is_array($value) ? $xml .= $this->xmlNodes($value, $key) : $xml .= "<$key>" . $this->xmlNode($value) . "</$key>";
	    }

	    return $xml;
	}


	private function xmlNodes($v, $n) {

		$xml = "";

		foreach($v as $a) {

			$xml .= "<$n>" . $a . "</$n>";
		}

		return $xml;
	}

	private function xmlNode($v) {

		if(is_string($v)) return $v;

		if(is_object($v)) {

			return $v->_asXML($v);
		}
	}


	/**
	 * 	@method asJSON
	 *	This method returs JSON as stirng, encoded by encode_json (PHP's function).
	 */
	public function asJSON() {

		return json_encode($this->asArray());	
	}


	/**
	 * 	@method asArray
	 *	This method returns	JSON object as PHP array.
	 */	
	public function asArray() {

		if(empty($this->root)) return array();

		$out = array();

		foreach($this->root as $name => $value) {

			if(is_array($value)) {

				($name == "") ? $out[] = $value : $out[$name] = $value;	

			} else if(is_string($value)) {

				($name == "") ? $out[] = $value : $out[$name] = $value;

			} else if(is_object($value)) {

				($name != "") ? $out[$name] = $value->asArray() : $out[] = $value->asArray();
			}
		}		

		return $out;
	}


	/**	
	 * 	@method add
	 * 	This method adds new entity to object with passed name and value.
	 * 	If the value is emtpty (missed) - will added just name as single value.
	 * 
	 * 	@param name		: string
	 * 	@param value	: mixed (string, integer, array) [ not required ]
	 */	
	public function add($_name, $_value = null) {

		if(is_int($_value)) $_value = strval($_value);

		if(is_bool($_value)) $_value = $_value ? "true" : "false";

		if($_value == null) return;

		if(!isset($_value)) {

			$this->root[] = $_name;

			return;
		}

		if(!isset($this->root[$_name])) $this->root[$_name]= $_value;

		else {

			$v = $this->root[$_name];

			if(!is_array($v)) {

				$this->root[$_name] = array($v, $_value);
			} else {

				$this->root[$_name] = $v;

				$this->root[$_name] [] = $_value;
			}
		}
	}


	/**	
	 * 	@method	addEntity
	 * 	This method add new sigle entity to root. 
	 * 	After that you may do with it all of above methods, include this and next ones.
	 * 	In successful creation, the method will return just created JSON object.
	 * 
	 * 	@param name		: string
	 * 
	 * 	@return JSON object 
	 */
	public function addEntity($_name) {

		$this->root[$_name] = "";

		return $this->get($_name);
	}


	/**
	 * 	@method get
	 * 	This method return entity as JSON object.
	 * 
	 * 	@param name 	: string
	 * 
	 * 	@return JSON object
	 */	
	public function get($_name) {

		if(isset($this->root[$_name])) {

			$prev_value = isset($this->root[$_name]) ? $this->root[$_name] : null;  

			if(!is_object($this->root[$_name])) $this->root[$_name] = new self();

			if(isset($prev_value)) $this->root[$_name]->root = (is_object($prev_value)) ? $prev_value->asArray(): $prev_value;

			unset($prev_value);

		} else {

			$this->root[$_name] = new self();
		}

		return $this->root[$_name];
	}
}