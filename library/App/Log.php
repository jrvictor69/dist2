<?php

/**
 * @package App
 * @subpackage Library
 */
class App_Log extends Zend_Log
{

	/**
	 * Show message with more datails.
	 * 
	 * @param string $pMessage
	 */
    public function alert($pMessage)
    {
        $vMessage = $pMessage . "

           " . ( 'host' ) . " : {$_SERVER['HTTP_HOST']}
     " . ( 'user agent' ) . " : {$_SERVER['HTTP_USER_AGENT']}
    " . ( 'remote addr' ) . " : {$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']}
" . ( 'script filename' ) . " : {$_SERVER['SCRIPT_FILENAME']}
 " . ( 'request method' ) . " : {$_SERVER['REQUEST_METHOD']}
   " . ( 'query string' ) . " : {$_SERVER['QUERY_STRING']}
    " . ( 'request uri' ) . " : {$_SERVER['REQUEST_URI']}
";

        try
        {
            $this->log($vMessage, Zend_Log::ALERT);
        }
        catch(Exception $e)
        {
            $this->debug($e->getMessage());
        }
    }

}
