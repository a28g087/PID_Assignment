<?php
    session_start();
    if(isset($_POST["action"])&&$_POST["action"]=="join"){
        require_once("connMysql.php");
        $sql="SELECT * FROM member WHERE m_username='{$_POST["m_username"]}'";
        $stmt=$db_link->query($sql);
        if ($stmt->num_rows>0){ 
            header("Location: registered.php?errMsg=repeat&username={$_POST["m_username"]}");
        }
        else{
            $sql_add_member=sprintf ("INSERT INTO member (m_name,m_username,m_passwd
                                                        ,m_level,m_sex,m_birthday
                                                        ,m_email,m_address,m_phone) 
                                VALUES ('%s','%s','%s','member','%s','%s','%s','%s','%s')"
                                ,$_POST["m_name"],$_POST["m_username"],$_POST["m_passwd"]
                                /*m_level='member'*/ ,$_POST["m_sex"],$_POST["m_birthday"]
                                ,$_POST["m_email"],$_POST["m_address"],$_POST["m_phone"]);
            $stmt_add=$db_link->query($sql_add_member);
            $db_link->close();
            header("Location: registered.php?success=success");
        }
    }

?>
<?php if(isset($_GET["success"])&&$_GET["success"]="success"){ ?>
<script>
    alert("註冊成功!\n請使用此帳號進行登入!");
    window.location.href="registered.php";
</script>
<?php }?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>網路購物系統</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <script language="javascript">
            function checkForm(){               
                if(document.form.m_username.value==""){
                    alert("請填寫帳號！");
                    document.form.m_username.focus();
                    return false;
                }
                else{
                    uid=document.form.m_username.value;
                    if(uid.length<5||uid.length>12){
                        alert("您的帳號長度只能5至12個字元!");
                        document.form.m_username.focus();
                        return false;
                    }
                }
                pw1=document.form.m_passwd.value;
                pw2=document.form.m_passwdrecheck.value;
                if(!check_passwd(pw1,pw2)){
                    document.form.m_passwd.focus();
                    return false;
                }
                if(document.form.m_name.value==""){
                    alert("請填寫姓名！");
                    document.form.m_name.focus();
                    return false;
                }
                if(document.form.m_birthday.value==""){
                    alert("請填寫生日！");
                    document.form.m_birthday.focus();
                    return false;
                }
                if(document.form.m_email.value==""){
                    alert("請填寫 E-mail！");
                    document.form.m_email.focus();
                    return false;
                }
                return confirm("確定送出嗎？");
            }
            function check_passwd(pw1,pw2){
                if(pw1==''){
                    alert("密碼不能空白!");
                    return false;
                }
                if(pw1.length<5||pw1.length>10){
                    alert("密碼長度只能5至10個字元!");
                    return false;
                }
                if(pw1!=pw2){
                    alert("密碼二次輸入不一樣,請重新輸入!");
                    return false;
                }
                for(var i=0;i<pw1.length;i++){
                    if(pw1.charAt(i)==' '){
                        alert("密碼不能含有空白!");
                        return false;
                    }
                }
                return true;
            }
            function check_mail(mymail){
                var check=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if(check.test(mymail.value)){
                    return true;
                }
                alert("E-mail 格式不正確!");
                return false;
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
                    <tr>
                        <form action="" method="POST" name="form" id="form" onSubmit="return checkForm();">
                    
                            <tr>
                                <p class="title">加入會員</p>
                            </tr>
                            <?php if(isset($_GET["errMsg"])&&($_GET["errMsg"]=="repeat")){ ?>
                                <tr><p>帳號：<?=$_GET["username"]?>已有人使用!</p></tr>    
                            <?php } ?>

                            <tr>
                                <hr size="1" />
                                <p class="heading">帳號資料</p>

                                <p><strong>使用帳號</strong>：
                                    <input name="m_username" type="text" class="normalinput" id="m_username">
                                    <font color="#FF0000">*</font><br><span class="smalltext">請填入5~12個字元以內的小寫英文字母、數字、以及_ 符號。</span>
                                </p>
                                <p><strong>使用密碼</strong>：
                                    <input name="m_passwd" type="password" class="normalinput" id="m_passwd">
                                    <font color="#FF0000">*</font><br><span class="smalltext">請填入5~10個字元以內的英文字母、數字、以及各種符號組合，</span>
                                </p>
                                <p><strong>確認密碼</strong>：
                                    <input name="m_passwdrecheck" type="password" class="normalinput" id="m_passwdrecheck">
                                    <font color="#FF0000">*</font> <br><span class="smalltext">再輸入一次密碼</span>
                                </p>
                                <hr size="1" />                                           
                                <!-- ........  -->
                                <p class="heading">個人資料</p>
                                <p><strong>真實姓名</strong>：
                                    <input name="m_name" type="text" class="normalinput" id="m_name">
                                    <font color="#FF0000">*</font>
                                </p>

                                <p><strong>性　　別</strong>：
                                    <input name="m_sex" type="radio" value="女" checked>女
                                    <input name="m_sex" type="radio" value="男">男
                                    <font color="#FF0000">*</font>
                                </p>

                                <p><strong>生　　日</strong>：
                                    <input name="m_birthday" type="text" class="normalinput" id="m_birthday">
                                    <font color="#FF0000">*</font> <br>
                                    <span class="smalltext">為西元格式(YYYY-MM-DD)。</span>
                                </p>

                                <p><strong>E-mail</strong>：
                                    <input name="m_email" type="text" class="normalinput" id="m_email">
                                    <font color="#FF0000">*</font><br><span class="smalltext">請確定此電子郵件為可使用狀態，以方便未來系統使用，如補寄會員密碼信。</span>
                                </p>
                                
                                <p><strong>電　　話</strong>：
                                    <input name="m_phone" type="text" class="normalinput" id="m_phone">
                                </p>
                                    
                                <p><strong>住　　址</strong>：
                                    <input name="m_address" type="text" class="normalinput" id="m_address" size="40"></p>
                                    <p> <font color="#FF0000">*</font> 表示為必填的欄位
                                </p>
                            </tr>
                            
                            <hr size="1" />
                            <p align="center">
                                <input name="action" type="hidden" id="action" value="join">
                                <input type="submit" name="Submit2" value="送出申請">
                                <input type="reset" name="Submit3" value="重設資料">
                                <input type="button" name="Submit" value="登入" onClick="window.location.href='login.php';">
                            </p>

                        </form>
                    </tr>

                    <tr>
                        <td height="30" align="center" class="trademark">© 2020 eHappy Studio All Rights Reserved.</td>
                    </tr>
                </table>
            </td>
        </table>
    </body>
</html>
