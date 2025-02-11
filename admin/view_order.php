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
    
    $_SESSION['urlpage'] = "<a href='index.php'>�������</a> \ <a href='view_order.php'>�������� �������</a>";
    
    include("include/db_connect.php");
    include("include/functions.php"); 
    
    $id = clear_string($_GET["id"]);
    $action = $_GET["action"];
  if (isset($action))
{
   switch ($action) {

	    case 'accept':
        
        if($_SESSION['accept_orders'] == '1')
        {
            $update = mysql_query("UPDATE orders SET order_confirmed='yes' WHERE order_id = '$id'",$link);
        }else
        {
            $msgerror = '� ��� ��� ���� �� �������������� �������';
        }   
              
     	 break;
        
        case 'delete':        
        
        if($_SESSION['delete_orders'] == '1')
        {
           $delete = mysql_query("DELETE FROM orders WHERE order_id = '$id'",$link); 
           header("Location: orders.php"); 
        }else
        {
            $msgerror = '� ��� ��� ���� �� �������� �������';
        }
        
                
        
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
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
    <script type="text/javascript" src="js/script.js"></script> 
    <script type="text/javascript" src="jquery_confirm/jquery_confirm.js"></script> 

	<title>������ ���������� - �������� �������</title>
</head>

<body>

<div id="block-body">
<?php
	include("include/block-header.php");     
?>
<div id="block-content">

<div id="block-parameters">
<p id="title-page">�������� ������</p>
</div>

<?php

if(isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';

if($_SESSION['view_orders'] == '1')
{

    $result = mysql_query("SELECT * FROM orders WHERE order_id = '$id'",$link);
 
 If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do
{
if ($row["order_confirmed"] == 'yes')
{
    $status = '<span class="green">���������</span>';
} else
{
    $status = '<span class="red">�� ���������</span>';    
}

 echo '
  <p class="view-order-link" ><a class="green" href="view_order.php?id='.$row["order_id"].'&action=accept" >����������� �����</a> | <a class="delete" rel="view_order.php?id='.$row["order_id"].'&action=delete" >������� �����</a></p>
  <p class="order-datetime" >'.$row["order_datetime"].'</p>
  <p class="order-number" >����� � '.$row["order_id"].' - '.$status.'</p>

<TABLE align="center" CELLPADDING="10" WIDTH="100%">
<TR>
<TH>�</TH>
<TH>������������ ������</TH>
<TH>����</TH>
<TH>����������</TH>
</TR>
';
$query_product = mysql_query("SELECT * FROM buy_products,table_products WHERE buy_products.buy_id_order = '$id' AND table_products.products_id = buy_products.buy_id_product",$link);
 
$result_query = mysql_fetch_array($query_product);
do
{
$price = $price + ($result_query["price"] * $result_query["buy_count_product"]);    
$index_count =  $index_count + 1; 
echo '
 <TR>
<TD  align="CENTER" >'.$index_count.'</TD>
<TD  align="CENTER" >'.$result_query["title"].'</TD>
<TD  align="CENTER" >'.$result_query["price"].' ���</TD>
<TD  align="CENTER" >'.$result_query["buy_count_product"].'</TD>
</TR>

';
} while ($result_query = mysql_fetch_array($query_product));


if ($row["order_pay"] == "accepted")
{
    $statpay = '<span class="green">��������</span>';
}else
{
    $statpay = '<span class="red">�� ��������</span>';
}

echo '
</TABLE>
<ul id="info-order">
<li>����� ���� - <span>'.$price.'</span> ���</li>
<li>������ �������� - <span>'.$row["order_dostavka"].'</span></li>
<li>������ ������ - '.$statpay.'</li>
<li>��� ������ - <span>'.$row["order_type_pay"].'</span></li>
<li>���� ������ - <span>'.$row["order_datetime"].'</span></li>
</ul>


<TABLE align="center" CELLPADDING="10" WIDTH="100%">
<TR>
<TH>���</TH>
<TH>�����</TH>
<TH>��������</TH>
<TH>����������</TH>
</TR>

 <TR>
<TD  align="CENTER" >'.$row["order_fio"].'</TD>
<TD  align="CENTER" >'.$row["order_address"].'</TD>
<TD  align="CENTER" >'.$row["order_phone"].'</br>'.$row["order_email"].'</TD>
<TD  align="CENTER" >'.$row["order_note"].'</TD>
</TR>
</TABLE>

 ';   
    
} while ($row = mysql_fetch_array($result));
}

} else
{
    echo '<p id="form-error" align="center">� ��� ��� ���� �� �������� ������� �������</p>';
}
?>

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