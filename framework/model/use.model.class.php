<?php

/** 
 *  UseModel
 *
 *	This contructions allows to you to include passed models;
 * 
 *  Public methods:
 *	@method __construct(mixed {string, array} : name | names of models);
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */


class UseModel {
	

	/**
     *  __construct
     *  Function includes model's file.
     *
     *	@param mixed {string, array} : name | names of models
     */
	public function __construct($i) {

		if(is_string($i)) return $this->includeOne($i);

		if(is_array($i)) {

			foreach($i as $n) {

				$this->includeOne($n);
			}

		}		
	}


	/**
     *  __construct
     *  Function includes model's file.
     *
     *	@param string : name of model
     */
	private function includeOne($name) {

		$model_path = APPLICATION_MODELS_PATH . $name . "/" . $name . ".model.php";

		if(!file_exists($model_path)) Log::error("File of model '$name' doesn't exists in $model_path", "UseModel");

		require_once $model_path;

		Log::success("Included '$name' model", "UseModel");		
	}
}