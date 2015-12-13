<?php
	session_start();
    
    if ($_SESSION['auth_admin'] == "yes_auth")
    {
define('myyshop', true);
    
    if (isset($_GET["logout"]))
    {
        unset($_SESSION["auth_admin"]);
        header("Location: login.php");
    }
    
    $_SESSION['urlpage'] = "<a href='index.php'>Главная</a> \ <a href='news.php'>Новости</a>";
    
    include("include/db_connect.php");
    include("include/functions.php");
    
    if($_POST["submit_news"])
    {
        if($_POST["news_title"] == "" || $_POST["news_text"] == "")
        {
            $message = "<p id='form-error'>Заполните все поля</p>";
        }
        else
        {
            mysql_query("INSERT INTO news(title,text,date)
                                VALUES(
                                    '".$_POST["news_title"]."',
                                    '".$_POST["news_text"]."',
                                    NOW()
                                    )",$link);
            $message = "<p id='form-success'>Новость добавлена</p>";
        }
    }
    
                 
$id = clear_string($_GET["id"]);
$action = $_GET["action"];
if (isset($action))
{
   switch ($action) {
        
        case 'delete':      

         $delete = mysql_query("DELETE FROM news WHERE id = '$id'",$link);      
                    
	    break;
        
	} 
    
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="jquery_confirm/jquery_confirm.css" rel="stylesheet" type="text/css" /> 
    <link href="fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />      
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script> 
    <script type="text/javascript" src="js/script.js"></script> 
    <script type="text/javascript" src="jquery_confirm/jquery_confirm.js"></script>  
    <script type="text/javascript" src="fancybox/jquery.fancybox.js"></script>    

<script type="text/javascript">
$(document).ready(function(){
    $(".news").fancybox();
});
</script>

	<title>Панель управления - Новости</title>
</head>

<body>

<div id="block-body">
<?php
	include("include/block-header.php");
    $all_count = mysql_query("SELECT * FROM news",$link);
    $result_count = mysql_num_rows($all_count);
?>
<div id="block-content">
<div id="block-parameters">
<p id="count-clients">Всего новостей - <strong><?php echo $result_count;?></strong></p>

<p align="right" id="add-style"><a class="news" href="#news">Добавить новость</a></p>
</div>

<?php
if($message != "") echo $message;
	$result = mysql_query("SELECT * FROM news ORDER BY id DESC",$link);
    
    if(mysql_num_rows($result) > 0)
    {
        $row = mysql_fetch_array($result);
        do
        {
            echo '
            
            <div class="block-news">
            
            <h3>'.$row["title"].'</h3>
            <span>'.$row["date"].'</span>
            <p>'.$row["text"].'</p>
            
            <p class="link-action" align="right"><a class="delete" rel="news.php?id='.$row["id"].'&action=delete">Удалить</a></p>
            
            </div>
            
            ';
        } while ($row = mysql_fetch_array($result));
    }
?>
<div id="news">
<form method="post">
<div id="block-input">
<label>Заголовок <input type="text" name="news_title" /></label>
<label>Описание <textarea name="news_text" ></textarea></label>
</div>
<p align="right">
<input type="submit" name="submit_news" id="submit_news" value="Добавить" />
</p>
</form>
</div>
</div>
</div>
</body>
</html>

<?php
} else
{
    header("Location: login.php");
}	
?>
    