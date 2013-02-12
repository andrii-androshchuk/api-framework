<?php

/** 
 *  Connection
 *
 *	This class creates new connection to database.
 * 	But this class does't contains instance of it. 	
 * 
 *  Public methods:
 *	@method __construct();
 * 	
 * 	This method is overloaded & may accept few diffrent ways of parameters:
 *	
 *  Connection(name, host, user_name, user_password);
 *	Connection(name, host, user_name, user_password, database_name);
 *	Connection(name, host, user_name, user_password, database_name, character_set);
 * 
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */
 

class Connection {
	
	
	/**
	 *	@name _construct
	 * 	This method makes connect to database.
	 *	
	 * 	@see parameters to it see above.
	 */
	public function __construct() {

		$num_args = func_num_args();

		$name = "connection_" . strval(DatabaseConnections::instance()->getConnectionsCount() + 1);

		$host = "";
		$port = 3306;

		$user = "";
		$password = "";

		$database_name = "";
		$character_set = "";

		switch($num_args) {

			case 4: {

				$name = func_get_arg(0);

				$host = func_get_arg(1);
				$user = func_get_arg(2);
				$password = func_get_arg(3);

			}break;

			case 5: {

				$name = func_get_arg(0);

				$host = func_get_arg(1);
				$user = func_get_arg(2);
				$password = func_get_arg(3);			

				$database_name = func_get_arg(4);

			}break;

			case 6: {

				$name = func_get_arg(0);

				$host = func_get_arg(1);
				$user = func_get_arg(2);
				$password = func_get_arg(3);			

				$database_name = func_get_arg(4);
				$character_set = func_get_arg(5);

			}break;

			default: {

				Log::error("Passed wrong data to connect to database", "Connection");

				return;

			}break;
		}

		DatabaseConnections::instance()->create($name, $host, $port, $user, $password, $database_name, $character_set);
	}
}