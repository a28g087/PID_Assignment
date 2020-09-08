<?php 
header("Content-Type: text/html; charset=utf-8");
require_once("connMysql.php");
session_start();

if(isset($_GET["logout"])&&$_GET["logout"]=="true"){
    unset($_SESSION["username"]);
    unset($_SESSION["passwd"]);
    header("Location: index.php");
}
//add product
if(isset($_POST["addbtn"])&&$_POST["addbtn"]=="確定新增"){
    if($_FILES["p_photo"]["error"]==0){
        if(move_uploaded_file($_FILES["p_photo"]["tmp_name"],"./proimg/".$_FILES["p_photo"]["name"])){
            $sql=sprintf("INSERT INTO product(productname, productprice, productimages,description)
                    VALUES('%s',%s,'%s','%s')"
                    ,$_POST["p_name"],$_POST["p_price"],$_FILES["p_photo"]["name"],$_POST["p_desc"]);
            $result=$db_link->query($sql);
?>
            <script>alert("新增成功!");</script>
<?php
            header("Location: admin_product.php");
        }
        else{
            echo "上傳error!";
        }
    }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>網路購物系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet"  href="css/bootstrap.min.css" >
<style>
    .hide{
        display:none;
    }
</style>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="./jquery-1.9.1.min.js"></script>
<script>
function checkForm(){               
    if(document.form.p_name.value==""){
        alert("請填寫商品名稱！");
        document.form.p_name.focus();
        return false;
    }
    if(document.form.p_price.value==""){
        alert("請填寫商品價格！");
        document.form.p_price.focus();
        return false;
    }
    return confirm('\n您確定要新增這個商品嗎?');
}
</script>
</head>
<body>
<script type="text/javascript">
	$(function() {
		$("#p_photo").change(function() {
			var readFile = new FileReader();
			var mfile = $("#p_photo")[0].files[0];  //注意這裡必須時$("#p_photo")[0]，document.getElementById('file')等價與$("#myfile")[0]
			readFile.readAsDataURL(mfile);
			readFile.onload = function() {
				var img = $("#show");
                img.removeClass("hide");
				img.attr("src", this.result);
			}
		});
	})
</script>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr>
    <td class="tdbline"><img width="100%" align="center" height="80" src="images/mlogo.png"/></td>
    </tr>
    <tr>
        <td>
            <tr>
                <td>
                <div class="container">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a data-toggle="tab" class="nav-link" href="admin.php">會員管理</a></li>
                        <li class="nav-item"><a data-toggle="tab" class="nav-link active" href="admin_product.php">商品管理</a></li>
                    </ul>
                </div>
                <div class="actionDiv">
                    <a>購物管理員 歡迎!</a>｜<a href="index.php?logout=true">登出</a>
                </div>
                </td>
            </tr>
        </td>
    </tr>
    <tr>
        <td class="tdbline">
            <table width="100%" border="0" cellspacing="0" cellpadding="10">
                <tr valign="top">
                    <td><p class="title">新增商品</p>
                        <table width="100%" border="1" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
                            <tr>
                                <th class="thcss" width="15%" bgcolor="#CCCCCC">商品名稱</th>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">商品價格</th>
                                <th class="thcss" width="30%" bgcolor="#CCCCCC">商品圖片</th>
                                <th class="thcss" width="30%" bgcolor="#CCCCCC">商品說明</th>
                                <th class="thcss" width="15%" bgcolor="#CCCCCC">新增</th>
                            </tr>

                            <form action="" method="post" name="form" id="form" enctype="multipart/form-data" onSubmit="return checkForm()">
                            <tr style="border: solid 1px orange;">
                                <td width="15%" align="center" bgcolor="#FFFFFF"><input type="text" name="p_name" id="p_name"></td>
                                <td width="10%" align="center" bgcolor="#FFFFFF"><input type="text" pattern="[1-9]{1}+[0-9]" name="p_price" id="p_price"></td>
                                <td width="30%"  align="center" bgcolor="#FFFFFF">
                                    請選取商品圖片:<input type="file" name="p_photo" id="p_photo"><br/>
                                                <img src="" id="show" width="200" class="hide">
                                </td>
                                <td width="30%" align="center" bgcolor="#FFFFFF"><input type="text" name="p_desc" id="p_desc"></td>
                                <td width="15%" align="center" bgcolor="#FFFFFF">
                                    <input type="submit" name="addbtn" id="addbtn" onClick="" value="確定新增">
                                </td>
                            </tr>
                            </form>

                        </table>     
                        <hr size="1" />
                    </td>
                </tr>
            </table>    
        </td>
    </tr>
    <tr>
        <td height="30" align="center" class="trademark">© 2020 eHappy Studio All Rights Reserved.</td>
    </tr>  
</table>
</body>
</html>
<?php
  $db_link->close();
?>