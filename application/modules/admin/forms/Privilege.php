<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Privilege extends Zend_Form {
	
	public function init() {
		$this
			->setAttrib('id', 'formId')
									
			->addElement('Text', 'name', array(
				'label' => _('Name'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('TextArea', 'description', array(
				'label' => _('Description'),
				'cols' =>'40',
        		'rows' =>'4',
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Text', 'module', array(
				'label' => _('Module'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Text', 'controller', array(
				'label' => _('Controller'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('Text', 'action', array(
				'label' => _('Action'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Privilege/template/PrivilegeForm.phtml');
	}
}