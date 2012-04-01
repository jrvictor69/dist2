<?php
class Form_Registration extends Zend_Form {
	
	public function init() {
        $this->setMethod('post');
        $firstName = new Zend_Form_Element_Text('firstName');
        $firstName
        	->setLabel('First Name:')
        	->setRequired(true);

        $lastName = new Zend_Form_Element_Text('lastName');
        $lastName
        	->setLabel('Last Name:')
        	->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Save');
        
        $this->addElements(array($firstName, $lastName, $submit));

		$this->setElementDecorators(array(
        	'ViewHelper',
            array(array('data' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element')),
            array('Label', array('tag' => 'td')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));
        
        $submit->setDecorators(array('ViewHelper',
            array(array('data' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element')),
            array(array('emptyrow' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element', 'placement' => 'PREPEND')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
            ));
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            'Form'
        ));
    }
    
    
    public function loadDefaultDecorators() {
    	$this->setDecorators(array(
    		'FormElements',
    		array('HtmlTag', array('tag' => 'table')),
    		'Form',
    	));
    }
    
//    public function loadDefaultDecorators()
//    {
//        $this->setDecorators(array(
//            'FormElements',
//            array('HtmlTag', array('tag' => 'table')),
//            'Form',
//        ));
//    }
    
}