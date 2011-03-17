<?php
/**
 * boolean if true the autoloader scripts will be parsed and their output shown. For debugging purposes only.
 */
//ob_start("ob_gzhandler");
$time_start = explode(' ', microtime());
//define('DEBUG_AUTOLOAD', false);
//define('STRICT_ERROR_REPORTING', true);
define('TAOBAO_PATH',dirname(__FILE__) .  '/taobao/');

//require_once dirname(__FILE__) . '/config.inc.php';

class OpenApi {
	
    /**
     * Imports the definition of class(es) and tries to create an object/a list of objects of the class.
     * @param string|array $class_name Name(s) of the class to be imported
     * @param string $path Path to the class file
     * @param bool $createObj Determined whether to create object(s) of the class
     * @return mixed returns NULL by default. If $createObj is TRUE, it creates and return the Object of the class name passed in.
     */
     public static function loadApi($class_name, $createObj=true){
        if(is_string($class_name)){
    		require_once(TAOBAO_PATH . "$class_name.php");
            if($createObj)
                return new $class_name;
        }else{
            //if not string, then a list of Class name, require them all.
            if($createObj)
                $obj=array();

            foreach ($class_name as $one) {
            	require_once(TAOBAO_PATH . "$one.php");
                if($createObj)
                    $obj[] = new $one;
            }

            if($createObj)
                return $obj;
        }
    }
}

/**
 * set the level of error reporting
 * 
 * Note STRICT_ERROR_REPORTING should never be set to true on a production site. <br />
 * It is mainly there to show php warnings during testing/bug fixing phases.<br />
 * note for strict error reporting we also turn on show_errors as this may be disabled<br />
 * in php.ini. Otherwise we respect the php.ini setting 
 * 
 */
//if (defined('STRICT_ERROR_REPORTING') && STRICT_ERROR_REPORTING == true) {
//  @ini_set('display_errors', '1');
//  error_reporting(E_ALL);
//} else {
//  error_reporting(0);
//}
?>