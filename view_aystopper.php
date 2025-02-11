<?php
    define('myyshop', true);
	include("include/db_connect.php"); 
    include("functions/functions.php");    
    session_start();
    include("include/auth_cookie.php");
    //unset($_SESSION['auth']);
    
    $go = clear_string($_GET["go"]);
    switch ($go) {
        case "news":
        $query_aystopper = " WHERE visible = '1' AND new = '1'";
        $name_aystopper = "������� �������";
        break;
        
        case "leaders":
        $query_aystopper = " WHERE visible = '1' AND leader = '1'";
        $name_aystopper = "������ ������";
        break;
        
        case "sale":
        $query_aystopper = " WHERE visible = '1' AND sale = '1'";
        $name_aystopper = "���������� �������";
        break;
        
        default:
        $query_aystopper = "";
        break;
    }
    
    $sorting = $_GET["sort"];
    
    switch ($sorting) {
        case 'price-asc';
        $sorting = 'price ASC';
        $sort_name = '�� ������� � �������';
        break;
        
        case 'price-desc';
        $sorting = 'price DESC';
        $sort_name = '�� ������� � �������';
        break;
        
        case 'popular';
        $sorting = 'count DESC';
        $sort_name = '����������';
        break;
        
        case 'news';
        $sorting = 'datetime DESC';
        $sort_name = '�������';
        break;
        
        case 'brand';
        $sorting = 'brand';
        $sort_name = '�� ������� � �������';
        break;
        
        default:
        $sorting = 'products_id';
        $sort_name = '��� ����������';
        break;
    };
    
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
	if ($query_aystopper != "")
    {
	$num = 6; // ����� ��������� ������� ����� �������� �������.
    $page = (int)$_GET['page'];              
    
	$count = mysql_query("SELECT COUNT(*) FROM table_products $query_aystopper",$link);
    $temp = mysql_fetch_array($count);

	If ($temp[0] > 0)
	{  
	$tempcount = $temp[0];

	// ������� ����� ����� �������
	$total = (($tempcount - 1) / $num) + 1;
	$total =  intval($total);

	$page = intval($page);

	if(empty($page) or $page < 0) $page = 1;  
       
	if($page > $total) $page = $total;
	 
	// ��������� ������� � ������ ������
    // ������� �������� ������ 
	$start = $page * $num - $num;

	$qury_start_num = " LIMIT $start, $num"; 
	}

If ($temp[0] > 0)
	{ 
?>

<div id="block-sorting">
<p id="nav-breadcrumbs"><a href="index.php">������� ��������</a> \ <span><?php echo $name_aystopper;?></span></p>
<ul id="option-list">
<li>���:</li>
<li><img id="style-grid" src="/images/icon-grid.png"/></li>
<li><img id="style-list" src="/images/icon-list.png"/></li>
<li>�����������:</li>
<li><a id="select-sort"><?php echo $sort_name;?></a>
<ul id="sorting-list">
<li><a href="view_aystopper.php?go=<?php echo $go;?>&sort=price-asc">�� ������� � �������</a></li>
<li><a href="view_aystopper.php?go=<?php echo $go;?>&sort=price-desc">�� ������� � �������</a></li>
<li><a href="view_aystopper.php?go=<?php echo $go;?>&sort=popular">����������</a></li>
<li><a href="view_aystopper.php?go=<?php echo $go;?>&sort=news">�������</a></li>
<li><a href="view_aystopper.php?go=<?php echo $go;?>&sort=brand">�� � �� �</a></li>
</ul>
</li>
</ul>
</div>

<ul id="block-tovar-grid">

<?php
	$result = mysql_query("SELECT * FROM table_products  $query_aystopper ORDER BY $sorting $qury_start_num",$link);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        
        do {
            if ($row["image"] != "" && file_exists("./uploads_images/".$row["image"])) {
                $img_path = './uploads_images/'.$row["image"];
                $max_width = 200;
                $max_height = 200;
                list($width, $height) = getimagesize($img_path);
                $ratioh = $max_height/$height;
                $ratiow = $max_width/$width;
                $ratio = min($ratioh, $ratiow);
                $width = intval($ratio*$width);
                $height = intval($ratio*$height);                
            }
            else {
                $img_path = "./images/no-image.png";
                $width = 110;
                $height = 200;                
            }
            
            //���������� �������
            $query_reviews = mysql_query("SELECT * FROM table_reviews WHERE products_id = {$row["products_id"]} AND moderat = '1'",$link);
            if ($query_reviews != ''){
            $count_reviews = mysql_num_rows($query_reviews);
            }else {$count_reviews = '0';}
            
            echo '
            <li>
            <div class="block-images-grid">
            <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'"/>
            </div>
            <p class="style-title-grid"><a href="view_content.php?id='.$row["products_id"].'">'.$row["title"].'</a></p>
            <ul class="reviews-and-counts-grid">
            <li><img src="/images/eye-icon.png"/><p>'.$row["count"].'</p></li>
            <li><img src="/images/comment-icon.png"/><p>'.$count_reviews.'</p></li>
            </ul>
            <a class="add-cart-style-grid" href="" tid="'.$row["products_id"].'"></a>
            <p class="style-price-grid"><strong>'.group_numerals($row["price"]).'</strong> ���</p>
            <div class="mini-features">
            '.$row["mini_features"].'            
            </div>
            </li>
            
            
            ';
            
        }
        while (
            $row = mysql_fetch_array($result)
            
            );
        
    }
?>
</ul>

<ul id="block-tovar-list">

<?php
	$result = mysql_query("SELECT * FROM table_products $query_aystopper ORDER BY $sorting $qury_start_num",$link);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        
        do {
            if ($row["image"] != "" && file_exists("./uploads_images/".$row["image"])) {
                $img_path = './uploads_images/'.$row["image"];
                $max_width = 150;
                $max_height = 150;
                list($width, $height) = getimagesize($img_path);
                $ratioh = $max_height/$height;
                $ratiow = $max_width/$width;
                $ratio = min($ratioh, $ratiow);
                $width = intval($ratio*$width);
                $height = intval($ratio*$height);                
            }
            else {
                $img_path = "./images/noimages80x70.png";
                $width = 80;
                $height = 70;                
            }
            
            //���������� �������
            $query_reviews = mysql_query("SELECT * FROM table_reviews WHERE products_id = {$row["products_id"]} AND moderat = '1'",$link);
            if ($query_reviews != ''){
            $count_reviews = mysql_num_rows($query_reviews);
            }else {$count_reviews = '0';}
            
            echo '
            <li>
            <div class="block-images-list">
            <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'"/>
            </div>                    
            
            <ul class="reviews-and-counts-list">
            <li><img src="/images/eye-icon.png"/><p>'.$row["count"].'</p></li>
            <li><img src="/images/comment-icon.png"/><p>'.$count_reviews.'</p></li>
            </ul>
            
            <p class="style-title-list"><a href="view_content.php?id='.$row["products_id"].'">'.$row["title"].'</a></p>
            
            <a class="add-cart-style-list" href="" tid="'.$row["products_id"].'"></a>
            <p class="style-price-list"><strong>'.group_numerals($row["price"]).'</strong> ���</p>
            <div class="style-text-list">
            '.$row["mini_description"].'            
            </div>
            </li>
            
            
            ';
            
        }
        while ($row = mysql_fetch_array($result));      
    }
    
echo '</ul>';
   
   }else {echo "<p>������� ���!</p>";} 
    }else 
    {
        echo '<p>������ ��������� �� �������!</p>';
    }
    
if ($page != 1){ $pstr_prev = '<li><a class="pstr-prev" href="view_aystopper.php?go='.$go.'&page='.($page - 1).'&sort='.$sorting.'">&lt;</a></li>';}
if ($page != $total) $pstr_next = '<li><a class="pstr-next" href="view_aystopper.php?go='.$go.'&page='.($page + 1).'&sort='.$sorting.'">&gt;</a></li>';


// ��������� ������ �� ����������

if($page - 5 > 0) $page5left = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page - 5).'&sort='.$sorting.'">'.($page - 5).'</a></li>';
if($page - 4 > 0) $page4left = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page - 4).'&sort='.$sorting.'">'.($page - 4).'</a></li>';
if($page - 3 > 0) $page3left = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page - 3).'&sort='.$sorting.'">'.($page - 3).'</a></li>';
if($page - 2 > 0) $page2left = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page - 2).'&sort='.$sorting.'">'.($page - 2).'</a></li>';
if($page - 1 > 0) $page1left = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page - 1).'&sort='.$sorting.'">'.($page - 1).'</a></li>';

if($page + 5 <= $total) $page5right = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page + 5).'&sort='.$sorting.'">'.($page + 5).'</a></li>';
if($page + 4 <= $total) $page4right = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page + 4).'&sort='.$sorting.'">'.($page + 4).'</a></li>';
if($page + 3 <= $total) $page3right = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page + 3).'&sort='.$sorting.'">'.($page + 3).'</a></li>';
if($page + 2 <= $total) $page2right = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page + 2).'&sort='.$sorting.'">'.($page + 2).'</a></li>';
if($page + 1 <= $total) $page1right = '<li><a href="view_aystopper.php?go='.$go.'&page='.($page + 1).'&sort='.$sorting.'">'.($page + 1).'</a></li>';    
    
if ($page+5 < $total)
{
    $strtotal = '<li><p class="nav-point">...</p></li><li><a href="view_aystopper.php?go='.$go.'&page='.$total.'&sort='.$sorting.'">'.$total.'</a></li>';
}else
{
     $strtotal = ""; 
}

if ($total > 1)
{
    echo '
    <div class="pstrnav">
    <ul>
    ';
    echo $pstr_prev.$page5left.$page4left.$page3left.$page2left.$page1left."<li><a class='pstr-active' href='view_aystopper.php?go=".$go."&page=".$page."&sort=".$sorting."'>".$page."</a></li>".$page1right.$page2right.$page3right.$page4right.$page5right.$strtotal.$pstr_next;
    echo '
    </ul>
    </div>
    ';
}



?>


</div>
<?php		
    include("include/block-random.php");
    include("include/block-footer.php");          
?>
</div>

</body>
</html>