<?php 
    header("Content-Type: text/html; charset=utf-8");
    session_start();
    require_once("connMysql.php");
    if(isset($_POST["registered"])){
        header("Location: registered.php");
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

            <td class="tdbline">
                <table width="100%" border="0" cellspacing="0" cellpadding="10" >
                    <form id="form1" name="form1" method="POST" action="login.php">
                        <table width="300" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#F2F2F2">
                            <tr><td colspan="2" align="center" bgcolor="#CCCCCC">
                                <font color="#FFFFFF">會員登入</font></td>
                            </tr>
                            <tr><td width="80" align="center" valign="baseline">帳號：</td>
                                <td valign="baseline"><input type="text" name="loginname" id="username" /></td>
                            </tr>
                            <tr><td width="80" align="center" valign="baseline">密碼：</td>
                                <td valign="baseline"><input type="password" name="loginpasswd" id="passwd" /></td>
                            </tr>
                            <tr><td colspan="2" align="center" bgcolor="#CCCCCC">
                                <input type="submit" name="login" id="login" value="登入" />
                                <input type="submit" name="registered" id="registered" value="註冊" /></td>
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
