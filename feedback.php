<?php
    define('myyshop', true);
	include("include/db_connect.php"); 
    include("functions/functions.php");    
    session_start();
    include("include/auth_cookie.php");
    //unset($_SESSION['auth']);
    
    if ($_POST["send_message"])
{
    $error = array();
    
  if (!$_POST["feed_name"]) $error[] = "������� ��� ���";  
  
  if(!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i",trim($_POST["feed_email"])))
  {
    $error[] = "������� ���������� E-mail"; 
  }
   
  if (!$_POST["feed_subject"]) $error[] = "������� ���� ������!";  
  if (!$_POST["feed_text"]) $error[] = "������� ����� ���������!";  
  
  if (strtolower($_POST["reg_captcha"]) != $_SESSION['img_captcha'])
  {
    $error[] = "�������� ��� � ��������!";
  }  
    
   
   if (count($error))
   {
     $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>";  
      
   }else
   {
    	         send_mail($_POST["feed_email"],
						       'admin@shop.ru',
						$_POST["feed_subject"],
						'��: '.$_POST["feed_name"].'<br/>'.$_POST["feed_text"]); 
    
     $_SESSION['message'] = "<p id='form-success'>���� ��������� ������� ����������!</p>";   
    
   }
    
} 
    
   
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=windows-1251"/>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="trackbar/trackbar.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="/js/jcarousellite_1.0.1.js"></script>
    <script type="text/javascript" src="/js/shop-script.js"></script>
    <script type="text/javascript" src="/js/jquery.cookie.min.js"></script>
    <script type="text/javascript" src="/trackbar/jquery.trackbar.js"></script>
    <script type="text/javascript" src="/js/TextChange.js"></script>
    <title>��������� ������� �������� �������</title>
</head>
<body>
<span id="okok"></span>
<div id="block-body">
<?php	
    include("include/block-header.php");    
?>
<div id="block-right">
<?php	
    include("include/block-category.php");
    include("include/block-parameter.php");   
    include("include/block-news.php");  
?>
</div>
<div id="block-content">

<?php
	if(isset($_SESSION['message']))
    {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
?>

<form method="post">
<div id="block-feedback">
<ul id="feedback">

<li><label>���� ���</label><input type="text" name="feed_name" value="<?php echo $_POST["feed_name"];?>"/></li>
<li><label>��� E-mail</label><input type="text" name="feed_email" value="<?php echo $_POST["feed_email"];?>"/></li>
<li><label>����</label><input type="text" name="feed_subject" value="<?php echo $_POST["feed_subject"];?>"/></li>
<li><label>����� ���������</label><textarea name="feed_text"><?php echo $_POST["feed_text"];?></textarea></li>

<li>
<label for="reg_captcha">�������� ���</label>
<div id="block-captcha">
<img src="/reg/reg_captcha.php"/>
<input type="text" name="reg_captcha" id="reg_captcha"/>
<p id="reloadcaptcha">��������</p>
</div>
</li>

</ul>
</div>
<p align="right"><input type="submit" name="send_message" id="form_submit"/></p>
</form>

</div>
<?php	
    include("include/block-random.php");   
    include("include/block-footer.php");        
?>
</div>

</body>
</html>