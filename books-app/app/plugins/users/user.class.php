<?php

class User {
	
	private $id;
	private $email;

	public function __construct($id) {

		$query = new Query("SELECT `id`, `email` FROM `users` WHERE `id` = " . $id);

		if(!$query->isNext()) {

			Log::error("Is no one record about user");
		}

		$this->id = intval($query["id"]);
		$this->email = $query["email"];
	}

	public function getId() {

		return $this->id;
	}
}