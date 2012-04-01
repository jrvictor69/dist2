<?php

class Form_Register extends Zend_Form {
	
	public function init() {
	
        $this->setName("login");
        $this->setMethod('post');
        $this->setAttrib('class', 'featured-inner object-lead user-input');
        
        $this->addElements(array(
           	new Zend_Form_Element_Text('username', array(
                'required'   => true,
                'label'      => _('Username'),
           		'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StringToLower'),
                'validators' => array(
                    'Alnum',
                    array('Regex',
                          false,
                          array('/^[a-z][a-z0-9]{2,}$/'))
                ),
                'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div')))
            )),
                        
            new Zend_Form_Element_Text('email', array(
                'required'   => true,
                'label'      => _('Email Address'),
            	'maxlength'  => 75,
                'filters'    => array('StringTrim', 'StringToLower'),
                'validators' => array('EmailAddress'),
             	'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div')))
            )),

            new Zend_Form_Element_Password('password', array(
                'required'   => true,
                'label'      => _('Password'),
            	'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StripTags'),
                'validators' => array(
                    'NotEmpty',
                    array('StringLength', false, array(5, 20))
                ),
                'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div')))
            )),
            
            new Zend_Form_Element_Password('password2', array(
                'required'   => true,
                'label'      => _('Confirm password'),
            	'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StripTags'),
                'validators' => array(
                    'NotEmpty',
                    array('StringLength', false, array(5, 20))
                ),
                'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div')))
            )),
            
            new Zend_Form_Element_Submit('submit', array(
            	'label'  => _('Register')
//            	'decorators' => array(
//				array('ViewHelper'),
//
//				array('HtmlTag', array('tag' => 'div', 'class'=>'fm-control')))
            	
     		)))            	
		);     
    }

	public function isValidPassword($data) {
        $passTwice = $this->getElement('password2');     
        $passTwice->getValidator('Identical')->setToken($data['password']);        
        return parent::isValid($data);
    }
    
 	public function loadDefaultDecorators() {
		$this->setDecorators(
			array(
				new \Zend_Form_Decorator_PrepareElements(),
				'ViewScript'
			)
		);	
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Register/decorators/register.phtml');
	}
}