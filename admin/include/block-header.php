<?php
	defined('myyshop') or die('Доступа нет');
?>
<div id="block-header">

<div id="block-header1">
<h3>E-Shop. Панель управления</h3>
<p id="link-nav"><?php echo $_SESSION['urlpage'];?></p>
</div>

<div id="block-header2">
<p align="right"><a href="administrators.php">Администраторы</a> | <a href="?logout">Выход</a></p>
<p align="right">Вы - <span>1234</span></p>
</div>

</div>

<div id="left-nav">
<ul>
<li><a href="orders.php">Заказы</a></li>
<li><a href="tovar.php">Товары</a></li>
<li><a href="reviews.php">Отзывы</a></li>
<li><a href="category.php">Категории</a></li>
<li><a href="clients.php">Клиенты</a></li>
<li><a href="news.php">Новости</a></li>
</ul>
</div>