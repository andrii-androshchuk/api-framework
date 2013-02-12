<?php

/** 
 *  DatabaseConnections
 *
 *	This class is like a connections manager. It contains all of create connections.
 * 	Each of connection is identified by name, for example 'loca' or 'remote'.
 * 
 *  Public methods:
 * 	@method instance();
 * 	@method	create(string : name, string : host, integer : port, string : user, tring : password, string : database name, string : character set);
 * 	@method	getConnection(string : name of connection);
 * 	@method	getConnectionsCount();
 * 	
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */
 
 
class DatabaseConnections {
    
    private $connections = array();
    
    private static $p_instance;

    private function __construct() { }
    
	
	/**
	 * 	@name instance
	 * 	Function return static instance of this class.
	 * 	Sinletone.
	 */
    public static function instance() {
        
        if(!self::$p_instance) {
                
            self::$p_instance = new self();
        }
        
        return self::$p_instance;
    }
    
    
	/**
	 *	@name create
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
    public function create($name, $host, $port, $user, $password, $database_name, $character_set) {
        
        if(!empty($this->connections[$name])) {
            
            Log::warning("The '$name' connection already exists", "DatabaseConnection");
        }

        $this->connections[$name] = new DatabaseConnection($name, $host, $port, $user, $password, $database_name, $character_set); 
    }
	
	
	/**
	 * 	@name getConnection
	 * 	Function return instance of connection by passed name
	 * 
	 * 	@param string : name of connection [not required, if here's only one defaul 'local' connection]
	 */
    public function getConnection($_name = "local") {
        
        if( empty($this->connections[$_name]) ) {
            
            Log::error("Can't find connection '$_name'.", "DatabaseConnections");
            
            return;
        }

        return $this->connections[$_name]; 
    }

	
	/**
	 * 	@name getConnectionsCount
	 * 	Function return count of existed connection
	 */
    public function getConnectionsCount() {

        return count($this->connections);
    }
}