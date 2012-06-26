<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Form_SearchFilter extends Zend_Form {
	
	public function init() {
		$this
			->setAttrib('id', 'formFilterId')
						
			->addElement('Text', 'nameFilter', array(
				'label' => _('Name')
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/template/SearchFilterForm.phtml');
	}
}