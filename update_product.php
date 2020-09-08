<?php 
header("Content-Type: text/html; charset=utf-8");
require_once("connMysql.php");
session_start();

if(isset($_GET["logout"])&&$_GET["logout"]=="true"){
  unset($_SESSION["username"]);
  unset($_SESSION["passwd"]);
  header("Location: index.php");
}
//update product
if(isset($_POST["updatebtn"])&&$_POST["updatebtn"]=="確定修改"){
    if($_FILES["p_photo"]["error"]==0){
        move_uploaded_file($_FILES["p_photo"]["tmp_name"],"./proimg/".$_FILES["p_photo"]["name"]);
        $img_name=$_FILES["p_photo"]["name"];
    }
    else{
        $img_name=$_SESSION["p_img"];
    }
    $sql=sprintf("UPDATE product SET productname='%s', productprice='%s', productimages='%s',description='%s' 
            WHERE productid=%s"
            ,$_POST["p_name"],$_POST["p_price"],$img_name,$_POST["p_desc"],$_POST["hidden"]);
    $result=$db_link->query($sql);
    unlink("proimg/".$_SESSION["p_img"]);
?>
    <script>alert("修改成功!");</script>
<?php
    header("Location: admin_product.php");
}
              
//預設狀況下未加限制顯示筆數的SQL敘述句
$sql=sprintf("SELECT * FROM product WHERE productid='%s'",$_SESSION["productid"]);
$result=$db_link->query($sql);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>網路購物系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet"  href="css/bootstrap.min.css" >
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="./jquery-1.9.1.min.js"></script>
<script>
function autogrow(textarea){
    var adjustedHeight=textarea.clientHeight;
    adjustedHeight=Math.max(textarea.scrollHeight,adjustedHeight);
    if (adjustedHeight>textarea.clientHeight){
        textarea.style.height=adjustedHeight+'px';
    }
}

function check(){
    if (confirm('\n您確定要修改這個商品嗎?')){ 
        return true;
    }
    return false;
}
jQuery(function($) {  
    $("textarea.AutoHeight").css("overflow","hidden").bind("keydown keyup", function(){  
        $(this).height('0px').height($(this).prop("scrollHeight")+"px");  
    }).keydown();  
});  

$(function() {
	$("#p_photo").change(function() {
		var readFile = new FileReader();
		var mfile = $("#p_photo")[0].files[0];  //注意這裡必須時$("#p_photo")[0]，document.getElementById('file')等價與$("#myfile")[0]
		readFile.readAsDataURL(mfile);
		readFile.onload = function() {
			var img = $("#show");
			img.attr("src", this.result);
		}
	});
})

</script>
</head>
<body>
    
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
                    <td>
                        <table width="100%" border="1" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
                            <tr>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">商品編號</th>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">商品名稱</th>
                                <th class="thcss" width="5%" bgcolor="#CCCCCC">商品價格</th>
                                <th class="thcss" width="30%" bgcolor="#CCCCCC">商品圖片</th>
                                <th class="thcss" width="30%" bgcolor="#CCCCCC">商品說明</th>
                                <th class="thcss" width="15%" bgcolor="#CCCCCC">修改/刪除</th>
                            </tr>
                            <?php while($row=$result->fetch_assoc()){
                                    $_SESSION["p_img"]=$row["productimages"];
                            ?>
                            <form action="" method="post" name="form" id="form" onSubmit="" enctype="multipart/form-data">
                            <tr style="border: solid 1px orange;">
                                <td width="10%" align="center" bgcolor="#FFFFFF"><?=$row["productid"];?></td>
                                <td width="10%" align="center" bgcolor="#FFFFFF"><input style="width: 10em;" type="text" name="p_name" id="p_name" value="<?=$row["productname"];?>"></td>
                                <td width="5%" align="center" bgcolor="#FFFFFF"><input style="width: 5em;" type="text" name="p_price" id="p_price" value="<?=$row["productprice"];?>"></td>
                                <td width="30%" align="center" bgcolor="#FFFFFF">
                                    <input type="file" name="p_photo" id="p_photo"><br/>
                                    <?php if($row["productimages"]==""){?>
                                    <img src="images/nopic.png" alt="暫無圖片" width="100" height="100" border="0" />
                                    <?php }else{?>
                                    <img id="show" src="proimg/<?php echo $row["productimages"];?>" width="100" height="100" border="0" />
                                    <?php }?>
                                </td>
                                <td width="30%" align="center" bgcolor="#FFFFFF">
                                    <textarea class="AutoHeight" name="p_desc" id="p_desc"><?=$row["description"];?></textarea>
                                </td>
                                <td width="15%" align="center" bgcolor="#FFFFFF">
                                    <input type="hidden" name="hidden" id="hidden" value="<?=$row["productid"];?>">
                                    <input type="submit" name="updatebtn" id="updatebtn" onClick="return check();" value="確定修改"> 
                                </td>
                            </tr>
                            </form>
                            <?php }?>
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