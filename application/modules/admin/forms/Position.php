<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Position extends Zend_Form {
	
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
			;
	}
}