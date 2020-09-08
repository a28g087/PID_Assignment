<?php 
    header("Content-Type: text/html; charset=utf-8");
    require_once("connMysql.php");
    require_once("mycart.php");
    session_start();
    $cart =& $_SESSION['cart']; // 將購物車的值設定為 Session

    if(!is_object($cart)){ 
    $cart = new myCart();
    }
    
    if(isset($_POST["registered"])&&$_POST["registered"]="registered"){
        header("Location: registered.php");
    }
    elseif(isset($_POST["login"])&&$_POST["login"]="login"){
        $sql="SELECT m_username,m_passwd,m_level FROM member 
                WHERE m_username='{$_POST["m_username"]}' AND m_passwd='{$_POST["m_passwd"]}'";
        $stmt=$db_link->query($sql);
        $result=$stmt->fetch_assoc();
        if($stmt->num_rows>0){
            $_SESSION["username"]=$_POST["m_username"];
            $_SESSION["passwd"]=$_POST["m_passwd"];
            
            if($result["m_level"]=="admin"){
                $_SESSION["admin"]="admin";
                header("Location: admin.php");
            }
            elseif($result["m_level"]=="member"){
                $sql=sprintf("SELECT * FROM cart WHERE username='%s'",$_SESSION["username"]);
                $result=$db_link->query($sql);
                if(mysqli_num_rows($result)>0){ 
                    while($row=$result->fetch_assoc()){ 
                        $cart->add_item($row["productid"],$row["qty"],$row["price"],$row["info"]);
                    }                                    
                }
                header("Location: index.php");
            }
            elseif($result["m_level"]=="blacklist"){
                ?>
                <script>
                    alert("您的帳戶已被停權!");
                </script>
                <?php
            }
        }
        else{
            ?>
                <script>
                    alert("沒有此帳戶!\n請在確認您的帳戶與密碼!");
                </script>
            <?php
        }
    }
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>網路購物系統</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <script language="javascript">
            function checkForm(){	
                user=document.form.m_username.value;
                pw=document.form.m_passwd.value;
                if(user==""){
                    alert("請填寫姓名!");
                    document.form.m_username.focus();
                    return false;
                }
                if(user.length<5||user.length>12){
                        alert("您的帳號長度只能5至12個字元!");
                        document.form.m_username.focus();
                        return false;
                }
                if(pw==""){
                    alert("請填寫密碼!");
                    document.form.m_passwd.focus();
                    return false;
                }
                if(pw.length<5||pw.length>12){
                    alert("您的密碼長度只能5至12個字元!");
                    document.form.m_passwd.focus();
                    return false;
                }
            }
        </script>
    </head>
    <body>
        <table width="780" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
            <tr>
                <td height="80" align="center" background="images/mlogo.png" class="tdbline"></td>
            </tr>

            <td class="tdbline">
                <table width="100%" border="0" cellspacing="0" cellpadding="10" >
                    <form id="" name="form" method="POST" action="login.php" onSubmit="return checkForm();">
                        <table width="300" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#F2F2F2">
                            <tr><td colspan="2" align="center" bgcolor="#CCCCCC">
                                <font color="#FFFFFF">會員登入</font></td>
                            </tr>
                            <tr><td width="80" align="center" valign="baseline">帳號：</td>
                                <td valign="baseline"><input type="text" name="m_username" id="m_username" /></td>
                            </tr>
                            <tr><td width="80" align="center" valign="baseline">密碼：</td>
                                <td valign="baseline"><input type="password" name="m_passwd" id="m_passwd" /></td>
                            </tr>
                            <tr><td colspan="2" align="center" bgcolor="#CCCCCC">
                                <input type="submit" name="login" id="login" value="登入" />
                                <input type="submit" name="registered" id="registered" value="註冊" />
                                <input type="button" name="Submit" value="首頁" onClick="window.location.href='index.php';">
                                </td>
                            </tr>
                        </table>
                    </form>
                    <tr>
                        <td height="30" align="center" class="trademark">© 2020 eHappy Studio All Rights Reserved.</td>
                    </tr>
                </table>
            </td>
        </table>
    </body>
</html>
