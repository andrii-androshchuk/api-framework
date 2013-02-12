<?php

/** 
 *  Configuration
 *
 *  The class that alllows to work with configuration. 
 *  All of them are loads from .cgf file from app path. 
 * 
 *  Public methods:
 *  @method initialize(string : name of configuration's file);
 *  @method get(string : key); 
 *  @method instance();
 *  @method loadFromArray(array : keys);
 * 	@method clearCache();
 * 
 * 	Note. How to create .cfg file
 * 		- each new parameter must starts from new line;
 * 		- each parameter and value must be splited by ' = ' (equal with spaced by sides);
 * 		- comments must starts with '#' (sharp); these lines woun't compile; (not '  #' or '*#', just '#' must be first symbol);
 * 		- the file must ends in '#end' line;
 * 
 * 		Example:
 * 		
 * 			# Database
 * 			database_user = root
 * 			database_password = root
 * 			database_host = host
 * 
 * 			# Language
 * 			# the string below 'en,de' Configuration's class will return as string. You will need to parse it by yourself.
 * 			available_languages = en,de
 * 			default_language = en
 * 		
 * 			#end 
 * 
 * 	If you'll have some troubles with configuration's file, we recommend to you before 
 * 	loading of configuration, clear up the compiled files by Configuration::clearCache() method.
 * 
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 *  @copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */

 
class Configuration {
    
	// Singletone
    private static $p_instance;
    
    // Files
    private $config_file_path;
    private $config_file_hash;    
    private $php_config_file_path;
    
    // Parameters
    private $parameters = array();


    private function __construct() {}

 
    /**
     *  @name initialize
     *  Load configurations. 
     *  
     *  @param string : name of configuration's file
     */
    public function initialize($configuration_name = "main") {
        
        $this->config_file_path = APPLICATION_CONFIGURATION_PATH . $configuration_name . ".cfg";
        
        // The .cfg file doesn't exists
        if (!file_exists($this->config_file_path)) {
            
            Log::error("Not found .cfg file in path: $this->config_file_path", "Configuration");
        }

        // Get hash of file
        $this->config_file_hash = md5_file($this->config_file_path);
                
        // Build path to .php configuration file
        $this->php_config_file_path = APPLICATION_CACHE_PATH . "configuration\\" . $this->config_file_hash . ".php";
        
        // If file doesn't exists then create new 
        if (!file_exists($this->php_config_file_path)) {
            
            Log::notice("Not found .php (compiled) configuration file in path: $this->php_config_file_path", "Configuration");

            // Load data from .cfg file
            $file = file($this->config_file_path);
            
            if(count($file) == 0) {

                Log::notice("In configuration's file $this->config_file_path is not found keys", "Configuration");

                return;
            }
            
            // Output string to .php configuration file
            $configuration_str = '<?php $configuration_array = array(';
            
            // Connection name (temp value)
            $connection_name = "";
            
            // Prase each of line from .cfg file
            foreach ( $file as $line ) {
                    
                if( $line[0] == "#" || trim($line) == "") continue;
            
                $parameter = explode(" = ", $line);
                $key = $parameter[0];
                $value = substr($parameter[1], 0, strlen($parameter[1]) - 2);
                
                    // Exception for connection
                    if($key === "connection_name") { $connection_name = $value;  continue; }
                    
                    // Replace connection_user to local_connection_user (added name before key name)
                    if(substr($key, 0, 11) === "connection_") { $key = $connection_name . "_" . $key; }
 
                $configuration_str = $configuration_str . "'$key'=>'$value',";
            }

            unset($connection_name);
            unset($file);
            
            // Delete last coma & space in array list
            $configuration_str = substr($configuration_str, 0, strlen($configuration_str) - 1);
            
            $configuration_str .= '); Configuration::instance()->loadFromArray($configuration_array);';
            
            
            // Put it into .php file
            file_put_contents($this->php_config_file_path, $configuration_str);
            
            Log::success("Created new configuration .php file: $this->php_config_file_path", "Configuration");
            
            
            // Find and delete previous file of configuration from cache
            $files = scandir(APPLICATION_CACHE_PATH . "configuration");
                        
            foreach ($files as $file) {
                
                if(!in_array($file, array(".", "..", ".svn", $this->config_file_hash . ".php"))) {

                    unlink(APPLICATION_CACHE_PATH . "configuration\\" . $file);
                    
                    Log::success("Deleted cache configuration file: " . APPLICATION_CACHE_PATH . "configuration\\" . $file, "Configuration");
                }                
            }
            
            unset($files);
        }

        // Loading configuration
        require_once $this->php_config_file_path;
    }
    
    
    /**
     * @name instance
     * Singletone function.
     * 
     * @return instance of itself 
     */
    public static function instance() {
        
        if(!self::$p_instance) {
            
            self::$p_instance = new self();
        }
        
        return self::$p_instance;
    }
    
    
    /**
     * @name get
     * The function returns value of configuration parameter.
     * 
     * @param string : name of configuration key
     * @return value of it.
     */      
    public function _get($_key) {

        if(!isset($this->parameters[$_key])) {
            
            Log::error("Unknow configuration key: $_key", "Configuration");
        }
        
        // If parameter is boolean the return is as boolean type.
        switch($this->parameters[$_key]) {
            
            case "true": {
                
                return true;
            } break;
            
            case "false": {
                
                return false;
            } break;
            
            default: {
                
                return $this->parameters[$_key];
            } break;
        }
    }
    
    
    /**
     * @name loadFromArray
     * The function that takes parameters from array and puts it into private array.
     * 
     * @param array : array of parameters
     */       
    public function loadFromArray($_arr) {

        $this->parameters = $_arr;

        Log::success("Configuration loaded from file: $this->php_config_file_path", "Configuration");
        Log::success("Configuration keys count:" . count($this->parameters), "Configuration");   
    }


    /**
     * @name get
     * The function return value of paramter
     * 
     * @param string : name of parameter
     */
    public static function get($n) {

        return self::instance()->_get($n);
    }
	
	
    /**
     * @name clearCache
     * The function make clean up in cache directory
     */
	public static function clearCache() {
		
    	$files = scandir(APPLICATION_CACHE_PATH . "configuration");
            
		// delete '.' & '..'
		unset($files[0]);
		unset($files[1]);
			           
        foreach ($files as $file) {unlink(APPLICATION_CACHE_PATH . "configuration\\" . $file);
                    
            Log::success("Deleted cache configuration file: " . APPLICATION_CACHE_PATH . "configuration\\" . $file, "Configuration");              
        }		
	}
}