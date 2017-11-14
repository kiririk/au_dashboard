<?php
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->setTitle('Список пользователей ЛК автора');

$jinput = JFactory::getApplication()->input;
$search = $jinput->get('search');

function getRowsUsers($search) {
	$db = JFactory::getDBO();
	$query = "SELECT us.`id`, uf.`login`, us.`email`, uf.`last_name`, us.`name`, uf.`patronym`, uf.`organisation`, uf.`position`, uf.`tel`
	FROM `#__users` as us, `#__cf-user_info` as uf  
	WHERE (us.`id` = uf.`user_id`)";
	
	if (strlen($search)>0) {
		$query .= " AND ((us.`id` LIKE '%{$search}%') OR (uf.`login` LIKE '%{$search}%') OR (us.`email` LIKE '%{$search}%')";
		$query .= " OR (uf.`last_name` LIKE '%{$search}%') OR (us.`name` LIKE '%{$search}%') OR (uf.`patronym` LIKE '%{$search}%')";
		$query .= " OR (uf.`organisation` LIKE '%{$search}%') OR (uf.`position` LIKE '%{$search}%') OR (uf.`tel` LIKE '%{$search}%'))";
	}
	
	$query .= ';';
	$db->setQuery($query);
	return $db->loadRowList();
}

function echoResults($results) {
	echo "<table border='0'>";
	echo "<tr>
			<th>ID</th>
			<th>Логин</th>
			<th>Email</th>
			<th>Фамилия</th>
			<th>Имя</th>
			<th>Отчество</th>
			<th>Организация</th>
			<th>Должность</th>
			<th>Телефон</th>
			";
	echo "</tr>";
	foreach ($results as $subArray) {
		echo "<tr>";
		for ($i = 0; $i < count($subArray); $i++) {
			echo "<td><div style='max-height: 150px; overflow-y:auto;'>";
			if ($i == 1) {
				echo "<a target='_blank' href='index.php?option=com_audash&view=profile&userId={$subArray[0]}'>{$subArray[$i]}</a>";
			} elseif ($i == 2) {
				echo "<a href='mailto:{$subArray[$i]}'>{$subArray[$i]}</a>";
			} else {
				echo "{$subArray[$i]}";
			}
			echo "</div></td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
?>
<style>
h1 {
	margin-top: 0;
}

header, #isisJsData {
display: none;}

.main-flex-container {
display: flex;
flex-flow: row nowrap;
justify-content: space-around;
align-items: stretch;}

aside {
	border-right: 1px black solid;
	padding-right: 10px;
	min-width: 15%;
}

article {
	margin-left: 10px;
	min-width: 85%;
}

.vertical-align {
position: relative;
top: 50%;
transform: translateY(-50%);
margin:0;
}

.ul-menu {
	list-style-type: none;
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: auto;
}

.ul-menu li {
	padding: 5px 0;
}

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    padding: 4px;
}

th {
	text-align: center;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
form {
	margin: 0;
}
</style>
<h1 align='center'>Список пользователей ЛК автора</h1>
<div class="main-flex-container">
	<aside>
		<ul class='ul-menu'>
		  <li><a href="<?php echo JRoute::_('index.php?option=com_audash&view=lists&status=0&search=&page=1') ?>">Статьи на обработке</a></li>
		  <li><a href="<?php echo JRoute::_('index.php?option=com_audash&view=lists&status=1&search=&page=1') ?>">Статьи готовые к публикации</a></li>
		  <li><a href="<?php echo JRoute::_('index.php?option=com_audash&view=lists&status=2&search=&page=1') ?>">Отклоненные статьи</a></li>
		  <li><a href="<?php echo JRoute::_('index.php?option=com_audash&view=lists&status=3&search=&page=1') ?>">Опубликованные статьи</a></li>
		  <li><a href="<?php echo JRoute::_('index.php?option=com_audash&view=plist') ?>">Список пользователей ЛК автора</a></li>
		</ul>
	</aside>
	<article>
		<form method='post' action='<?php echo JRoute::_('index.php?option=com_audash&task=searchUsers') ?>'>
			<input type="text" size='40' placeholder="Поиск" name='search'>
			<input type="submit" style="margin: 0 0 9px 5px;" value="Искать">
		</form>
		<?php
			$results = getRowsUsers(isset($search) ? $search : '');
			echoResults($results);
		?>
		<br>
		<a target='_blank' href='index.php?option=com_users&view=users'>Менеджер пользователей: Пользователи</a>
	</article>
</div>