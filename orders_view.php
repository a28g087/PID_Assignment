<?php 
header("Content-Type: text/html; charset=utf-8");
require_once("connMysql.php");
session_start();

if(isset($_GET["logout"])&&$_GET["logout"]=="true"){
  unset($_SESSION["username"]);
  unset($_SESSION["passwd"]);
  header("Location: index.php");
}

$pageRow_records = 8;  //預設每頁筆數
$num_pages = 1;  //預設頁數
if (isset($_GET['page'])) {  //若已經有翻頁，將頁數更新
  $num_pages = $_GET['page'];
}
$startRow_records = ($num_pages -1) * $pageRow_records;  //本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
              
//預設狀況下未加限制顯示筆數的SQL敘述句
$sql=sprintf("SELECT * FROM orders WHERE m_username='%s'",$_SESSION["username"]);
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
</head>
<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr>
        <td height="80" align="center" background="images/mlogo.png" class="tdbline"></td>
    </tr>
    <tr>
        <td>
          <!-- <table width="100%" border="1" cellspacing="0" cellpadding="10"> -->
            <tr>
                <td>
                <div class="subjectDiv"> <span class="heading"><img src="images/16-cube-green.png" width="16" height="16" align="absmiddle"></span> 訂單列表</div>
                <div class="actionDiv">
                <a><?=$_SESSION["username"]?> 歡迎!</a>｜<a href="index.php">首頁</a>｜<a href="cart.php">我的購物車</a>｜<a href="index.php?logout=true">登出</a>
                </div>
                </td>
            </tr>
        </td>
    </tr>
    <tr>
        <td class="tdbline">
            <table width="100%" border="0" cellspacing="0" cellpadding="10">
                <tr valign="top">
                    <td><p class="title">我的訂單</p>
                        <table width="100%" border="1" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
                            <tr>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">訂單編號</th>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">訂購人名稱</th>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">訂購人電話</th>
                                <th class="thcss" width="50%" bgcolor="#CCCCCC">訂購商品單價及數量</th>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">運費</th>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">含運費總額</th>
                            </tr>
                            <?php if($result->num_rows>0){ ?>
                            <?php while($row=$result->fetch_assoc()){
                                    $sel_orderdetail=sprintf("SELECT * FROM orderdetail WHERE orderid='%s'",$row["orderid"]);
                                    $result_sel=$db_link->query($sel_orderdetail);?>
                            <form action="" method="post" name="form" id="form">
                            <tr style="border: solid 1px orange;">
                                <td width="10%" align="center" bgcolor="#FFFFFF"><?php echo $row["orderid"];?></td>
                                <td width="10%" align="center" bgcolor="#FFFFFF"><?php echo $row["customername"];?></td>
                                <td width="10%" align="center" bgcolor="#FFFFFF"><?php echo $row["customerphone"];?></td>
                                <td width="50%" align="center" bgcolor="#FFFFFF" style="font-size:13px">
                                <?php while($row_sel=$result_sel->fetch_assoc()){
                                    echo "".$row_sel["productname"]."(品名): ".$row_sel["unitprice"]."(單價)*".$row_sel["quantity"],"(數量)<br>";
                                }?>
                                </td>
                                <td width="10%" align="center" bgcolor="#FFFFFF"><?php echo $row["deliverfee"];?></td>
                                <td width="10%" align="center" bgcolor="#FFFFFF"><?php echo $row["grandtotal"];?></td>
                            </tr>
                            </form>
                            <?php }
                                } else { ?>
                                <td width="100%" colspan="6" align="center" bgcolor="#FFFFFF">您並無下訂單..</td>
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