<?php

class Form_Login extends Zend_Form {
	
    public function init() {
    	
        $this->setName("login");
        $this->setMethod('post');
        $this->setAttrib('class', 'featured-inner object-lead');
             
        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(3, 20)),
            ),
            'required'   => true,
            'label'      => _('Username:')
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(3, 20)),
            ),
            'required'    => true,
            'label'       => _('Password:')
        ));

      
       
        
        $this->addElement('button', 'buttonlogin', array(
        	'type'       => 'submit',
            'required'   => false,
            'ignore'     => true,
            'label'      => _('Login'),
        	'class'    	 => 'prominent'
        ));
    }
        
 	public function loadDefaultDecorators() {
    	$this->setDecorators(array(
    		'FormElements',
    		'Form'
    	));
    }
}

