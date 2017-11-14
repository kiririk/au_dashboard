<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file'); 

class AuDashController extends JControllerLegacy
{
	function deleteArticle() {
		$jinput = JFactory::getApplication()->input;
		$articleId = $jinput->get('articleId');
		
		$db = JFactory::getDBO();
		
		$app = JFactory::getApplication();  
		$link = JRoute::_('index.php?option=com_audash');
		
		$query = "SELECT `path`  
		FROM `#__cf_articles` 
		WHERE `id` = {$articleId} AND `status`<>3;";
		$db->setQuery($query);
		$path = $db->loadResult();
		$path = explode('/', $path);
			
		$ds = '\\';
		JFile::delete(JPATH_ROOT.$ds.$path[4].$ds.$path[5].$ds.$path[6].$ds.$path[7].$ds.$path[8].$ds.$path[9]);
		
		$query = "DELETE FROM `#__cf_articles` 
		WHERE `id` = {$articleId} AND `status`<>3;";
		$db->setQuery($query);
		$result = $db->execute();
		
		$link = $_SERVER['HTTP_REFERER'];
		$app->redirect($link);
	}
	
	function searchUsers() {
		$jinput = JFactory::getApplication()->input;
		$search= $jinput->get('search');
		
		$app = JFactory::getApplication();
		$link = 'index.php?option=com_audash&view=plist'.'&search='.$search;
		$app->redirect($link);
	}
	
	function searchArticles() {
		$status = htmlspecialchars($_POST["status"]);
		$search = htmlspecialchars($_POST["search"]);
		
		$app = JFactory::getApplication();
		$link = 'index.php?option=com_audash&view=lists'.'&status='.$status.'&search='.$search;
		$app->redirect($link);
	}
	
	function changeArticlesStatus() {
		$json = $_POST['json'];
		
		if (!isset($json)) {
			$app = JFactory::getApplication();
			$link = 'index.php?option=com_audash';
			$app->redirect($link);
		}
		
		$result = json_decode($json);

		$cleaned_result = array();
		foreach ($result as $key => $value) {
			$cleaned_result[substr($key,6)] = $value; 
		}
		
		$db = JFactory::getDBO();
		$query = "SELECT `id`  
		FROM `#__cf_articles`;";
		$db->setQuery($query);
		$art_ids = $db->loadColumn();
		
		foreach ($cleaned_result as $key => $value) {
			if (in_array(intval($key), $art_ids)) {
				$kk = intval($key);
				$query = "UPDATE `#__cf_articles`
				SET `status` = {$value}, `etc` = ''
				WHERE `id` = {$kk};";
				$db->setQuery($query);
				$result = $db->execute();
				
				//почта
				
				$query = "SELECT u.`email`, u.`username`, art.`article_name`, art.`article_authors`, art.`status`
				FROM `#__cf_articles` as art, `#__users` as u
				WHERE art.`id` = {$kk} AND art.`user_id` = u.`id`;";
				$db->setQuery($query);
				$result = $db->loadRow();
				
				$stats_rus = array('На обработке','Готова к публикации','Отклонена','Опубликована');
				$stats_eng = array('In process','Accepted','Returned','Published');
				
				$to      = $result[0];
				$subject = "Статус статьи изменен / The arcticle's status was changed";
				$message = $result[1].' / '.$result[2].' / '.$result[3]."\r\n".
				$message .= $stats_rus[intval($result[4])];
				$message .= ' / ';
				$message .= $stats_eng[intval($result[4])];
				$message = wordwrap($message, 70, "\r\n");
				$headers = 'From: webmaster@example.com' . "\r\n" .
						'Reply-To: webmaster@example.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();

				mail($to, $subject, $message, $headers);
				
				$message = ''; $subject = ''; $headers = ''; $to = '';
				
				//почта
			}
		}
	}
	
	function returningArticle() {
		$reason = $_POST['why'];
		$artID = $_POST['articleId'];
		
		$db = JFactory::getDBO();
		$query = "UPDATE `#__cf_articles`
				SET `etc` = '{$reason}', `status` = 2
				WHERE `id` = {$artID};";
		$db->setQuery($query);
		$result = $db->execute();
		
		//почта
		
		$query = "SELECT u.`email`, u.`username`, art.`article_name`, art.`article_authors`
				FROM `#__cf_articles` as art, `#__users` as u
				WHERE art.`id` = {$artID} AND art.`user_id` = u.`id`;";
		$db->setQuery($query);
		$result = $db->loadRow();
		
		$to      = $result[0];
		$subject = 'Статья отклонена - причины / The arcticle was returned: reasons';
		$message = $result[1].' / '.$result[2].' / '.$result[3]."\r\n".
		$message .= $reason;
		$message = wordwrap($message, 70, "\r\n");
		$headers = 'From: webmaster@example.com' . "\r\n" .
				'Reply-To: webmaster@example.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		
		//почта
		
		$app = JFactory::getApplication();
		$link = $_SERVER['HTTP_REFERER'];
		$app->redirect($link);
	}
}
?>