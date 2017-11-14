<?php
defined('_JEXEC') or die('Restricted access');

$PROCESS = 0;
$PRESS = 1;
$RETURNED = 2;
$PUBLISHED = 3;

$status = htmlspecialchars($_GET['status']);
$search = htmlspecialchars($_GET['search']);
$page_num = htmlspecialchars($_GET['page']);

$max_rows = 10;

if (intval($page_num) == 0) {
	$page_num = 1;
	$app = JFactory::getApplication();
	$link = 'index.php?option=com_audash&view=lists'.'&status='.$status.'&search='.$search.'&page='.$page_num;
	$app->redirect($link);
}

$page_title = '';

if (isset($status)) {
	switch ($status) {
		case $PROCESS:
			$page_title = 'Статьи на обработке';
			break;
		case $PRESS:
			$page_title = 'Статьи готовые к публикации';
			break;
		case $RETURNED:
			$page_title = 'Отклоненные статьи';
			break;
		case $PUBLISHED:
			$page_title = 'Опубликованные статьи';
			break;
		default:
			$app = JFactory::getApplication();
			$link = 'index.php?option=com_audash'; 
			$app->redirect($link);
	}
} else {
	$app = JFactory::getApplication();
	$link = 'index.php?option=com_audash'; 
	$app->redirect($link);
}

$document = JFactory::getDocument();
$document->setTitle($page_title);

function getRowsByStatus($status, $search, $page_num, $max_rows) {
	$db = JFactory::getDBO();
	$query = "SELECT a.`id`, a.`article_name`, a.`article_authors`, a.`key_words`, a.`anotation`, a.`path`, u.`login`, u.`user_id`, a.`etc`  
	FROM `#__cf_articles` as a, `#__cf-user_info` as u  
	WHERE ((a.`status` = {$status}) AND (u.`user_id` = a.`user_id`))";
	
	if (strlen($search)>0) {
		$query .= " AND ((a.`id` LIKE '%{$search}%') OR (a.`article_name` LIKE '%{$search}%') OR (a.`article_authors` LIKE '%{$search}%')";
		$query .= " OR (a.`key_words` LIKE '%{$search}%') OR (a.`anotation` LIKE '%{$search}%') OR (u.`login` LIKE '%{$search}%')";
		$query .= " OR (u.`user_id` LIKE '%{$search}%'))";
	}
	
	$query .= " ORDER BY a.`id` ASC";
	
	if (strlen($page_num)>0) {
		$pos = (intval($page_num) - 1) * $max_rows;
		$query .= " LIMIT {$pos} , {$max_rows}";
	}
	
	$query .= ';';
	$db->setQuery($query);
	return $db->loadRowList();
}

function echoResults($results, $status, $search, $max_rows) {
	echo "<input type='hidden' id='status_num' value='{$status}'>";
	
	$СOM_AUDASH_ARTICLENAME=JText::_('СOM_AUDASH_ARTICLENAME');
	$СOM_AUDASH_ARTICLEAUTHORS=JText::_('СOM_AUDASH_ARTICLEAUTHORS');
	$СOM_AUDASH_ARTICLEKEYS=JText::_('СOM_AUDASH_ARTICLEKEYS');
	$СOM_AUDASH_ARTICLEANN=JText::_('СOM_AUDASH_ARTICLEANN');
	$СOM_AUDASH_ARTICLELINK=JText::_('СOM_AUDASH_ARTICLELINK');
	$СOM_AUDASH_DELARTICLE = JText::_('СOM_AUDASH_DELARTICLE');
	$СOM_AUDASH_LINKTOART = JText::_('СOM_AUDASH_LINKTOART');
	$СOM_AUDASH_DELETEBTN = JText::_('СOM_AUDASH_DELETEBTN');
	$СOM_AUDASH_NOARTICLES = JText::_('СOM_AUDASH_NOARTICLES');
	
	$deleteble = ($status != 3);
	
	$rowCounter = count($results);
	if ($rowCounter>0) {
		echo "<form method='post' action='index.php?option=com_audash&task=searchArticles'>
			<input type='text' size='40' placeholder='Поиск' name='search'>
			<input type='submit' style='margin: 0 0 9px 5px;' value='Искать'>
			<input type='hidden' name='status' value='{$status}'>
		</form>";
		
		//
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*)
		FROM `#__cf_articles` as a, `#__cf-user_info` as u  
		WHERE ((a.`status` = {$status}) AND (u.`user_id` = a.`user_id`))";
		
		if (strlen($search)>0) {
			$query .= " AND ((a.`id` LIKE '%{$search}%') OR (a.`article_name` LIKE '%{$search}%') OR (a.`article_authors` LIKE '%{$search}%')";
			$query .= " OR (a.`key_words` LIKE '%{$search}%') OR (a.`anotation` LIKE '%{$search}%') OR (u.`login` LIKE '%{$search}%')";
			$query .= " OR (u.`user_id` LIKE '%{$search}%'))";
		}

		$query .= ';';
		$db->setQuery($query);
		$rowAmount = $db->loadResult();
		
		$pageAmount = intval($rowAmount / $max_rows) + ($rowAmount % $max_rows != 0 ? 1 : 0);
		//
		
		if ($pageAmount > 1) {
			echo "<div style='text-align: center; font-size: 16px; margin-bottom: 10px;'>Страницы: ";
			for ($z = 1; $z <= $pageAmount; $z++) {
				$link = 'index.php?option=com_audash&view=lists'.'&status='.$status.'&search='.$search.'&page='.$z;
				echo "<a style='margin: 0 10px;' href='{$link}'>{$z}</a>";
			}
			echo "</div>";
		}
		
		$width = ($deleteble ? 12.5 : 14.285714).'%';
		echo "<table border='0'>";
		echo "<tr>
				<th>Опубликовано</th>
				<th>{$СOM_AUDASH_ARTICLENAME}</th>
				<th>{$СOM_AUDASH_ARTICLEAUTHORS}</th>
				<th>{$СOM_AUDASH_ARTICLEKEYS}</th>
				<th>{$СOM_AUDASH_ARTICLEANN}</th>
				<th>{$СOM_AUDASH_ARTICLELINK}</th>";
				if ($deleteble) {
					echo "<th>Установка статуса</th>";
					echo "<th>{$СOM_AUDASH_DELARTICLE}</th>";
				}
		echo "</tr>";
		foreach ($results as $subArray) {
			echo "<tr title='{$subArray[8]}'>";
				echo "<td width='{$width}' align='center'><a target='_blank' href='index.php?option=com_audash&view=profile&userId={$subArray[7]}'>{$subArray[6]}</a></td>";
				for ($i = 1; $i <= 4; $i++) {
					echo "<td width='{$width}'><div style='max-height: 150px; overflow-y:auto;'>{$subArray[$i]}</div></td>";
				}
				echo "<td width='{$width}' align='center'><a href='{$subArray[5]}'>{$СOM_AUDASH_LINKTOART}</a></td>";
				if ($deleteble) {
					$stats = array('На обработку','Готова к публикации','Отклонить','Опубликовать');
					echo "<td width='{$width}' align='center'><select class='select-status' name='artID_{$subArray[0]}' style='width:160px;'>";
					for ($j = 0; $j <= 3; $j++) {
						echo "<option value='{$j}'";
						if ($j == $status) {
							echo "selected style='background-color: green; color: white;'";
						}
						echo ">{$stats[$j]}</option>";
					}
					echo "</select></td>";
					$routed_link = JRoute::_('index.php?option=com_audash&task=deleteArticle');
					echo "<td width='{$width}' align='center'>
							<form class='article_item' action='{$routed_link}' method='post'>
								<input type='hidden' name='articleId' value='{$subArray[0]}'>
								<input type='submit' value={$СOM_AUDASH_DELETEBTN} onclick='return confirm(ask)'>
							</form>
						</td>";
				}
			echo "</tr>";
		}
		echo "</table>";
		
		if ($deleteble) {
			echo "<button style='margin-top:10px;' id='update-stats'>Изменить статусы</button>";
		}
	} else {
		echo "<p class='vertical-align' align='center' style='margin-top: 10px;'><i>{$СOM_AUDASH_NOARTICLES}</i></p>";
	}
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
    padding: 8px;
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

#cover-html {
   position: absolute;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   opacity: 0.80;
   background: #aaa;
   z-index: 9998;
   display: none;
}

#modal-return {
	
	position: absolute;
	right: 50%;
	top: 20%;
	transform: translateX(50%);
	background: white;
	z-index: 9999;
	display: none;
	padding: 10px;
}
</style>
<script>
	var ask = 'Вы действительно хотите удалить статью?';
</script>
<h1 align='center'><?php echo $page_title ?></h1>
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
		<?php
			$results = getRowsByStatus($status, $search, $page_num, $max_rows);
			echoResults($results, $status, $search, $max_rows);
		?>
	</article>
</div>
<div id="cover-html"></div>
<div id="modal-return">
	<h3 align='center'>Отклонение статьи</h3>
	<p align='center'>Укажите причину(ы). Они будут направлены на почту пользователя <span id="p-modal-user"></snap></p>
	<hr style="margin: 5px 0;">
	<h4 align='center' id="h4-modal-info"></h4>
	<form method='post' action='index.php?option=com_audash&task=returningArticle'>
		<textarea required id="txtar" name='why' style="resize: none; height: 225px;" placeholder="Содержание письма"></textarea>
		<input type='hidden' value='' name='articleId' id='art-id-ret-form'>
		<br>
		<button id='btn-cnsl' type="button">Отмена</button>
		<input type='submit' value='Отклонить'>
	</form>
</div>
<script>
	if (document.getElementById('update-stats') !== null) {
		document.getElementById('update-stats').onclick = function() {
			document.getElementById('cover-html').style.display = 'block';
			
			let stats = document.getElementsByClassName('select-status');
			var result = {};
			
			var prop = 'artID_';
			Array.from(stats).forEach(el => {
				let name_num = el.name.match(/\d+/ig);
				if (el.name == prop + (name_num === null ? '' : name_num[0]))
					if ((el.value >= 0) && (el.value <= 3))
						result[el.name] = el.value;
			});
			var json_res = JSON.stringify(result),
				request = new XMLHttpRequest();
				
			request.onreadystatechange = function() {
				if (request.readyState == 4 && request.status == 200) {
					//console.log(request.responseText);
					window.location.reload();
				}
			}
			
			request.open('POST','index.php?option=com_audash&task=changeArticlesStatus');
			request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			request.send('json='+json_res);
		}
		
		Array.from(document.getElementsByClassName('select-status'))
			.map(el => el.onchange = function(e) {
				if (e.target.value == 2) {
					document.getElementById('cover-html').style.display = 'block';
					var modal_return = document.getElementById('modal-return');
					modal_return.style.display = 'block';
					
					var link_user = e.target.parentNode.parentNode.firstElementChild.firstElementChild;
					var title = e.target.parentNode.parentNode.children[1].firstElementChild.innerHTML,
						authors = e.target.parentNode.parentNode.children[2].firstElementChild.innerHTML;
					
					document.getElementById('p-modal-user').innerHTML = link_user.outerHTML;
					document.getElementById('h4-modal-info').innerHTML = title + '; '+authors;
					document.getElementById('txtar').style.width = modal_return.offsetWidth - 20 + 'px';
					
					var artID = e.target.parentNode.parentNode.lastElementChild.firstElementChild.firstElementChild.value;
					document.getElementById('art-id-ret-form').value = artID;
					
					//закрытие модалки в "молоке"
					document.getElementById('cover-html').onclick = function(ev) {
						ev.target.style.display = 'none';
						modal_return.style.display = 'none';
						e.target.value = document.getElementById('status_num').value;
					}
					
					//закрытие модалки кнопкой в модалке
					document.getElementById('btn-cnsl').onclick = function(ev) {
						document.getElementById('cover-html').style.display = 'none';
						modal_return.style.display = 'none';
						e.target.value = document.getElementById('status_num').value;
					}
				}
			})
	}
</script>