<?php

/** 
 *  Plugins
 *
 *	This contructions allows to you to include passed plugins.
 * 
 *  Public methods:
 *	@method __construct(mixed {string, array} : name | names of plugins);
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */


class Plugins {


	/**
     *  __construct
     *  Function includes plugin's file.
     *
     *	@param mixed {string, array} : name | names of plugins
     */	
	public function __construct($in) {
		
		if(is_string($in)) return $this->uploadOne($in);

		if(is_array($in)) {

			foreach($in as $plugin) {

				$this->uploadOne($plugin);
			}
		}
	}


	/**
     *  @name uploadOne
     *  Function includes plugin's file.
     *
     *	@param string : plugin's name
     */		
	private function uploadOne($plugin_name) {

		$plugin_path = APPLICATION_PLUGINS_PATH . $plugin_name . "/" . $plugin_name . ".plugin.php";

		if(!file_exists($plugin_path)) {

			Log::error("Can't find plugin '$plugin_name' in $plugin_path", "PluginsManager");
			
			return;
		}

		require_once $plugin_path;

		Log::success("Uploaded '$plugin_name' plugin", "PluginsManager");		
	}
}