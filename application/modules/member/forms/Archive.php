<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Member_Form_Archive extends Zend_Form {
	
	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
       		->setAttrib('enctype', 'multipart/form-data')
												
			->addElement('Text', 'name', array(
				'label' => _('Name'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('TextArea', 'note', array(
				'label' => _('Note'),
				'cols' =>'40',
        		'rows' =>'4',
				'filters' => array(
					array('StringTrim')
				)
			))
			
			->addElement('File', 'file', array(
				'label' => _('File'),
				'required'   => TRUE
			))
			;
	}
}