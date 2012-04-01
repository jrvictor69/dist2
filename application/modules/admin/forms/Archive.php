<?php
/**
 * 
 * Enter description here ...
 * @author victor villca
 *
 */
class Admin_Form_Archive extends Zend_Form {
		
	public function init() {
        $this->setName('archive');
        $this->setAttrib('enctype', 'multipart/form-data');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
               ->setRequired(true)
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('NotEmpty');

        $note = new Zend_Form_Element_Textarea('note');
        $note->setLabel('Note')
        		->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty');
              
		$file = new Zend_Form_Element_File('binary');
		$file->setLabel('Upload')
			->setRequired(true)
			//->setDestination(APPLICATION_PATH.'/../data/uploads')
			//->setDestination('D:/temp')
			//->addValidator('Count', false, 1)//ensure only 1 file
			//->addValidator('Size', false, 102400)//limit to 100K
			//->addValidator('Extension', false, 'jpg, png, gif');//only images
			->addValidator('Extension', false, 'doc, docx, pdf');//only doc, docx
			
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $name, $note, $file, $submit));
    }
}