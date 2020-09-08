<?php 
header("Content-Type: text/html; charset=utf-8");
require_once("connMysql.php");
session_start();

if(isset($_GET["logout"])&&$_GET["logout"]=="true"){
  unset($_SESSION["username"]);
  unset($_SESSION["passwd"]);
  header("Location: index.php");
}
//刪除商品
if(isset($_POST["delbtn"])&&$_POST["delbtn"]=="刪除"){
    $sql=sprintf("DELETE FROM product WHERE productid=%s"
                    ,$_POST["hidden"]);
    $result=$db_link->query($sql);
    if($_POST["hidden_img"]=="nopic"){
        echo $_POST["hidden_img"];
    }
    else{
        unlink("proimg/".$_POST["hidden_img"]);
    }
    ?>
    <script>alert("已刪除此商品!");</script>
    <?php
}
//修改商品
if(isset($_POST["updatebtn"])&&$_POST["updatebtn"]=="修改"){
    $_SESSION["productid"]=$_POST["hidden"];
    header("Location: update_product.php");
}

$pageRow_records = 6;  //預設每頁筆數
$num_pages = 1;  //預設頁數
if (isset($_GET['page'])) {  //若已經有翻頁，將頁數更新
  $num_pages = $_GET['page'];
}
$startRow_records = ($num_pages -1) * $pageRow_records;  //本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
              
//預設狀況下未加限制顯示筆數的SQL敘述句
$sql=sprintf("SELECT * FROM product");
$result=$db_link->query($sql);

$stmt=$db_link->prepare($sql);
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
function checkdel(){
    if (confirm('\n您確定要刪除這個商品嗎?')){ 
        return true;
    }
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
                        <li class="nav-item"><a data-toggle="tab" class="nav-link" href="admin.php">會員管理</a></li>
                        <li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#">商品管理</a></li>
                    </ul>
                    <form action="add_product.php" method="post" name="form" id="form" onSubmit="">
                    <input type="submit" name="addbtn" id="addbtn" value="新增商品">
                    </form>
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
                            <tr >
                                <th class="thcss" width="5%" bgcolor="#CCCCCC">商品編號</th>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">商品名稱</th>
                                <th class="thcss" width="10%" bgcolor="#CCCCCC">商品價格</th>
                                <th class="thcss" width="30%" bgcolor="#CCCCCC">商品圖片</th>
                                <th class="thcss" width="30%" bgcolor="#CCCCCC">商品說明</th>
                                <th class="thcss" width="15%" bgcolor="#CCCCCC">修改/刪除</th>
                            </tr>
                            <?php 
                            $query_limit=$sql." LIMIT {$startRow_records}, {$pageRow_records}";
                            $stmt=$db_link->prepare($query_limit);
                            $stmt->execute();
                            $RecProduct=$stmt->get_result();
                            while($row=$RecProduct->fetch_assoc()){?>
                            <form action="" method="post" name="form" id="form" onSubmit="">
                            <tr style="border: solid 1px orange;">
                                <td width="5%" align="center" bgcolor="#FFFFFF"><?=$row["productid"]?></td>
                                <td width="10%" align="center" bgcolor="#FFFFFF"><?=$row["productname"]?></td>
                                <td width="10%" align="center" bgcolor="#FFFFFF"><?=$row["productprice"]?></td>
                                <td width="30%" align="center" bgcolor="#FFFFFF">
                                    <?php if($row["productimages"]==""){?>
                                    <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
                                    <input type="hidden" name="hidden_img" id="hidden_img" value="nopic">
                                    <?php }else{?>
                                    <img src="proimg/<?php echo $row["productimages"];?>" width="135" height="135" border="0" />
                                    <input type="hidden" name="hidden_img" id="hidden_img" value="<?=$row["productimages"];?>">
                                    <?php }?>
                                </td>
                                <td width="30%" align="center" bgcolor="#FFFFFF"><?=nl2br($row["description"])?></td>
                                <td width="15%" align="center" bgcolor="#FFFFFF">
                                    <input type="hidden" name="hidden" id="hidden" value="<?=$row["productid"];?>">
                                    <input style="margin:5px;" type="submit" name="updatebtn" id="updatebtn" value="修改"><br >
                                    <input type="submit" name="delbtn" id="delbtn" onClick="return checkdel();" value="刪除"> 
                                </td>
                            </tr>
                            </form>
                            <?php }?>
                        </table>     
                        <hr size="1" />
                        <div class="navDiv">
                        <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
                        <a href="?page=1<?php echo keepURL();?>">|&lt;</a> <a href="?page=<?php echo $num_pages-1;?><?php echo keepURL();?>">&lt;&lt;</a>
                        <?php }else{?>
                        |&lt; &lt;&lt;
                        <?php }?>
                        <?php
                            for($i=1;$i<=$total_pages;$i++){
                                if($i==$num_pages){
                                    echo $i." ";
                                }else{
                                    $urlstr = keepURL();
                                    echo "<a href=\"?page=$i$urlstr\">$i</a> ";
                                }
                            }
                        ?>
                        <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
                        <a href="?page=<?php echo $num_pages+1;?><?php echo keepURL();?>">&gt;&gt;</a> <a href="?page=<?php echo $total_pages;?><?php echo keepURL();?>">&gt;|</a>
                        <?php }else{?>
                        &gt;&gt; &gt;|
                        <?php }?>
                        </div>
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