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
    
    $_SESSION['urlpage'] = "<a href='index.php'>�������</a> \ <a href='reviews.php'>������</a>";
    
    include("include/db_connect.php");
    include("include/functions.php"); 
    
    $id = clear_string($_GET["id"]);
    $sort = $_GET["sort"];
    
    switch ($sort) {
        
        case 'accept':
        
        $sort = "moderat='1' DESC";
        $sort_name = '�����������';
        
        break;
        
        case 'no-accept':
        
        $sort = "moderat='0' DESC";
        $sort_name = '�� �����������';
        
        break;
        
        default:
        
        $sort = "reviews_id DESC";
        $sort_name = '��� ����������';
        
        break;
    }
    
    $action = $_GET["action"];
    if(isset($action))
    {
        switch ($action) {
            case 'accept':
            
            if($_SESSION['accept_reviews'] == '1')
            {
                $update = mysql_query("UPDATE table_reviews SET moderat='1' WHERE reviews_id='$id'",$link);
            }else
            {
                $msgerror = '� ��� ��� ���� �� ��������� �������';
            }
            
            break;
            
            case 'delete':
            
            if($_SESSION['delete_reviews'] == '1')
            {
                $delete = mysql_query("DELETE FROM table_reviews WHERE reviews_id='$id'",$link);
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

	<title>������ ���������� - ������</title>
</head>

<body>

<div id="block-body">
<?php
	include("include/block-header.php");   
    
    $all_count = mysql_query("SELECT * FROM table_reviews",$link); 
    $all_count_result = mysql_num_rows($all_count);
    
    $no_accept_count = mysql_query("SELECT * FROM table_reviews WHERE moderat = '0'",$link);
    $no_accept_count_result = mysql_num_rows($no_accept_count);
?>
<div id="block-content">

<div id="block-parameters">

<ul id="options-list">
<li>�����������</li>
<li><a id="select-links" href="#"><? echo $sort_name; ?></a>

<ul id="list-links-sort">

<li><a href="reviews.php?sort=accept">�����������</a></li>
<li><a href="reviews.php?sort=no-accept">�� �����������</a></li>
</ul>

</li>
</ul>
</div>

<div id="block-info">

<ul id="review-info-count">
<li>����� ������� - <strong><?php echo $all_count_result;?></strong></li>
<li>�� ����������� - <strong><?php echo $no_accept_count_result;?></strong></li>
</ul>
</div>

<?php
if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';

    $num = 4;

    $page = strip_tags($_GET['page']);              
    $page = mysql_real_escape_string($page);

$count = mysql_query("SELECT COUNT(*) FROM table_reviews",$link);
$temp = mysql_fetch_array($count);
$post = $temp[0];
// ������� ����� ����� �������
$total = (($post - 1) / $num) + 1;
$total =  intval($total);
// ���������� ������ ��������� ��� ������� ��������
$page = intval($page);
// ���� �������� $page ������ ������� ��� ������������
// ��������� �� ������ ��������
// � ���� ������� �������, �� ��������� �� ���������
if(empty($page) or $page < 0) $page = 1;
  if($page > $total) $page = $total;
// ��������� ������� � ������ ������
// ������� �������� ���������
$start = $page * $num - $num;

$result = mysql_query("SELECT * FROM table_reviews,table_products WHERE table_products.products_id = table_reviews.products_id ORDER BY $sort LIMIT $start, $num",$link);
 
 If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do
{
    if  (strlen($row["image"]) > 0 && file_exists("../uploads_images/".$row["image"]))
{
$img_path = "../uploads_images/".$row["image"];
$max_width = 150; 
$max_height = 150; 
 list($width, $height) = getimagesize($img_path); 
$ratioh = $max_height/$height; 
$ratiow = $max_width/$width; 
$ratio = min($ratioh, $ratiow); 
// New dimensions 
$width = intval($ratio*$width); 
$height = intval($ratio*$height);    
}else
{
$img_path = "/images/no-image.png";
$width = 100;
$height = 182;
}

if ($row["moderat"] == '0'){ $link_accept = '<a class="green" href="reviews.php?id='.$row["reviews_id"].'&action=accept" >�������</a> | ';  } else { $link_accept = '';  }
    
 echo '
 <div class="block-reviews">
 <div class="block-title-img">
 <p>'.$row["title"].'</p>
 <center>
 <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />
 </center>
 </div> 
<p class="author-date"><strong>'.$row["name"].'</strong>, '.$row["date"].'</p>
<div class="plus-minus">
            <img src="./images/plus16.png"/><p>'.$row["good_reviews"].'</p>          
            <img src="./images/minus16.png"/><p>'.$row["bad_reviews"].'</p>
</div>  

<p class="reviews-comment" >'.$row["comment"].'</p>          
 <p class="links-actions" align="right" >'.$link_accept.'<a class="delete" rel="reviews.php?id='.$row["reviews_id"].'&action=delete" >�������</a> </p>
 </div>
 ';   
    
} while ($row = mysql_fetch_array($result));
}   
   
if ($page != 1) $pervpage = '<li><a class="pstr-prev" href="reviews.php?page='. ($page - 1) .'" />�����</a></li>';

if ($page != $total) $nextpage = '<li><a class="pstr-next" href="reviews.php?page='. ($page + 1) .'"/>�����</a></li>';

// ������� ��� ��������� ������� � ����� �����, ���� ��� ����
if($page - 5 > 0) $page5left = '<li><a href="reviews.php?page='. ($page - 5) .'">'. ($page - 5) .'</a></li>';
if($page - 4 > 0) $page4left = '<li><a href="reviews.php?page='. ($page - 4) .'">'. ($page - 4) .'</a></li>';
if($page - 3 > 0) $page3left = '<li><a href="reviews.php?page='. ($page - 3) .'">'. ($page - 3) .'</a></li>';
if($page - 2 > 0) $page2left = '<li><a href="reviews.php?page='. ($page - 2) .'">'. ($page - 2) .'</a></li>';
if($page - 1 > 0) $page1left = '<li><a href="reviews.php?page='. ($page - 1) .'">'. ($page - 1) .'</a></li>';

if($page + 5 <= $total) $page5right = '<li><a href="reviews.php?page='. ($page + 5) .'">'. ($page + 5) .'</a></li>';
if($page + 4 <= $total) $page4right = '<li><a href="reviews.php?page='. ($page + 4) .'">'. ($page + 4) .'</a></li>';
if($page + 3 <= $total) $page3right = '<li><a href="reviews.php?page='. ($page + 3) .'">'. ($page + 3) .'</a></li>';
if($page + 2 <= $total) $page2right = '<li><a href="reviews.php?page='. ($page + 2) .'">'. ($page + 2) .'</a></li>';
if($page + 1 <= $total) $page1right = '<li><a href="reviews.php?page='. ($page + 1) .'">'. ($page + 1) .'</a></li>';

if ($page+5 < $total)
{
    $strtotal = '<li><p class="nav-point">...</p></li><li><a href="reviews.php?page='.$total.'">'.$total.'</a></li>';
}else
{
    $strtotal = ""; 
}

if ($total > 1)
{
    echo '
    <center>
    <div class="pstrnav">
    <ul>
    ';
    echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left."<li><a class='pstr-active' href='reviews.php?page=".$page."'>".$page."</a></li>".$page1right.$page2right.$page3right.$page4right.$page5right.$strtotal.$nextpage;
    echo '
    </ul>
    </div>
    </center>    
    ';
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