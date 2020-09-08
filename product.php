<?php 
header("Content-Type: text/html; charset=utf-8");
require_once("connMysql.php");
//購物車開始
require_once("mycart.php");
session_start();

$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)){ 
  $cart = new myCart();
}

if(isset($_POST["addcart"])&&(!isset($_SESSION["username"])||$_SESSION["username"]=="")){
?>
<script>alert("請先進行登入!");</script>
<?php
}
elseif(isset($_POST["cartaction"]) && ($_POST["cartaction"]=="add")){// 新增購物車內容
  $cart->add_item($_POST['id'],$_POST['qty'],$_POST['price'],$_POST['name']);
  header("Location: cart.php");
}
//購物車結束
//繫結產品資料
$query_RecProduct = "SELECT * FROM product WHERE productid=?";
$stmt=$db_link->prepare($query_RecProduct);
$stmt->bind_param("i",$_GET["id"]);
$stmt->execute();
$RecProduct = $stmt->get_result();
$row_RecProduct=$RecProduct->fetch_assoc();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>網路購物系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="80" align="center" background="images/mlogo.png" class="tdbline"></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        
          <td><div class="subjectDiv"> <span class="heading"><img src="images/16-cube-green.png" width="16" height="16" align="absmiddle"></span> 產品列表</div>
            <div class="actionDiv">
              <?php if(isset($_SESSION["username"])&&$_SESSION["username"]!=""){?>
                <a><?=$_SESSION["username"]?> 歡迎!</a>｜<a href="index.php">首頁</a>｜<a href="cart.php">我的購物車</a>｜<a href="orders_view.php">我的訂單</a>｜<a href="index.php?logout=true">登出</a>
              <?php }else{?>
                <a href="login.php">登入</a>｜<a href="registered.php">註冊</a>
              <?php }?>
            </div>
          <div class="albumDiv">
            <div class="picDiv">
              <?php if($row_RecProduct["productimages"]==""){?>
              <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
              <?php }else{?>
              <img src="proimg/<?php echo $row_RecProduct["productimages"];?>" alt="<?php echo $row_RecProduct["productname"];?>" width="135" height="135" border="0" />
              <?php }?>
            </div>
            <div class="albuminfo"><span class="smalltext">特價 </span><span class="redword"><?php echo $row_RecProduct["productprice"];?></span><span class="smalltext"> 元</span></div>
          </div>
          <div class="titleDiv">
            <?php echo $row_RecProduct["productname"];?></div>
          <div class="dataDiv">
            <p><?php echo nl2br($row_RecProduct["description"]);?></p>
            <hr width="100%" size="1" />
            <form name="form3" method="post" action="">
              <input name="id" type="hidden" id="id" value="<?php echo $row_RecProduct["productid"];?>">
              <input name="name" type="hidden" id="name" value="<?php echo $row_RecProduct["productname"];?>">
              <input name="price" type="hidden" id="price" value="<?php echo $row_RecProduct["productprice"];?>">
              <input name="qty" type="hidden" id="qty" value="1">
              <input name="cartaction" type="hidden" id="cartaction" value="add">
              <input type="submit" name="addcart" id="addcart" value="加入購物車">
              <input type="button" name="button4" id="button4" value="回上一頁" onClick="window.history.back();">
            </form>
          </div></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td height="30" align="center" class="trademark">© 2020 eHappy Studio All Rights Reserved.</td>
  </tr>
</table>
</body>
</html>
<?php
$stmt->close();
$db_link->close();
?>