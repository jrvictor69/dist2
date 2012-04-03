<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_News extends Zend_Form {
	
	public function init() {
		$this->setAttrib('id', 'formId')
			->setMethod('post')
       		->setAttrib('enctype', 'multipart/form-data');
       	
        $title = new Zend_Form_Element_Text('title');
        $title
        	->setLabel(_('Title'))
            ->setRequired(TRUE)
        	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ;
		
        $summary = new Zend_Form_Element_Textarea('summary');
        $summary
        	->setLabel(_('Summary'))
        	->setAttrib('cols', 10)
        	->setAttrib('rows', 2)
        	->setRequired(TRUE)
           	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ;
                        
       	$contain = new Zend_Form_Element_Textarea('contain');
        $contain
        	->setLabel(_('Contain'))
        	->setAttrib('cols', 10)
        	->setAttrib('rows', 3)
        	->setRequired(TRUE)
           	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ;
       
       $fount = new Zend_Form_Element_Text('fount');
       $fount
        	->setLabel(_('Fount'))
            ->setRequired(TRUE)
        	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ;
              
        $file = new Zend_Form_Element_File('imageFile');
		$file->setLabel(_('Upload Image'))
			->setDestination(APPLICATION_PATH.'/../data/upload')
			->addValidator('Count', false, 1)//ensure only 1 file
			->addValidator('Extension', false, 'jpg, png, gif')            
            ;
        	
        $category = new Zend_Form_Element_Select('categoryId');
		$category
			->setLabel(_('Category'))
			->setRequired(TRUE)
			;
			
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel(_('Save'));

        $this->addElements(array($title, $summary, $contain, $fount, $file, $category, $submit));
	}
}