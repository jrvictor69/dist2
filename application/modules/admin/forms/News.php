<?php
class Admin_Form_News extends Zend_Form {
		
	public function init() {
		$this->setName('news');
		
        $this->setAttrib('class', 'featured-inner object-lead');
        $this->setAttrib('enctype', 'multipart/form-data');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

        $title = new Zend_Form_Element_Text('titleNews');
        $title
        	->setLabel(_('Title'))
            ->setRequired(true)
        	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addDecorators(array(
					array('ViewHelper'),
					array('Errors'),
					array('Label', array('separator' => ' ')),
					array('HtmlTag', array('tag' => 'div', 'class' => 'container'))))
            ;
            
            

        $summary = new Zend_Form_Element_Textarea('summary');
        $summary
        	->setLabel(_('Summary'))
        	->setAttrib('cols', 10)
        	->setAttrib('rows', 2)
        	->setRequired(true)
           	->addFilter('StripTags')
            ->addFilter('StringTrim')
       		->addDecorators(array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div'))))
            ;
                        
       	$contain = new Zend_Form_Element_Textarea('contain');
        $contain
        	->setLabel(_('Contain'))
        	->setAttrib('cols', 10)
        	->setAttrib('rows', 3)
        	->setRequired(true)
           	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addDecorators(array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div'))))
            ;
            
            
       $fount = new Zend_Form_Element_Text('fount');
       $fount
        	->setLabel(_('Fount'))
            ->setRequired(true)
        	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addDecorators(array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div'))))
            ;
              
        $file = new Zend_Form_Element_File('imageFile');
		$file->setLabel('Upload File')
			->setRequired(true)
			->setDestination(APPLICATION_PATH.'/../data/uploads')
			->addValidator('Count', false, 1)//ensure only 1 file
			->addValidator('Extension', false, 'jpg, png, gif')//only images
			
		
					
            
            ;
            
//		$file = new Zend_Form_Element_File('image');
//		$file->setLabel(_('File Imagen'))
//			->setRequired(true)
//			->setDestination(APPLICATION_PATH.'/../data/uploads')
//			//->setDestination('D:/temp')
//			->addValidator('Count', false, 1)//ensure only 1 file
//			//->addValidator('Size', false, 102400)//limit to 100K
//			->addValidator('Extension', false, 'jpg, png, gif');//only images
////			->addValidator('Extension', false, 'doc, docx, pdf');//only doc, docx
			
		$result = array('2' => 'Deportes', '4' => 'Nacionales', '5' => 'Conquistadores', '6' => 'Guias Mayores');	
        $category = new Zend_Form_Element_Select('categoryId');
		$category
			->setLabel(_('Category'))
//			->setMultiOptions($result)
			->setRequired(true)
			->addDecorators(array(
					array('ViewHelper'),
					array('label'),
					array('Errors', array('class' => 'errorlist')),
					array('HtmlTag', array('tag' => 'div'))))
			;
			
        $submit = new Zend_Form_Element_Submit('submit');
        $submit
        	->setLabel(_('Save'));
//        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $title, $summary, $contain, $fount, $file, $category, $submit));
	}
}