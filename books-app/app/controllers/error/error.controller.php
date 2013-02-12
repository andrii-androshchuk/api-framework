<?php

class ErrorController extends Controller {
	
	public function unknow_controller() {

		$view = new View();

		$view->add("status", "failure");

		$view->add("message", "The passed controller isn't exists");

		$view->add("code", "0x984");

		$this->out($view);			
	}

	public function unauthorized_user() {

		$view = new View();

		$view->add("status", "failure");

		$view->add("message", "The user isn't authorized");

		$view->add("code", "0x154");

		$this->out($view);		
	}
}