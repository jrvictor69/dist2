<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Picture extends Zend_Form {
	
	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
       		->setAttrib('enctype', 'multipart/form-data');
															
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel(_('Title'))
			->setRequired(TRUE)
			->addFilter('StringTrim');

		$description = new Zend_Form_Element_Textarea('description');
		$description->setLabel(_('Description'))
				->setAttrib("cols", 40)
				->setAttrib("rows", 4)
				->addFilter('StringTrim');
              
		$file = new Zend_Form_Element_File('file');
		$file->setLabel(_('File'))
				->setRequired(TRUE)
				->setDestination(APPLICATION_PATH.'/../public/image/upload')
				->addValidator('Extension', false, 'jpg, png, gif');

		$this->addElements(array($title, $description, $file));
	}
}