<?php
/**
 * Helper for DIST, Creates button add.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class App_View_Helper_ButtonAdd {
	
	/**
	 * 
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 * 
	 * This function returns the html button add code for input
	 * @param string $id
	 * @param string $name
	 * @param string $href
	 */
	public function buttonAdd($id, $name = "Add", $href = "#") {				
		$html = '<a class="green buttonNg" id="'.$id.'" href="'.$href.'">';
			$html .= '<span class="left"></span>';
				$html .= '<div>'.$name.'</div>';
			$html .= '<span class="right"></span>';
		$html.= '</a>';
		
		return $html;
	}
	
	/**
	 * 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}