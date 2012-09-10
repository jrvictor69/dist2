<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_UnityClub extends Zend_Form {

	/**
	 * @var string
	 */
	private $source;

	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
			->setAttrib('enctype', 'multipart/form-data')

// 			->addElement('File', 'file', array(
// 					'label' => _('File')
// // 					'required'   => TRUE
// 			))

			->addElement('Hidden', 'id')

			->addElement('Text', 'name', array(
				'label' => _('Name'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('TextArea', 'motto', array(
				'label' => _('Motto'),
				'required' => TRUE,
				'cols' => 40,
				'rows' => 4,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('TextArea', 'description', array(
				'label' => _('Description'),
				'required' => TRUE,
				'cols' => 40,
				'rows' => 4,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Select', 'club', array(
				'label' => _('Club'),
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/UnityClub/template/UnityClubForm.phtml');
	}

	/**
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param string $source
	 * @return Zend_Form
	 */
	public function setSource($source) {
		$this->source = $source;
		return $this;
	}
}