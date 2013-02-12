<?php

/** 
 *  Query
 *
 *  This class is like a connections manager. It contains all of create connections.
 *  Each of connection is identified by name, for example 'loca' or 'remote'.
 * 
 *  Public methods:
 * 	@method __construct(string : sql, string : connection name);
 *  @method execute(string : sql);
 * 	@method clear();
 * 	@method isNext();
 * 	@method getInsertId();
 * 	@method getNumRows();
 * 	@method operator[](); < offsetGet >
 * 
 * 	Example of using query class:
 * 
 * 		$q = new Query("SELECT * FROM `tablename`");
 * 
 * 		$count_rows = $q->getNumRows();
 * 	
 * 		echo "Count of rows is " . strval($count_rows);	
 * 
 * 		while($q->isNext()) {
 * 		
 * 			echo $q["a"];
 * 			echo $q["b"];	
 * 		}
 *  
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 *  @copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */


class Query extends ArrayObject {
    
    
    /**
     *  @var connection
     *  The instance to mysqli
     */ 
    private $connection;
    
    /**
     *  @var Result of query
     */
    private $result;
    
    
    /**
     *  @var Row
     *  Current row (for while loop)
     */ 
    private $row;
    
    
    /**
     *  @var SQL
     *  Contains SQL request 
     */
    private $SQL;
    
    
    /**
     *  @var Connection name
     */
    private $connection_name;
    

    private $insert_id;
    private $num_rows;
    private $affected_rows;
    private $active_row;
	
	
   /**
    * 	@name _construct
    * 	Function creates new query.
    * 	
    * 	@param string : sql statement [not required]
    * 	@param string : connection name [not required, if you'll use default 'local' connection']
    */
    public function __construct($_sql = "", $_connection_name = "local") {
        
        $this->connection_name = $_connection_name;
        $this->connection = DatabaseConnections::instance()->getConnection($this->connection_name)->getInterface();
        
        if(!empty($_sql)) {
            
            $this->execute($_sql);
        }
    }
	    
	
	/**
	 * 	@name execute
	 * 	Function executed passed query using by mysqli_query.
	 */
    public function execute($_sql = "") {
        
        // Initialization
        $this->insert_id = 0;
        $this->affected_rows = 0;
        $this->num_rows = 0;
        $this->active_row = 0;
        
        
        // Check for empty SQL request
        if(!empty($_sql)) $this->SQL = $_sql;
        
        
        $this->result = mysqli_query($this->connection, $this->SQL);
        
        if(!$this->result) {
            
            Log::error("<" . $this->SQL . "> " . mysqli_errno($this->connection) . ": " . mysqli_error($this->connection), $this->connection_name . "_query");
            return;
        }
        
        Log::success($this->SQL, $this->connection_name . "_query");
                 
        $this->parseRequestInfo();    
    }


	/**
	 * 	@name parseRequestInfo
	 * 	Function calls after successful execution of query to take results of it (number of rows or insert id)
	 */
    private function parseRequestInfo() {
        
        $command = explode(" ", $this->SQL);
        $command = $command[0];         
        
        switch(strtolower($command)) {
            
            case "insert": {
                
                $this->insert_id = mysqli_insert_id($this->connection);
                
            }break;
                
            case "select": {
                
                $this->num_rows = mysqli_num_rows($this->result);

            }break;
                
            case "update": {
                
                $this->affected_rows = mysqli_affected_rows($this->connection);

            }break;
        }
    }
    

    /**
     *  @name clear
     *  Make clear
     */
    public function clear() {
        
        mysqli_free_result($this->result);
        
        $this->insert_id = 0;
        $this->affected_rows = 0;
        $this->num_rows = 0;
        $this->active_row = 0;
                
        $this->SQL = "";
    }
    
    
    /**
     *  @name isNext
     *  The functino check for existing next row for while loop
     *  
     *  @return boolean : true or false
     */
    public function isNext() {

        $this->row = mysqli_fetch_array($this->result, MYSQLI_BOTH);
        
        if(!$this->row) {
            
            mysqli_free_result($this->result);
            return false;
        }
        
        $this->active_row++; 
        
        return $this->row; 
    }
    
    
    /**
     *  @name offsetGet
     *  It's a operator[].
     *  
     *  @param string : field name
      *
     */
    public function offsetGet($_k) {
            
        if(!isset($this->row[$_k])) {
            
            Log::error("Row # ". $this->active_row . " Can't find value of field: " . $_k, $this->connection_name . "_query");
        }
        
        return $this->row[$_k];
    }
    
    
    /**
     *  @name getNumRows
     *  The function returns num of rows
     * 
     */
    public function getNumRows() {
        
        return $this->num_rows;
    }
    
    
    /**
     *  @name insertId
     *  The function returns last insert id
     */
    public function getInsertId() {
        
        return $this->insert_id;
    }
}