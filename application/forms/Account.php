<?php

class Form_Account extends Zend_Form {
	
	protected $_update = false;
	
	public function init() {
//		$this->update = true;
		
        $this->setName("account");
        $this->setMethod('post');
//        $this->setAttrib('class', 'featured-inner object-lead user-input');
        
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
                )
            ),

            new Zend_Form_Element_Password('oldpassword', array(
                'label'      => _('Old Password'),
            	'maxlength'  => 255,
//                'filters'    => array('StringTrim', 'StripTags'),
//                'validators' => array(
//                    array('StringLength', false, array(5, 20))
//                ),
                'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div')))                
            )),
            
            new Zend_Form_Element_Password('password', array(
                'label'      => _('New Password'),
            	'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StripTags'),
//                'validators' => array(
//                    'NotEmpty',
//                    array('StringLength', false, array(5, 20))
//                ),
				'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('HtmlTag', array('tag' => 'div')))                  
                
            )),
//            The passwords did not match.
            new Zend_Form_Element_Password('password2', array(
                'label'      => _('Confirm password'),
            	'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StripTags'),
//                'validators' => array(
//                    'NotEmpty',
//                    array('StringLength', false, array(5, 20))
//                ),
                'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div')))
            )),
            
            new Zend_Form_Element_Text('displayname', array(
                'label'      => _('Display Name'),
            	'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StringToLower'),
            	'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('HtmlTag', array('tag' => 'div')))
                )
            ),
            
            new Zend_Form_Element_Text('location', array(
//            	'required'   => true,
                'label'      => _('Location'),
            	'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StringToLower'),
            	'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('Errors'),
					array('HtmlTag', array('tag' => 'div')))
                )
                
            ),
            
            new Zend_Form_Element_Text('occupation', array(
                'label'      => _('Occupation'),
            	'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StringToLower'),
            	'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('HtmlTag', array('tag' => 'div')))
                )
            ),
            
            new Zend_Form_Element_Text('homepage', array(
                'label'      => _('Homepage'),
            	'maxlength'  => 255,
                'filters'    => array('StringTrim', 'StringToLower'),
            	'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('HtmlTag', array('tag' => 'div')))
                )
            ),
            
            new Zend_Form_Element_File('photo', array(
            	'label' 	  => _('Profile Photo'),
//				'destination' => APPLICATION_PATH.'/../data/uploads',
            	'validators' => array(
							array('Count', false, 1),
//							array('Size', false, 3145728),
							array('Extension', false, 'jpg, png'),
//							array('ImageSize', false, array('minwidth' => 100,
//                                                'minheight' => 100,
//                                                'maxwidth' => 3000,
//                                                'maxheight' => 3000))
							)
				       	
            )),
            
            new Zend_Form_Element_Textarea('detail', array(
            	'cols' => 40,
            	'rows' => 10,
            	'decorators' => array(
					array('ViewHelper'),
					array('label'),
					array('HtmlTag', array('tag' => 'div'))),
					'decorators' => array(
						array('ViewHelper'),
						array('HtmlTag', array('tag' => 'div', 'class' => 'trans')))
            )),
            
            new Zend_Form_Element_Button('button', array(
            	'label' => _('Update'),
            	'class' => 'prominent',
            	'type'  => 'submit',
            	'decorators' => array(
					array('ViewHelper'),
					array('HtmlTag', array('tag' => 'div', 'class' => 'listing-footer')))
            	
     		)))            	
		);

		
		
//		$file = new Zend_Form_Element_File('file');
//		$file->setLabel('Upload')
//			->setRequired(true)
//			->setDestination(APPLICATION_PATH.'/../data/uploads')
//			->addValidator('Extension', false, 'jpg, png, gif');//only images
    }
    
 	public function loadDefaultDecorators() {
		$this->setDecorators(
			array(
				new \Zend_Form_Decorator_PrepareElements(),
				'ViewScript'
			)
		);	
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Auth/decorators/account.phtml');
	}
	
	public function isUpdate() {
		return $this->_update;	
	}
	
	public function marketUpdate() {
		$this->_update = true;	
	}
}