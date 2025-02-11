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
    
    $_SESSION['urlpage'] = "<a href='index.php'>�������</a> \ <a href='add_administrators.php'>���������� ��������������</a>";
    
    include("include/db_connect.php");
    include("include/functions.php");  
    
    if ($_POST["submit_add"])
{
    if ($_SESSION['auth_admin_login'] == 'admin3')
    {

    $error = array();
    
    if ($_POST["admin_login"])
    {
        $login = clear_string($_POST["admin_login"]);
        $query = mysql_query("SELECT login FROM reg_admin WHERE login='$login'",$link);
 
     If (mysql_num_rows($query) > 0)
     {
        $error[] = "����� �����!";
     }
        
        
    }else
    {
        $error[] = "������� �����!";
    }
    
     
    if (!$_POST["admin_pass"]) $error[] = "������� ������!";
    if (!$_POST["admin_fio"]) $error[] = "������� ���!";
    if (!$_POST["admin_role"]) $error[] = "������� ���������!";
    if (!$_POST["admin_email"]) $error[] = "������� E-mail!";

 if (count($error))
 {
    $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>";
 }else
 {
    $pass   = md5(clear_string($_POST["admin_pass"]));
    $pass   = strrev($pass);
    $pass   = strtolower("mb03foo51".$pass."qj2jjdp9");
    
                  		mysql_query("INSERT INTO reg_admin(login,pass,fio,role,email,phone,view_orders,accept_orders,delete_orders,add_tovar,edit_tovar,delete_tovar,accept_reviews,delete_reviews,view_clients,delete_clients,add_news,delete_news,add_category,delete_category,view_admin)
						VALUES(						
                            '".clear_string($_POST["admin_login"])."',
                            '".$pass."',
                            '".clear_string($_POST["admin_fio"])."',
                            '".clear_string($_POST["admin_role"])."',
                            '".clear_string($_POST["admin_email"])."',
                            '".clear_string($_POST["admin_phone"])."',
                            '".$_POST["view_orders"]."',
                            '".$_POST["accept_orders"]."',
                            '".$_POST["delete_orders"]."',							
                            '".$_POST["add_tovar"]."',
                            '".$_POST["edit_tovar"]."',                            
							'".$_POST["delete_tovar"]."',
                            '".$_POST["accept_reviews"]."',
                            '".$_POST["delete_reviews"]."',
							'".$_POST["view_clients"]."',
                            '".$_POST["delete_clients"]."',
							'".$_POST["add_news"]."',							
							'".$_POST["delete_news"]."',
							'".$_POST["add_category"]."',
							'".$_POST["delete_category"]."',
                            '".$_POST["view_admin"]."'
                            
                                                                                                                                                
						)",$link);
                   
          $_SESSION['message'] = "<p id='form-success'>������������ ������� ��������!</p>";
 } 
 }  else
    {
       $msgerror = '� ��� ��� ���� �� ���������� ���������������!'; 
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
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
    <script type="text/javascript" src="js/script.js"></script> 
    <script type="text/javascript" src="jquery_confirm/jquery_confirm.js"></script>     

	<title>������ ���������� - ���������� ��������������</title>
</head>

<body>

<div id="block-body">
<?php
	include("include/block-header.php");
?>
<div id="block-content">
<div id="block-parameters">
<p id="title-page">���������� ��������������</p>
</div>

<?php
if(isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';
	if(isset($_SESSION['message']))
    {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
?>

<form method="post" id="form-info" >

<ul id="info-admin">
<li><label>�����</label><input type="text" name="admin_login" value="<?php echo $_POST["admin_login"];?>"/></li>
<li><label>������</label><input type="password" name="admin_pass" value="<?php echo $_POST["admin_pass"];?>"/></li>
<li><label>���</label><input type="text" name="admin_fio" value="<?php echo $_POST["admin_fio"];?>"/></li>
<li><label>���������</label><input type="text" name="admin_role" value="<?php echo $_POST["admin_role"];?>"/></li>
<li><label>E-mail</label><input type="text" name="admin_email" value="<?php echo $_POST["admin_email"];?>"/></li>
<li><label>�������</label><input type="text" name="admin_phone" value="<?php echo $_POST["admin_phone"];?>"/></li>
</ul>

<h3 id="title-privilege" >����������</h3>

<p id="link-privilege"><a id="select-all" >������� ���</a> | <a id="remove-all" >����� ���</a></p>

<div class="block-privilege">

<ul class="privilege">
<li><h3>������</h3></li>

<li>
<input type="checkbox" name="view_orders" id="view_orders" value="1" />
<label for="view_orders">�������� �������.</label>
</li>

<li>
<input type="checkbox" name="accept_orders" id="accept_orders" value="1" />
<label for="accept_orders">��������� �������.</label>
</li>

<li>
<input type="checkbox" name="delete_orders" id="delete_orders" value="1" />
<label for="delete_orders">�������� �������.</label>
</li>

</ul>
<ul class="privilege">
<li><h3>������</h3></li>

<li>
<input type="checkbox" name="add_tovar" id="add_tovar" value="1" />
<label for="add_tovar">���������� �������.</label>
</li>

<li>
<input type="checkbox" name="edit_tovar" id="edit_tovar" value="1" />
<label for="edit_tovar">��������� �������.</label>
</li>

<li>
<input type="checkbox" name="delete_tovar" id="delete_tovar" value="1" />
<label for="delete_tovar">�������� �������.</label>
</li>

</ul>

<ul class="privilege">
<li><h3>������</h3></li>

<li>
<input type="checkbox" name="accept_reviews" id="accept_reviews" value="1" />
<label for="accept_reviews">��������� �������.</label>
</li>

<li>
<input type="checkbox" name="delete_reviews" id="delete_reviews" value="1" />
<label for="delete_reviews">�������� �������.</label>
</li>

</ul>

</div>
<div class="block-privilege">

<ul class="privilege">
<li><h3>�������</h3></li>

<li>
<input type="checkbox" name="view_clients" id="view_clients" value="1" />
<label for="view_clients">�������� ��������.</label>
</li>

<li>
<input type="checkbox" name="delete_clients" id="delete_clients" value="1" />
<label for="delete_clients">�������� ��������.</label>
</li>

</ul>

<ul class="privilege">
<li><h3>�������</h3></li>

<li>
<input type="checkbox" name="add_news" id="add_news" value="1" />
<label for="add_news">���������� ��������.</label>
</li>


<li>
<input type="checkbox" name="delete_news" id="delete_news" value="1" />
<label for="delete_news">�������� ��������.</label>
</li>

</ul>

<ul class="privilege">
<li><h3>���������</h3></li>

<li>
<input type="checkbox" name="add_category" id="add_category" value="1" />
<label for="add_category">���������� ���������.</label>
</li>

<li>
<input type="checkbox" name="delete_category" id="delete_category" value="1" />
<label for="delete_category">�������� ���������.</label>
</li>

</ul>

</div>

<div class="block-privilege">

<ul class="privilege">
<li><h3>��������������</h3></li>

<li>
<input type="checkbox" name="view_admin" id="view_admin" value="1" />
<label for="view_admin">�������� ���������������.</label>
</li>

</ul>

</div>

<p align="right"><input type="submit" id="submit_form" name="submit_add" value="��������"/></p>
</form>

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
    