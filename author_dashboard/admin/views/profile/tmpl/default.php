<?php
defined('_JEXEC') or die('Restricted access');

$jinput = JFactory::getApplication()->input;
$id = $jinput->get('userId');  

function checkData ($str) {
	return (strlen($str)>0) ? $str : 'n/a';
}

if (!isset($id)) {
	$app = JFactory::getApplication();
	$link = 'index.php?option=com_audash'; 
	$app->redirect($link);
} else {
	$db = JFactory::getDBO();
	$query = "SELECT uj.`registerDate`, uj.`username`, uj.`email`, ui.`last_name`, uj.`name`, ui.`patronym`, ui.`organisation`, ui.`position`, ui.`tel` 
	  FROM `#__cf-user_info` as ui, `#__users` as uj
	  WHERE ui.`user_id` = {$id} AND uj.`id` = {$id};
	";
	$db->setQuery($query);
	$results = $db->loadRow();
	
	if (count($results) == 0) {
		$app = JFactory::getApplication();
		$link = 'index.php?option=com_audash'; 
		$app->redirect($link);
	}
	
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
	
	$document = JFactory::getDocument();
	$document->setTitle($COM_AUDASH_TITLEPROF);
	
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
				<legend>{$COM_AUDASH_TITLEPROF} о {$results[1]}</legend>
				<dl class='dl-horizontal'>
					<dt>{$COM_AUDASH_REGDATA}</dt><dd>{$results[0]}</dd>
					<dt>{$COM_AUDASH_LOGIN}</dt><dd>{$results[1]}</dd>
					<dt>{$COM_AUDASH_EMAIL}</dt><dd><a href='mailto:{$results[2]}'>{$results[2]}</a></dd>
					<dt>{$COM_AUDASH_LASTNAME}</dt><dd>{$results[3]}</dd>
					<dt>{$COM_AUDASH_FIRSTNAME}</dt><dd>{$results[4]}</dd>
					<dt>{$СOM_AUDASH_MIDNAME}</dt><dd>{$results[5]}</dd>
					<dt>{$СOM_AUDASH_ORG}</dt><dd>{$results[6]}</dd>
					<dt>{$СOM_AUDASH_POS}</dt><dd>{$results[7]}</dd>
					<dt>{$СOM_AUDASH_TEL}</dt><dd>{$results[8]}</dd>
				</dl>
			</fieldset>
		</div>
	";
}
?>
<style>
header, #isisJsData {
display: none;}
</style>