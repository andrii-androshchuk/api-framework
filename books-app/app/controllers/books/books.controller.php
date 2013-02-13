<?php

class BooksController extends Controller {
	
	public function initialize() {

		// Include model
		new UseModel("Books");
	}

	public function get_available() {
/*
		// Our model
		$books = new Books();

		// Array of books
		$items = $books->getAvailable();

		// Output view
		$view = new View();

		$view->add("available_books", $items);

		$this->out($view);*/
	}

	public function like_book() {

		// Model
		$books = new Books();

		// Getting id of book from passed parameter
		$book_id = $this->get("book_id");

		// Getting id of user from main class
		$user_id = $this->app->user->getId();

		$status = $books->likeBook($book_id, $user_id);


		// Out
		$view = new View();

		$view->add("status", strval($status ? "successful" : "failure"));

		$this->out($view);
	}
}