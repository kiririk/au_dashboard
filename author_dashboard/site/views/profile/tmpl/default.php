<?php
defined('_JEXEC') or die('Restricted access');
$user = \JFactory::getUser();
$app = JFactory::getApplication();    
$id=$user->id;

function checkData ($str) {
	return (strlen($str)>0) ? $str : 'n/a';
}

if ($id == 0) {
	$link = 'index.php'; 
	$app->redirect($link);
} else {
	$db = JFactory::getDBO();
	$query = "SELECT `last_name`, `patronym`, `organisation`, `position`, `tel`
	  FROM `#__cf-user_info`
	  WHERE `user_id` = {$id};
	";
	$db->setQuery($query);
	$results = $db->loadAssoc();
	
	foreach ($results as $key => $value) {
		$results[$key] = checkData($value);
	}
	
	$COM_AUDASH_TITLEPROF = JText::_('COM_AUDASH_TITLEPROF');
	$COM_AUDASH_REGDATA = JText::_('COM_AUDASH_REGDATA');
	$COM_AUDASH_LOGIN = JText::_('COM_AUDASH_LOGIN');
	$COM_AUDASH_EMAIL = JText::_('COM_AUDASH_EMAIL');
	$COM_AUDASH_LASTNAME = JText::_('COM_AUDASH_LASTNAME');
	$COM_AUDASH_FIRSTNAME = JText::_('COM_AUDASH_FIRSTNAME');
	$СOM_AUDASH_MIDNAME = JText::_('СOM_AUDASH_MIDNAME');
	$СOM_AUDASH_ORG = JText::_('СOM_AUDASH_ORG');
	$СOM_AUDASH_POS = JText::_('СOM_AUDASH_POS');
	$СOM_AUDASH_TEL = JText::_('СOM_AUDASH_TEL');
	
	echo "
		<style>
			.dl-horizontal dt {
				width: 300px;
			}
			.dl-horizontal dd {
				margin-left: 310px;
			}
		</style>
		<div class='profile'>
			<fieldset id='users-profile-core'>
				<legend>{$COM_AUDASH_TITLEPROF}</legend>
				<dl class='dl-horizontal'>
					<dt>{$COM_AUDASH_REGDATA}</dt><dd>{$user->registerDate}</dd>
					<dt>{$COM_AUDASH_LOGIN}</dt><dd>{$user->username}</dd>
					<dt>{$COM_AUDASH_EMAIL}</dt><dd>{$user->email}</dd>
					<dt>{$COM_AUDASH_LASTNAME}</dt><dd>{$results['last_name']}</dd>
					<dt>{$COM_AUDASH_FIRSTNAME}</dt><dd>{$user->name}</dd>
					<dt>{$СOM_AUDASH_MIDNAME}</dt><dd>{$results['patronym']}</dd>
					<dt>{$СOM_AUDASH_ORG}</dt><dd>{$results['organisation']}</dd>
					<dt>{$СOM_AUDASH_POS}</dt><dd>{$results['position']}</dd>
					<dt>{$СOM_AUDASH_TEL}</dt><dd>{$results['tel']}</dd>
				</dl>
			</fieldset>
		</div>
	";
}
?>