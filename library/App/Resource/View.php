<?php

/**
 * The Zend View resource.
 *
 * @package App
 * @subpackage Resource
 */
class App_Resource_View extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Holds the View instance
     *
     * @var
     */
    protected $_view;


    public function init()
    {
        // Return view so bootstrap will store it in the registry
        return $this->getView();
    }


    public function getView()
    {
        // Get session configuration options from the application.ini file
        $options = $this->getOptions();
		
        if( $options['enabled'] )
        {
        	
            if( null === $this->_view )
            {
                // Initialize view
                $view = new Zend_View();
                $view->doctype( $options['doctype'] );
                $view->headTitle( $options['title'] );
                $view->headMeta()->appendHttpEquiv('Content-Type',$options['content-type']);
				$view->addHelperPath(APPLICATION_PATH . '/../library/App/View/Helper','App_View_Helper');
                // Add it to the ViewRenderer
                $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper( 'ViewRenderer' );
                $viewRenderer->setView( $view );

                $this->_view = $view;
            }

            return $this->_view;
        }
    }
}
