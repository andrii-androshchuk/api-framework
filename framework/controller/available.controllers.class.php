<?php

/** 
 *  AvailableControllers
 *
 *	This contructions allows to you register all of your available controllers that are accessible.
 * 
 *  Public methods:
 *	@method __construct(string : array of controllers names);
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */
 
class AvailableControllers {
	
	/**
	 * 	@name __construct
	 * 	This construct register passed to it array of controllers.
	 * 	Every new call of it will overwrite existed array of them.
	 * 
	 * 	@param array : array of controller's name
	 */
	public function __construct($i) {

		APIFramework::$available_controllers = $i;
	}
}