<?php
    define('myyshop', true);
	include("include/db_connect.php"); 
    include("functions/functions.php");    
    session_start();
    include("include/auth_cookie.php");
    //unset($_SESSION['auth']);
    
    $id = clear_string($_GET["id"]);
   
    $action = clear_string($_GET["action"]);
    
    switch ($action) {
        case 'clear':
        $clear = mysql_query("DELETE FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'", $link);
        break;
        
        case 'delete':
        $delete = mysql_query("DELETE FROM cart WHERE cart_id_product = '$id' and cart_ip = '{$_SERVER['REMOTE_ADDR']}'", $link);
        break;
    }
    
    if (isset($_POST["submitdata"]))
    {
    if ( $_SESSION['auth'] == 'yes_auth' ) 
 {
        
    mysql_query("INSERT INTO orders(order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
						VALUES(	
                             NOW(),
                            '".$_POST["order_delivery"]."',					
							'".$_SESSION['auth_surname'].' '.$_SESSION['auth_name'].' '.$_SESSION['auth_patronymic']."',
                            '".$_SESSION['auth_address']."',
                            '".$_SESSION['auth_phone']."',
                            '".$_POST['order_note']."',
                            '".$_SESSION['auth_email']."'                              
						    )",$link);         

 }else
 {
$_SESSION["order_delivery"] = $_POST["order_delivery"];
$_SESSION["order_fio"] = $_POST["order_fio"];
$_SESSION["order_email"] = $_POST["order_email"];
$_SESSION["order_phone"] = $_POST["order_phone"];
$_SESSION["order_address"] = $_POST["order_address"];
$_SESSION["order_note"] = $_POST["order_note"];

    mysql_query("INSERT INTO orders(order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
						VALUES(	
                             NOW(),
                            '".clear_string($_POST["order_delivery"])."',					
							'".clear_string($_POST["order_fio"])."',
                            '".clear_string($_POST["order_address"])."',
                            '".clear_string($_POST["order_phone"])."',
                            '".clear_string($_POST["order_note"])."',
                            '".clear_string($_POST["order_email"])."'                   
						    )",$link);    
 }

                          
 $_SESSION["order_id"] = mysql_insert_id();                          
                            
$result = mysql_query("SELECT * FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'",$link);
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);    

do{

    mysql_query("INSERT INTO buy_products(buy_id_order,buy_id_product,buy_count_product)
						VALUES(	
                            '".$_SESSION["order_id"]."',					
							'".$row["cart_id_product"]."',
                            '".$row["cart_count"]."'                   
						    )",$link);



} while ($row = mysql_fetch_array($result));
}
                            
header("Location: cart.php?action=completion");
    }
    
    $result = mysql_query("SELECT * FROM cart,table_products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND table_products.products_id = cart.cart_id_product", $link);
    if (mysql_num_rows($result) > 0)
    {
        $row = mysql_fetch_array($result);
        
        do 
        {
            $int = $int + ($row["price"] * $row["cart_count"]);
        }
        while ($row = mysql_fetch_array($result));
        
        $itogpricecart = $int;
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
    <title>������� �������</title>
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
	
    $action = clear_string($_GET["action"]);
    switch($action)
    {
        case 'oneclick':
        
        echo '        
        <div id="block-step">        
        <div id="name-step">        
        <ul>
        <li><a class="active">1. ������� �������</a></li>
        <li><span>&rarr;</span></li>
        <li><a>2. ���������� ����������</a></li>
        <li><span>&rarr;</span></li>
        <li><a>3. ����������</a></li>                
        </ul>        
        </div>        
        <p>��� 1 �� 3</p>
        <a href="cart.php?action=clear">��������</a>        
        </div>        
        ';
        
        $result = mysql_query("SELECT * FROM cart,table_products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND table_products.products_id = cart.cart_id_product",$link);
        
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            
            echo '        
        <div id="header-list-cart">        
        <div id="header1">�����������</div>
        <div id="header2">������������ ������</div>
        <div id="header3">���-��</div>
        <div id="header4">����</div>        
        </div>        
        ';
            
            do {
                $int = $row["cart_price"] * $row["cart_count"];
                $all_price = $all_price + $int;
                
                if (strlen($row["image"]) > 0 && file_exists("./uploads_images/".$row["image"])) {
                    $img_path = './uploads_images/'.$row["image"];
                    $max_width = 100;
                    $max_height = 100;
                    list($width, $height) = getimagesize($img_path);
                    $ratioh = $max_height/$height;
                    $ratiow = $max_width/$width;
                    $ratio = min($ratioh, $ratiow);
                    
                    $width = intval($ratio*$width);
                    $height = intval($ratio*$height);
                } else {
                    $img_path = "/images/noimages.jpeg";
                    $width = 120;
                    $height = 105;
                }
               
               echo '
        
        <div class="block-list-cart">
        
        <div class="img-cart">
        <p align="center"><img src="'.$img_path.'" width="'.$width.'" heigh="'.$height.'" /></p>
        </div>
        
        <div class="title-cart">
        <p><a href="">'.$row["title"].'</a></p>
        <p class="cart-mini_features">'.$row["mini_features"].'</p>
        </div>
        
        <div class="count-cart">
        <ul class="input-count-style">
        
        <li>
        <p align="center" class="count-minus" iid="'.$row["cart_id_product"].'">-</p>
        </li>
        
        <li>
        <p align="center"><input id="input-id'.$row["cart_id_product"].'"class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" iid="'.$row["cart_id_product"].'"/></p>
        </li>
        
        <li>
        <p align="center" class="count-plus" iid="'.$row["cart_id_product"].'">+</p>
        </li>
        
        </ul>
        </div>
        
        <div id="tovar'.$row["cart_id_product"].'" class="price-product"><h5><span class="span-count">'.$row["cart_count"].'</span> x <span>'.$row["cart_price"].'</span></h5><p price="'.$row["cart_price"].'">'.group_numerals($int).' ���</p></div>
        <div class="delete-cart"><a href="cart.php?id='.$row["cart_id_product"].'&action=delete"><img src="/images/bsk_item_del.png"/></a></div>
        
        <div id="bottom-cart-line"></div>
        </div> 
        
        
        ';
                
            }
            while ($row = mysql_fetch_array($result));
            
            echo '
            <h2 class="itog-price" align="right">�����: <strong>'.group_numerals($all_price).'</strong> ���</h2>
            <p align="right" class="button-next" ><a href="cart.php?action=confirm">�����</a></p>            
            ';
        }
        else
        {
            echo '<h3 id="clear-cart" align="center">������� �����</h3>';
        }               
        
        break;
        
        case 'confirm':
        
        echo '        
        <div id="block-step">        
        <div id="name-step">        
        <ul>
        <li><a href="cart.php?action=oneclick">1. ������� �������</a></li>
        <li><span>&rarr;</span></li>
        <li><a class="active">2. ���������� ����������</a></li>
        <li><span>&rarr;</span></li>
        <li><a>3. ����������</a></li>                
        </ul>        
        </div>        
        <p>��� 2 �� 3</p>               
        </div>        
        ';
        
        $chck = "";
        if ($_SESSION['order_delivery'] == "�� �����") $chck1 = "checked";
        if ($_SESSION['order_delivery'] == "��������") $chck2 = "checked";
        if ($_SESSION['order_delivery'] == "���������") $chck3 = "checked";
        
        echo '
        
        <h3 class="title-h3">������� ��������:</h3>
        <form method="post">
        <ul id="info-radio">
        <li>
        <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery1" value="�� �����" '.$chck1.' />
        <label class="label_delivery" for="order_delivery1">�� �����</label>
        </li>
        <li>
        <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery2" value="��������" '.$chck2.' />
        <label class="label_delivery" for="order_delivery2">��������</label>
        </li>
        <li>
        <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery3" value="���������" '.$chck3.' />
        <label class="label_delivery" for="order_delivery3">���������</label>
        </li>       
        </ul>
        <h3 class="title-h3" >���������� ��� ��������:</h3>
        <ul id="info-order">
        ';
        
        if ($_SESSION['auth'] != 'yes_auth')
        {
            echo '
            
            <li><label for="order_fio"><span>*</span>���</label><input type="text" name="order_fio" id="order_fio" value="'.$_SESSION["order_fio"].'" /><span class="order_span_style">������: ������ ���� ��������</span></li>
            <li><label for="order_email"><span>*</span>E-mail</label><input type="text" name="order_email" id="order_email" value="'.$_SESSION["order_email"].'" /><span class="order_span_style" >������: ivanov@mail.ru</span></li>
            <li><label for="order_phone"><span>*</span>�������</label><input type="text" name="order_phone" id="order_phone" value="'.$_SESSION["order_phone"].'" /><span class="order_span_style" >������: 8 950 100 12 34</span></li>
            <li><label class="order_label_style" for="order_address"><span>*</span>�����<br /> ��������</label><input type="text" name="order_address" id="order_address" value="'.$_SESSION["order_address"].'" /><span>������: �. �������,<br /> ��. �. ������� � 51�, � 305</span></li>            
            ';
        }
        echo '
        <li><label class="order_label_style" for="order_note">����������</label><textarea name="order_note" >'.$_SESSION["order_note"].'</textarea><span>�������� ���������� � ������</span></li>
        </ul>
        <p align="right"><input type="submit" name="submitdata" id="confirm-button-next" value="�����" /></p>
        </form>
        ';
        
        break;
        
        case 'completion':
        
        echo '        
        <div id="block-step">        
        <div id="name-step">        
        <ul>
        <li><a href="cart.php?action=oneclick">1. ������� �������</a></li>
        <li><span>&rarr;</span></li>
        <li><a href="cart.php?action=confirm">2. ���������� ����������</a></li>
        <li><span>&rarr;</span></li>
        <li><a class="active">3. ����������</a></li>                
        </ul>        
        </div>        
        <p>��� 3 �� 3</p>                
        </div> 
        <h3>�������� ����������</h3>       
        ';
        
        if ($_SESSION["auth"] == 'yes_auth')
        {
            echo '
            <ul id="list-info">
            <li><strong>������ ��������: </strong>'.$_SESSION['order_delivery'].'</li>
            <li><strong>Email: </strong>'.$_SESSION['auth_email'].'</li>
            <li><strong>���: </strong>'.$_SESSION['auth_surname'].' '.$_SESSION['auth_name'].' '.$_SESSION['auth_patronymic'].'</li>
            <li><strong>����� ��������: </strong>'.$_SESSION['auth_address'].'</li>
            <li><strong>�������: </strong>'.$_SESSION['auth_phone'].'</li>
            <li><strong>����������: </strong>'.$_SESSION['order_note'].'</li>            
            </ul>
            ';
        }else{
            echo '
           <ul id="list-info">
            <li><strong>������ ��������: </strong>'.$_SESSION['order_delivery'].'</li>
            <li><strong>Email: </strong>'.$_SESSION['order_email'].'</li>
            <li><strong>���: </strong>'.$_SESSION['order_fio'].'</li>
            <li><strong>����� ��������: </strong>'.$_SESSION['order_address'].'</li>
            <li><strong>�������: </strong>'.$_SESSION['order_phone'].'</li>
            <li><strong>����������: </strong>'.$_SESSION['order_note'].'</li>            
            </ul>
            ';
        }
        
        echo '
            <h2 class="itog-price" align="right">�����: <strong>'.$itogpricecart.'</strong> ���</h2>
            <p align="right" class="button-next" ><a href="">��������</a></p>            
            ';
        
        break;
        
        default:
        
        echo '        
        <div id="block-step">        
        <div id="name-step">        
        <ul>
        <li><a class="active">1. ������� �������</a></li>
        <li><span>&rarr;</span></li>
        <li><a>2. ���������� ����������</a></li>
        <li><span>&rarr;</span></li>
        <li><a>3. ����������</a></li>                
        </ul>        
        </div>        
        <p>��� 1 �� 3</p>
        <a href="cart.php?action=clear">��������</a>        
        </div>        
        ';
        
        $result = mysql_query("SELECT * FROM cart,table_products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND table_products.products_id = cart.cart_id_product",$link);
        
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            
            echo '        
        <div id="header-list-cart">        
        <div id="header1">�����������</div>
        <div id="header2">������������ ������</div>
        <div id="header3">���-��</div>
        <div id="header4">����</div>        
        </div>        
        ';
            
            do {
                $int = $row["cart_price"] * $row["cart_count"];
                $all_price = $all_price + $int;
                
                if (strlen($row["image"]) > 0 && file_exists("./uploads_images/".$row["image"])) {
                    $img_path = './uploads_images/'.$row["image"];
                    $max_width = 100;
                    $max_height = 100;
                    list($width, $height) = getimagesize($img_path);
                    $ratioh = $max_height/$height;
                    $ratiow = $max_width/$width;
                    $ratio = min($ratioh, $ratiow);
                    
                    $width = intval($ratio*$width);
                    $height = intval($ratio*$height);
                } else {
                    $img_path = "/images/noimages.jpeg";
                    $width = 120;
                    $height = 105;
                }
               
               echo '
        
        <div class="block-list-cart">
        
        <div class="img-cart">
        <p align="center"><img src="'.$img_path.'" width="'.$width.'" heigh="'.$height.'" /></p>
        </div>
        
        <div class="title-cart">
        <p><a href="">'.$row["title"].'</a></p>
        <p class="cart-mini_features">'.$row["mini_features"].'</p>
        </div>
        
        <div class="count-cart">
        <ul class="input-count-style">
        
        <li>
        <p align="center" class="count-minus" iid="'.$row["cart_id_product"].'">-</p>
        </li>
        
        <li>
        <p align="center"><input id="input-id'.$row["cart_id_product"].'"class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" iid="'.$row["cart_id_product"].'"/></p>
        </li>
        
        <li>
        <p align="center" class="count-plus" iid="'.$row["cart_id_product"].'">+</p>
        </li>
        
        </ul>
        </div>
        
        <div id="tovar'.$row["cart_id_product"].'" class="price-product"><h5><span class="span-count">'.$row["cart_count"].'</span> x <span>'.$row["cart_price"].'</span></h5><p price="'.$row["cart_price"].'">'.group_numerals($int).' ���</p></div>
        <div class="delete-cart"><a href="cart.php?id='.$row["cart_id_product"].'&action=delete"><img src="/images/bsk_item_del.png"/></a></div>
        
        <div id="bottom-cart-line"></div>
        </div> 
        
        
        ';
                
            }
            while ($row = mysql_fetch_array($result));
            
            echo '
            <h2 class="itog-price" align="right">�����: <strong>'.group_numerals($all_price).'</strong> ���</h2>
            <p align="right" class="button-next" ><a href="cart.php?action=confirm">�����</a></p>            
            ';
        }
        else
        {
            echo '<h3 id="clear-cart" align="center">������� �����</h3>';
        }     
        
        break;
    }
?>

</div>
<?php	
    include("include/block-footer.php");          
?>
</div>

</body>
</html>