<pre>
<?php 
    header("Content-Type: text/html; charset=utf-8");
    require_once("connMysql.php");
$pageRow_records = 8;  //預設每頁筆數
$num_pages = 1;  //預設頁數
if (isset($_GET['page'])) {  //若已經有翻頁，將頁數更新
  $num_pages = $_GET['page'];
}
$startRow_records = ($num_pages -1) * $pageRow_records;  //本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
              
//預設狀況下未加限制顯示筆數的SQL敘述句
$query_RecProduct = "SELECT * FROM product ORDER BY productid DESC";
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
    <td class="tdbline">
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
          <td><div class="subjectDiv"> <span class="heading"><img src="images/16-cube-green.png" width="16" height="16" align="absmiddle"></span> 產品列表</div>
            <div class="actionDiv"><a href="cart.php">登出</a>｜<a href="cart.php">我的購物車</a></div>
                  <?php
                  $query_limit_RecProduct=$query_RecProduct." LIMIT {$startRow_records}, {$pageRow_records}";
                  $stmt=$db_link->prepare($query_limit_RecProduct);
                  $stmt->execute();
                  $RecProduct=$stmt->get_result();
                  ?>
            <?php	while($row_RecProduct=$RecProduct->fetch_assoc()){ ?>

            <div class="albumDiv">
              <div class="picDiv"><a href="product.php?id=<?php echo $row_RecProduct["productid"];?>">
                <?php if($row_RecProduct["productimages"]==""){?>
                <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
                <?php }else{?>
                <img src="proimg/<?php echo $row_RecProduct["productimages"];?>" alt="<?php echo $row_RecProduct["productname"];?>" width="135" height="135" border="0" />
                <?php }?>
                </a></div>
              <div class="albuminfo"><a href="product.php?id=<?php echo $row_RecProduct["productid"];?>"><?php echo $row_RecProduct["productname"];?></a><br />
                <span class="smalltext">特價 </span><span class="redword"><?php echo $row_RecProduct["productprice"];?></span><span class="smalltext"> 元</span> </div>
            </div>
            <?php }?>
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
            </div></td>
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
</pre>