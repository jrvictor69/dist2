<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Form_DirectiveViewProfile extends Zend_Form {

	/**
	 * @var string
	 */
	private $source;

	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
			->setAttrib('enctype', 'multipart/form-data')

			->addElement('Text', 'firstName', array(
				'label' => _('Firstname')
			))

			->addElement('Text', 'lastName', array(
				'label' => _('Lastname')
			))

			->addElement('Text', 'sex', array(
				'label' => _('Sex')
			))

			->addElement('Text', 'email', array(
				'label' => _('Email')
			))

			->addElement('Text', 'phonemobil', array(
				'label' => _('Phone mobil')
			))

			->addElement('Text', 'phone', array(
				'label' => _('Phone')
			))

			->addElement('Text', 'club', array(
				'label' => _('Club')
			))

			->addElement('Text', 'position', array(
				'label' => _('Position')
			));
	}

	public function loadDefaultDecorators() {
		$this->setDecorators(
			array(
				new \Zend_Form_Decorator_PrepareElements(),
				'ViewScript'
			)
		);
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Pathfinder/template/DirectiveViewForm.phtml');
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