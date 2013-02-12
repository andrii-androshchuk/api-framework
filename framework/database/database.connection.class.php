<?php

/** 
 *  DatabaseConnection
 *
 *	This class contains instance of mysqli connection.
 * 
 *  Public methods:
 *	@method __construct(string : name, string : host, integer : port, string : user, tring : password, string : database name, string : character set);
 * 	@method getInterface();
 * 	
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */
 

class DatabaseConnection {
    
	// An instance
    private $connection;


	/**
	 * 	@name __construct
	 * 	Function creates new connection
	 * 	
	 * 	@param string 	: name of connection (local, remote, other etc.)
	 * 	@param string 	: host name
	 * 	@param integer 	: port
	 * 	@param string 	: user name
	 * 	@param string	: user password
	 * 	@param string 	: database naem
	 * 	@param string	: character set 
	 * 
	 */
    public function __construct($name, $host, $port, $user, $password, $database_name, $character_set) {
        
        $this->connection = new mysqli($host, $user, $password, $database_name, intval($port));
       
        if(mysqli_connect_errno()) 
            Log::error(mysqli_connect_errno() . ": " . mysqli_connect_error(), $name);
        
        $this->connection->set_charset($character_set);
        
        Log::success("Created new connection '$name'", $name);
    }
    
	
	/**
	 * 	@name getInterface
	 * 	Function return instance of mysqli connection
	 */
    public function getInterface() {
        
        return $this->connection;
    }
}