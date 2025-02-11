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
    
    $_SESSION['urlpage'] = "<a href='index.php'>�������</a> \ <a href='tovar.php'>������</a> \ <a>���������� ������</a>";
    
    include("include/db_connect.php");
    include("include/functions.php"); 
   
   if ($_POST["submit_add"])
    {
 if($_SESSION['add_tovar'] == '1')
 {
      $error = array();
    
    // �������� �����
        
       if (!$_POST["form_title"])
      {
         $error[] = "������� �������� ������";
      }
      
       if (!$_POST["form_price"])
      {
         $error[] = "������� ����";
      }
          
       if (!$_POST["form_category"])
      {
         $error[] = "������� ���������";         
      }else
      {
       	$result = mysql_query("SELECT * FROM category WHERE id='{$_POST["form_category"]}'",$link);
        $row = mysql_fetch_array($result);
        $selectbrand = $row["brand"];

      }
      
 // �������� ���������
      
       if ($_POST["chk_visible"])
       {
          $chk_visible = "1";
       }else { $chk_visible = "0"; }
      
       if ($_POST["chk_new"])
       {
          $chk_new = "1";
       }else { $chk_new = "0"; }
      
       if ($_POST["chk_leader"])
       {
          $chk_leader= "1";
       }else { $chk_leader = "0"; }
      
       if ($_POST["chk_sale"])
       {
          $chk_sale = "1";
       }else { $chk_sale = "0"; }                   
      
                                      
       if (count($error))
       {           
            $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>";
            
       }else
       {
                           
              		mysql_query("INSERT INTO table_products(title,price,brand,seo_words,seo_description,mini_description,description,mini_features,features,new,leader,sale,visible,type_tovara,brand_id)
						VALUES(						
                            '".$_POST["form_title"]."',
                            '".$_POST["form_price"]."',
                            '".$selectbrand."',
                            '".$_POST["form_seo_words"]."',
                            '".$_POST["form_seo_description"]."',
                            '".$_POST["txt1"]."',
                            '".$_POST["txt2"]."',
                            '".$_POST["txt3"]."',
                            '".$_POST["txt4"]."',
                            '".$chk_new."',
                            '".$chk_leader."',
                            '".$chk_sale."',
                            '".$chk_visible."',
                            '".$_POST["form_type"]."',
                            '".$_POST["form_category"]."'                               
						)",$link);
                   
      $_SESSION['message'] = "<p id='form-success'>����� ������� ��������!</p>";
      $id = mysql_insert_id();
                 
       if (empty($_POST["upload_image"]))
      {        
      include("actions/upload-image.php");
      unset($_POST["upload_image"]);           
      } 
      
       if (empty($_POST["galleryimg"]))
      {        
      include("actions/upload-gallery.php"); 
      unset($_POST["galleryimg"]);                 
      }
}

    
           
}else
{
    $msgerror = '� ��� ��� ���� �� ���������� ������';
}
}   

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
    <script type="text/javascript" src="js/script.js"></script> 
    <script type="text/javascript" src="./ckeditor/ckeditor.js"></script> 

	<title>������ ����������</title>
</head>

<body>

<div id="block-body">
<?php
	include("include/block-header.php");
?>
<div id="block-content">

<div id="block-parameters">
<p id="title-page">���������� ������</p>
</div>
<?php
if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';

		 if(isset($_SESSION['message']))
		{
		echo $_SESSION['message'];
		unset($_SESSION['message']);
		}
        
     if(isset($_SESSION['answer']))
		{
		echo $_SESSION['answer'];
		unset($_SESSION['answer']);
		} 
?>
<form enctype="multipart/form-data" method="post">
<ul id="edit-tovar">

<li>
<label>�������� ������</label>
<input type="text" name="form_title" value="<?php echo $_POST["form_title"];?>"/>
</li>

<li>
<label>����</label>
<input type="text" name="form_price"  value="<?php echo $_POST["form_price"];?>"/>
</li>

<li>
<label>�������� �����</label>
<input type="text" name="form_seo_words"  value="<?php echo $_POST["form_seo_words"];?>"/>
</li>

<li>
<label>������� ��������</label>
<textarea name="form_seo_description" ></textarea>
</li>
<li>
<label>��� ������</label>
<select name="form_type" id="type" size="1" >

<option value="mobile" >��������� ��������</option>
<option value="notebook" >��������</option>
<option value="notepad" >��������</option>

</select>
</li>

<li>
<label>���������</label>
<select name="form_category" size="10" >

<?php
$category = mysql_query("SELECT * FROM category",$link);
    
If (mysql_num_rows($category) > 0)
{
$result_category = mysql_fetch_array($category);
do
{
  
  echo '
  
  <option value="'.$result_category["id"].'" >'.$result_category["brand"].'</option>
  
  ';
    
}
 while ($result_category = mysql_fetch_array($category));
}
?> 

</select>
</ul> 
<label class="stylelabel" >�������� ��������</label>

<div id="baseimg-upload">
<input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
<input type="file" name="upload_image" />

</div>

<h3 class="h3click" >������� �������� ������</h3>
<div class="div-editor1" >
<textarea id="editor1" name="txt1" cols="100" rows="20"></textarea>
		<script type="text/javascript">
			var ckeditor1 = CKEDITOR.replace( "editor1" );
			AjexFileManager.init({
				returnTo: "ckeditor",
				editor: ckeditor1
			});
		</script>
 </div>       
 
<h3 class="h3click" >�������� ������</h3>
<div class="div-editor2" >
<textarea id="editor2" name="txt2" cols="100" rows="20"></textarea>
		<script type="text/javascript">
			var ckeditor1 = CKEDITOR.replace( "editor2" );
			AjexFileManager.init({
				returnTo: "ckeditor",
				editor: ckeditor1
			});
		</script>
 </div>          

<h3 class="h3click" >������� ��������������</h3>
<div class="div-editor3" >
<textarea id="editor3" name="txt3" cols="100" rows="20"></textarea>
		<script type="text/javascript">
			var ckeditor1 = CKEDITOR.replace( "editor3" );
			AjexFileManager.init({
				returnTo: "ckeditor",
				editor: ckeditor1
			});
		</script>
 </div>        

<h3 class="h3click" >��������������</h3>
<div class="div-editor4" >
<textarea id="editor4" name="txt4" cols="100" rows="20"></textarea>
		<script type="text/javascript">
			var ckeditor1 = CKEDITOR.replace( "editor4" );
			AjexFileManager.init({
				returnTo: "ckeditor",
				editor: ckeditor1
			});
		</script>
  </div> 

<label class="stylelabel" >�������� ��������</label>

<div id="objects" >

<div id="addimage1" class="addimage">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
<input type="file" name="galleryimg[]" />
</div>

</div>

<p id="add-input" >��������</p>
     
<h3 class="h3title" >��������� ������</h3>   
<ul id="chkbox">
<li><input type="checkbox" name="chk_visible" id="chk_visible" /><label for="chk_visible" >���������� �����</label></li>
<li><input type="checkbox" name="chk_new" id="chk_new"  /><label for="chk_new" >����� �����</label></li>
<li><input type="checkbox" name="chk_leader" id="chk_leader"  /><label for="chk_leader" >���������� �����</label></li>
<li><input type="checkbox" name="chk_sale" id="chk_sale"  /><label for="chk_sale" >����� �� �������</label></li>
</ul> 


    <p align="right" ><input type="submit" id="submit_form" name="submit_add" value="�������� �����"/></p>     
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
    