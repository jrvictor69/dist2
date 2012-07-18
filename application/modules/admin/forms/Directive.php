<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Managerial extends Zend_Form {

	public function init() {
		$this
			->setAttrib('id', 'formId')

			->addElement('Text', 'firstName', array(
				'label' => _('Firstname'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'lastName', array(
				'label' => _('Lastname'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Radio', 'sex', array(
				'label' => _('Sex'),
				'required' => TRUE
			))

			->addElement('Text', 'email', array(
				'label' => _('Email'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
					'EmailAddress'
				)
			))

			->addElement('Text', 'phone', array(
				'label' => _('Phone'),
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'phonemobil', array(
				'label' => _('Phone mobil'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
					array('Digits', false)
				)
			))

			->addElement('Text', 'phonework', array(
				'label' => _('Phone work'),
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
}