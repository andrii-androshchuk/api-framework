<?php

class BooksAPI extends APIFramework {

	public $user;

	public function initialize() {

		// Create connection
		new Connection(
			
			"local", // Connection's name

			Configuration::get("database_host"), 
			Configuration::get("database_user"), 
			Configuration::get("database_password"), 
			Configuration::get("database_name")
		);


		// Upload next plugins
		new Plugins(array("Users"));


		// Set avaialble controllers
		// In any wat the 'error' controller must be
		new AvailableControllers(array("error", "books"));

		$this->setOutputType("JSON");


		// Let's check for user

		//
		// --- here you put your user's check
		//

		$user_id = 1;


		// Create user class (from plugins)
		// This variable is public.
		if($user_id > 0) {

			$this->user = new User($user_id);	
		}
	}


	public function execute() {

		if(!$this->isValidateController()) {

			$this->redirect("error.unknow_controller");
		}

		// Also you make disable access for some controllers if user isn't authorized by next steps:
		if(!$this->user) {

			if($this->getController() == "<controller_name>") {

				$this->redirect("error.unauthorized_user");	
			}
		}
	}


	public function finalize() {

		// Make some clean up or stat
	}
}