<?php
    define('myyshop', true);
	include("include/db_connect.php");
    include("functions/functions.php");  
    session_start(); 
    include("include/auth_cookie.php");  
    unset($_SESSION['auth']);
    
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
    <script type="text/javascript" src="/js/jquery.form.js"></script>
    <script type="text/javascript" src="/js/jquery.validate.js"></script>
    <script type="text/javascript" src="/js/TextChange.js"></script>
    <script type="text/javascript">
$(document).ready(function() {	
      $('#form_reg').validate(
				{	
					// ������� ��� ��������
					rules:{
						"reg_login":{
							required:true,
							minlength:5,
                            maxlength:15,
                            remote: {
                            type: "post",    
		                    url: "/reg/check_login.php"
		                            }
						},
						"reg_pass":{
							required:true,
							minlength:7,
                            maxlength:15
						},
						"reg_surname":{
							required:true,
                            minlength:3,
                            maxlength:15
						},
						"reg_name":{
							required:true,
                            minlength:3,
                            maxlength:15
						},
						"reg_patronymic":{
							required:true,
                            minlength:3,
                            maxlength:25
						},
						"reg_email":{
						    required:true,
							email:true
						},
						"reg_phone":{
							required:true
						},
						"reg_address":{
							required:true
						},
						"reg_captcha":{
							required:true,
                            remote: {
                            type: "post",    
		                    url: "/reg/check_captcha.php"
		                    
		                            }
                            
						}
					},

					// ��������� ��������� ��� ��������� ��������������� ������
					messages:{
						"reg_login":{
							required:"������� �����!",
                            minlength:"�� 5 �� 15 ��������!",
                            maxlength:"�� 5 �� 15 ��������!",
                            remote: "����� �����!"
						},
						"reg_pass":{
							required:"������� ������!",
                            minlength:"�� 7 �� 15 ��������!",
                            maxlength:"�� 7 �� 15 ��������!"
						},
						"reg_surname":{
							required:"������� ���� �������!",
                            minlength:"�� 3 �� 20 ��������!",
                            maxlength:"�� 3 �� 20 ��������!"                            
						},
						"reg_name":{
							required:"������� ���� ���!",
                            minlength:"�� 3 �� 15 ��������!",
                            maxlength:"�� 3 �� 15 ��������!"                               
						},
						"reg_patronymic":{
							required:"������� ���� ��������!",
                            minlength:"�� 3 �� 25 ��������!",
                            maxlength:"�� 3 �� 25 ��������!"  
						},
						"reg_email":{
						    required:"������� ���� E-mail",
							email:"�� ���������� E-mail"
						},
						"reg_phone":{
							required:"������� ����� ��������!"
						},
						"reg_address":{
							required:"���������� ������� ����� ��������!"
						},
						"reg_captcha":{
							required:"������� ��� � ��������!",
                            remote: "�� ������ ��� ��������!"
						}
					},
					
	submitHandler: function(form){
	$(form).ajaxSubmit({
	success: function(data) { 
								 
        if (data == 'true')
    {
       $("#block-form-registration").fadeOut(300,function() {
        
        $("#reg_message").addClass("reg_message_good").fadeIn(400).html("�� ������� ����������������!");
        $("#form_submit").hide();
        
       });
         
    }
    else
    {
       $("#reg_message").addClass("reg_message_error").fadeIn(400).html(data); 
    }
		} 
			}); 
			}
			});
    	});
     
</script>
    
    <title>�����������</title>
</head>
<body>
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

<h2 class="h2-title">�����������</h2>

<form method="POST" id="form_reg" action="/reg/handler_reg.php">

<p id="reg_message"></p>

<div id="block-form-registration">
<ul id="form-registration">

<li>
<label>�����</label>
<span class="star">*</span>
<input type="text" name="reg_login" id="reg_login"/>
</li>

<li>
<label>������</label>
<span class="star">*</span>
<input type="text" name="reg_pass" id="reg_pass"/>
<span id="genpass">�������������</span>
</li>

<li>
<label>�������</label>
<span class="star">*</span>
<input type="text" name="reg_surname" id="reg_surname"/>
</li>

<li>
<label>���</label>
<span class="star">*</span>
<input type="text" name="reg_name" id="reg_name"/>
</li>

<li>
<label>��������</label>
<span class="star">*</span>
<input type="text" name="reg_patronymic" id="reg_patronymic"/>
</li>

<li>
<label>E-mail</label>
<span class="star">*</span>
<input type="text" name="reg_email" id="reg_email"/>
</li>

<li>
<label>��������� �������</label>
<span class="star">*</span>
<input type="text" name="reg_phone" id="reg_phone"/>
</li>

<li>
<label>����� ��������</label>
<span class="star">*</span>
<input type="text" name="reg_address" id="reg_address"/>
</li>

<li>
<div id="block-captcha">
<img src="/reg/reg_captcha.php"/>
<input type="text" name="reg_captcha" id="reg_captcha"/>
<p id="reloadcaptcha">��������</p>

</div>
</li>

</ul>

</div>
<p align="right"><input type="submit" name="reg_submit" id="form_submit" value="�����������"/></p>

</form>

</div>
<?php		
    include("include/block-random.php");
    include("include/block-footer.php");          
?>
</div>

</body>
</html>