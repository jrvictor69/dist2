<?php

/**
 * The namespace (session) resource.
 *
 * @package App
 * @subpackage Resource
 */
class App_Resource_Namespace extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Holds the Logger instance
     *
     * @var
     */
    protected $_session;


    public function init()
    {
        // Return session so bootstrap will store it in the registry
        return $this->getSession();
    }


    public function getSession()
    {
        if( null === $this->_session )
        {
        	// init resource session
            $this->getBootstrap()->bootstrap( 'Session' );

            // Get session configuration options from the application.ini file
            $options = $this->getOptions();

            $ApplicationNamespace = new Zend_Session_Namespace( 'Application' );

            // Secutiry tip from http://framework.zend.com/manual/en/zend.session.global_session_management.html
            if( !isset( $ApplicationNamespace->initialised ) )
            {
                // FIXME Zend_Session::regenerateId();
                $ApplicationNamespace->initialized = true;
            }

            // ensure IP consistancy
            if ( (isset($options['checkip'])) && ($options['checkip']) && (isset($_SERVER['REMOTE_ADDR'])) )
            {
                if( !isset( $ApplicationNamespace->clientIP ) )
                {
                    $ApplicationNamespace->clientIP = $_SERVER['REMOTE_ADDR'];
                }
                else if( $ApplicationNamespace->clientIP != $_SERVER['REMOTE_ADDR'] )
                {
                    // security violation - client IP has changed indicating a possible hijacked session
                    $this->getBootstrap()->bootstrap( 'Logger' );
                    $this->getBootstrap()->getResource('logger')->warn(
                        _( 'IP address changed - possible session hijack attempt.')
                        . ' ' . _( 'old' ) . ": {$ApplicationNamespace->clientIP} " . _( 'new' ) . ": {$_SERVER['REMOTE_ADDR']}"
                    );
                    Zend_Session::destroy( true, true );
                    die( _( 'Your IP address has changed indication a possible session hijack attempt. Your session has been destroyed for your own security.' ) );
                }
            }

            $this->_session = $ApplicationNamespace;
        }

        return $this->_session;
    }

}
