<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Managerial extends Zend_Form {
	
	public function init() {
		$this
			->setAttrib('id', 'formId')
									
			->addElement('Text', 'firstName', array(
				'label' => _('Firstname'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Text', 'lastName', array(
				'label' => _('Lastname'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Text', 'identityCard', array(
				'label' => _('Cedula identidad'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
					array('Digits', false),
					array('Between', false, array('min' => 3000000, 'max' => 8000000)),
				)
			))
			
			->addElement('Radio', 'sex', array(
				'label' => _('Sex'),
				'required' => TRUE
			))
			
			->addElement('Text', 'username', array(
				'label' => _('Username'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Password', 'password', array(
				'label' => _('Password'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Password', 'passwordConfirm', array(
				'label' => _('Confirm Password'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Text', 'email', array(
				'label' => _('Email'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
                	'EmailAddress'
            	)
			))
			
			->addElement('Text', 'address', array(
				'label' => _('Address'),
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Text', 'phone', array(
				'label' => _('Phone'),
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Text', 'phonemobil', array(
				'label' => _('Phone mobil'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
					array('Digits', false)
				)
			))
			
			->addElement('Text', 'phonework', array(
				'label' => _('Phone work'),
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Select', 'userGroupId', array(
				'label' => _('User Group'),
				'required' => TRUE
			))
			;
	}
	
	public function loadDefaultDecorators() {
		$this->setDecorators(
			array(
				new \Zend_Form_Decorator_PrepareElements(),
				'ViewScript'
			)
		);	
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Managerial/template/ManagerialForm.phtml');
	}
}