<?php
defined('_JEXEC') or die('Restricted access');

class AuDashViewLists extends JViewLegacy
{
	function display($tpl = null)
	{
		// Assign data to the view
		$this->msg = 'main view';
 
		// Display the view
		parent::display($tpl);
	}
}
?>