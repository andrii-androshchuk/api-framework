<?php

class Books extends Model {
	
	public function getAvailable() {

		$q = new Query("SELECT * FROM `books`");

		$items = array();

		while($q->isNext()) {

			$items [] = array(

				"id" => $q["id"],
				"title" => $q["title"],
				"pages_count" => $q["pages_count"]
			);
		}
		return $items;
 	}

 	public function likeBook($book_id, $user_id) {

 		$q = new Query("INSERT INTO `book_likes` (`book_id`, `user_id`) VALUES ($book_id, $user_id)");

 		return $q->getInsertId() > 0 ? true : false;
 	}
}