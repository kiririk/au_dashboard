<?php
defined('_JEXEC') or die('Restricted access');

$PROCESS = 0;
$PRESS = 1;
$RETURNED = 2;
$PUBLISHED = 3;

function getRowsByStatus($status) {
	$user = JFactory::getUser();
	$user_id = $user->get('id'); 
	$db = JFactory::getDBO();
	$query = "SELECT `id`, `article_name`, `article_authors`, `key_words`, `anotation`, `path`, `etc`  
	FROM `#__cf_articles` 
	WHERE `user_id` = {$user_id} and `status` = {$status};";
	$db->setQuery($query);
	return $db->loadRowList();
}

function echoResults($results, $button_id, $deleteble=True) {
	$СOM_AUDASH_ARTICLENAME=JText::_('СOM_AUDASH_ARTICLENAME');
	$СOM_AUDASH_ARTICLEAUTHORS=JText::_('СOM_AUDASH_ARTICLEAUTHORS');
	$СOM_AUDASH_ARTICLEKEYS=JText::_('СOM_AUDASH_ARTICLEKEYS');
	$СOM_AUDASH_ARTICLEANN=JText::_('СOM_AUDASH_ARTICLEANN');
	$СOM_AUDASH_ARTICLELINK=JText::_('СOM_AUDASH_ARTICLELINK');
	$СOM_AUDASH_DELARTICLE = JText::_('СOM_AUDASH_DELARTICLE');
	$СOM_AUDASH_LINKTOART = JText::_('СOM_AUDASH_LINKTOART');
	$СOM_AUDASH_DELETEBTN = JText::_('СOM_AUDASH_DELETEBTN');
	$СOM_AUDASH_NOARTICLES = JText::_('СOM_AUDASH_NOARTICLES');
	
	$rowCounter = count($results);
	if ($rowCounter>0) {
		echo "
		<script>
			document.getElementById('{$button_id}').textContent += ' ('+{$rowCounter}+')';
		</script>
		";
		$width = ($deleteble ? 16.666 : 20).'%';
		echo "<table border='0'>";
		echo "<tr>
				<th>{$СOM_AUDASH_ARTICLENAME}</th>
				<th>{$СOM_AUDASH_ARTICLEAUTHORS}</th>
				<th>{$СOM_AUDASH_ARTICLEKEYS}</th>
				<th>{$СOM_AUDASH_ARTICLEANN}</th>
				<th>{$СOM_AUDASH_ARTICLELINK}</th>";
				if ($deleteble) {
					echo "<th>{$СOM_AUDASH_DELARTICLE}</th>";
				}
		echo "</tr>";
		foreach ($results as $subArray) {
			echo "<tr title='{$subArray[6]}'>";
				for ($i = 1; $i <= 4; $i++) {
					echo "<td width='{$width}'><div style='max-height: 150px; overflow-y:auto;'>{$subArray[$i]}</div></td>";
				}
				echo "<td width='{$width}' align='center'><a href='{$subArray[5]}'>{$СOM_AUDASH_LINKTOART}</a></td>";
				if ($deleteble) {
					$routed_link = JRoute::_('index.php?option=com_audash&task=deleteArticle');
					echo "<td width='{$width}' align='center'>
							<form class='article_item' action='{$routed_link}' method='post'>
								<input type='hidden' name='articleId' value='{$subArray[0]}'>
								<input type='submit' value={$СOM_AUDASH_DELETEBTN} onclick='return confirm()'>
							</form>
						</td>";
				}
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "<p align='center' style='margin-top: 10px;'>{$СOM_AUDASH_NOARTICLES}</p>";
	}
}

$app = JFactory::getApplication();              
$user = JFactory::getUser();
$user_id = $user->get('id');  
if ($user_id == 0) {
	$link = 'index.php'; 
	$app->redirect($link);
} else {
	//nothing
}
?>
<style>
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}
div.tab button:hover {
    background-color: #ddd;
}
div.tab button.active {
    background-color: #ccc;
}
.tabcontent {
    display: none;
    padding: 0;
    border: 1px solid #ccc;
    border-top: none;
}
.tabcontent {
    -webkit-animation: fadeEffect 1s;
    animation: fadeEffect 1s; /* Fading effect takes 1 second */
}

@-webkit-keyframes fadeEffect {
    from {opacity: 0;}
    to {opacity: 1;}
}

@keyframes fadeEffect {
    from {opacity: 0;}
    to {opacity: 1;}
}

.article_item {
	margin: 0;
}

div.scrollable {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: auto;
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
	margin:0;
}
</style>
<h3 align="center"><?php echo JText::_('СOM_AUDASH_TITLEART'); ?></h3>
<div class="tab">
  <button id="btn-in_process" class="tablinks active" onclick="openCity(event, 'in_process')"><?php echo JText::_('СOM_AUDASH_INPROCESS'); ?></button>
  <button id="btn-in_press" class="tablinks" onclick="openCity(event, 'in_press')"><?php echo JText::_('СOM_AUDASH_INPRESS'); ?></button>
  <button id="btn-returned" class="tablinks" onclick="openCity(event, 'returned')"><?php echo JText::_('СOM_AUDASH_RETURNED'); ?></button>
  <button id="btn-published" class="tablinks" onclick="openCity(event, 'published')"><?php echo JText::_('СOM_AUDASH_PUBLISHED'); ?></button>
</div>

<div id="in_process" class="tabcontent" style="display:block;">
	<?php 
		$results = getRowsByStatus($PROCESS);
		echoResults($results, 'btn-in_process');
	?>
</div>

<div id="in_press" class="tabcontent">
  <?php 
	$results = getRowsByStatus($PRESS);
	echoResults($results, 'btn-in_press');
  ?>
</div>

<div id="returned" class="tabcontent">
  <?php 
	$results = getRowsByStatus($RETURNED);
	echoResults($results, 'btn-returned');
  ?>
</div>

<div id="published" class="tabcontent">
  <?php 
	$results = getRowsByStatus($PUBLISHED);
	echoResults($results, 'btn-published',False);
  ?>
</div>

<script>
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
