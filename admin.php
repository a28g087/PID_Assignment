<?php 
header("Content-Type: text/html; charset=utf-8");
require_once("connMysql.php");
session_start();

if(isset($_GET["logout"])&&$_GET["logout"]=="true"){
  unset($_SESSION["username"]);
  unset($_SESSION["passwd"]);
  header("Location: index.php");
}

//設定黑名單
if(isset($_POST["blackbtn"])&&($_POST["blackbtn"]=="設為黑名單")){
    $sql=sprintf("UPDATE member SET m_level='blacklist' WHERE m_username='%s'",$_POST["hidden"]);
    echo $sql;
    $result=$db_link->query($sql);
    header("Location: admin.php");
}

//解除黑名單
if(isset($_POST["whitebtn"])&&($_POST["whitebtn"]=="解除黑名單")){
    $sql=sprintf("UPDATE member SET m_level='member' WHERE m_username='%s'",$_POST["hidden"]);
    echo $sql;
    $result=$db_link->query($sql);
    header("Location: admin.php");
}

//查看訂單
if(isset($_POST["orderbtn"])&&($_POST["orderbtn"]=="查看訂單")){
    $_SESSION["order"]="select_order";
    $_SESSION["m_username"]=$_POST["hidden"];
    header("Location: admin_order.php");
}

$pageRow_records = 8;  //預設每頁筆數
$num_pages = 1;  //預設頁數
if (isset($_GET['page'])) {  //若已經有翻頁，將頁數更新
  $num_pages = $_GET['page'];
}
$startRow_records = ($num_pages -1) * $pageRow_records;  //本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
              
//預設狀況下未加限制顯示筆數的SQL敘述句
$query_RecProduct = "SELECT * FROM member WHERE m_level<>'admin'";
$stmt=$db_link->prepare($query_RecProduct);
$stmt->execute();
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$all_RecProduct = $stmt->get_result();
//計算總筆數
$total_records = $all_RecProduct->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);

//返回 URL 參數
function keepURL(){
	$keepURL = "";
	if(isset($_GET["cid"])) $keepURL.="&cid=".$_GET["cid"];
	return $keepURL;
}

$sql_member="SELECT * FROM member WHERE m_level='member'";
$stmt_sql_member=$db_link->query($sql_member);

$sql_bkmember="SELECT * FROM member WHERE m_level='blacklist'";
$stmt_sql_bkmember=$db_link->query($sql_bkmember);

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
function checkbk(){
    if (confirm('\n您確定要設定這個會員為黑名單嗎?')) return true;
    return false;
}
function checkwhite(){
    if (confirm('\n您確定要解除這個黑名單嗎?')) return true;
    return false;
}
</script>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr>
        <td height="80" align="center" background="images/mlogo.png" class="tdbline"></td>
    </tr>
    <tr>
        <td>
            <tr>
                <td>
                <div class="container">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#">會員管理</a></li>
                        <li class="nav-item"><a data-toggle="tab" class="nav-link" href="admin_product.php">商品管理</a></li>
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
                        <table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
                            <tr >
                                <th class="thcss" width="15%" bgcolor="#CCCCCC">姓名</th>
                                <th class="thcss" width="15%" bgcolor="#CCCCCC">帳號</th>
                                <th class="thcss" width="5%" bgcolor="#CCCCCC">性別</th>
                                <th class="thcss" width="20%" bgcolor="#CCCCCC">電話號碼</th>
                                <th class="thcss" width="20%" bgcolor="#CCCCCC">E-mail</th>
                                <th class="thcss" width="15%" bgcolor="#CCCCCC">查看訂單/設為黑名單</th>
                            </tr>
                            
                            <?php while($row=$stmt_sql_member->fetch_assoc()){?>
                            <form action="" method="post" name="memberform" id="memberform" onSubmit="return checkForm();">
                            <tr style="border: solid 1px orange;">
                                <td width="15%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_name"];?></td>
                                <td width="15%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_username"];?></td>
                                <td width="5%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_sex"];?></td>
                                <td width="20%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_phone"];?></td>
                                <td width="20%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_email"];?></td>
                                <td width="25%" align="center" bgcolor="#FFFFFF"><p>  
                                    <input type="hidden" name="hidden" id="hidden" value="<?=$row["m_username"];?>">
                                    <input style="margin:5px;" type="submit" name="orderbtn" id="orderbtn" value="查看訂單"><br >
                                    <input type="submit" name="whitebtn" id="whitebtn" onClick="return checkbk();" value="設為黑名單"></p>                           
                                </td>
                            </tr>
                            </form>
                            <?php }?>
                            <?php if($stmt_sql_bkmember->num_rows>0){?>
                                <tr>
                                    <th class="thcss" width="15%" bgcolor="#CCCCCC" colspan="6">黑名單</th>
                                </tr>
                                <?php while($row=$stmt_sql_bkmember->fetch_assoc()){ ?>
                                <form action="" method="post" name="memberform" id="memberform" onSubmit="return checkForm();">
                                <tr style="border: solid 1px orange;">
                                    <td width="15%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_name"];?></td>
                                    <td width="15%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_username"];?></td>
                                    <td width="5%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_sex"];?></td>
                                    <td width="20%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_phone"];?></td>
                                    <td width="20%" align="center" bgcolor="#FFFFFF"><?php echo $row["m_email"];?></td>
                                    <td width="25%" align="center" bgcolor="#FFFFFF"><p>
                                        <input type="hidden" name="hidden" id="hidden" value="<?=$row["m_username"];?>">
                                        <input style="margin:5px;" type="submit" name="orderbtn" id="orderbtn" value="查看訂單"><br>
                                        <input type="submit" name="whitebtn" id="whitebtn" onClick="return checkwhite();" value="解除黑名單"></p>                          
                                    </td>
                                </tr>
                                </form>
                                <?php } //end while ?>
                            <?php }?>
                        </table>     
                        <hr size="1" />
                        <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
                            <tr>
                                <td valign="middle"><p>資料筆數：<?php echo $total_records;?></p></td>
                                <td align="right">
                                    <p>
                                        <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
                                        <a href="?page=1">第一頁</a> | <a href="?page=<?php echo $num_pages-1;?>">上一頁</a> |
                                        <?php }?>
                                        <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
                                        <a href="?page=<?php echo $num_pages+1;?>">下一頁</a> | <a href="?page=<?php echo $total_pages;?>">最末頁</a>
                                        <?php }?>
                                    </p>
                                </td>
                            </tr>
                        </table><p>&nbsp;</p>
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
  $stmt->close();
  $db_link->close();
?>