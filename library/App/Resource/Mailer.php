<?php

/**
 * The mailer resource.
 *
 * @package App
 * @subpackage Resource
 */
class App_Resource_Mailer extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Holds the Mailer instance
     *
     * @var
     */
    protected $_mailer;


    public function init()
    {
        // Return mailer so bootstrap will store it in the registry
        return $this->getMailer();
    }


    public function getMailer()
    {
        if( null === $this->_mailer )
        {
            $options = $this->getOptions();

            if( count( $options ) )
            {
                if( isset( $options['auth'] ) )
                {
                    $config = array( $options );
                }
                else
                    $config = array();

                $transport = new Zend_Mail_Transport_Smtp( $options['smtphost'], $config );
                Zend_Mail::setDefaultTransport( $transport );

                $this->_mailer = $transport;
            }
        }

        return $this->_mailer;
    }


}
