<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file'); 

class AuDashController extends JControllerLegacy
{
	function deleteArticle() {
		$jinput = JFactory::getApplication()->input;
		$articleId = $jinput->get('articleId');
		$user = JFactory::getUser();
		$user_id = $user->get('id'); 
		
		$db = JFactory::getDBO();
		$query = "SELECT `user_id`  
		FROM `#__cf_articles` 
		WHERE `id` = {$articleId} AND `status`<>3;";
		$db->setQuery($query);
		$result = $db->loadResult();
		
		$app = JFactory::getApplication();  
		$link = JRoute::_('index.php?option=com_audash');
		
		if ($result == $user_id) {
			$query = "SELECT `path`  
			FROM `#__cf_articles` 
			WHERE `id` = {$articleId};";
			$db->setQuery($query);
			$path = $db->loadResult();
			$path = explode('/', $path);
			
			$ds = '\\';
			JFile::delete(JPATH_ROOT.$ds.$path[4].$ds.$path[5].$ds.$path[6].$ds.$path[7].$ds.$path[8].$ds.$path[9]);
			
			$query = "DELETE FROM `#__cf_articles` 
			WHERE `id` = {$articleId};";
			$db->setQuery($query);
			$result = $db->execute();
			
			$msg = JText::_('СOM_AUDASH_MESSDEL');
			$app->redirect($link, $msg, $msgType='message');
		} else {
			$msg = JText::_('СOM_AUDASH_MESSCANTDEL');
			$app->redirect($link, $msg, $msgType='warning');
		}
	}
}
?>