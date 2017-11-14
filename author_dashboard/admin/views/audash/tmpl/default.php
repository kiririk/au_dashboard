<?php
defined('_JEXEC') or die('Restricted access');
echo "<h1 align='center'>Списки статей</h1>";
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
	flex-basis: 20%;
}

article {
	margin-left: 10px;
	flex-basis: 80%;
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
</style>
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
		<h3 class='vertical-align' align='center'>Добро пожаловать в административную панель личного кабинета авторов</h3>
	</article>
</div>