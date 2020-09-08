<!DOCTYPE html>
<html>

<head>  
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
   <title>文字區塊自動高度</title>  
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>  
   <script type="text/javascript">   
   jQuery(function($) {  
       $("textarea.AutoHeight").css("overflow","hidden").bind("keydown keyup", function(){  
           $(this).height('0px').height($(this).prop("scrollHeight")+"px");  
       }).keydown();  
   });  
   </script>   
</head>  
<body>  
   <textarea class="AutoHeight">◆ 1024MBDDRII雙通道記憶體<br/>◆ 100GB超大硬碟容量<br/>◆ 內建130萬畫素網路攝影機<br/>◆ 12吋鏡面寬螢幕</textarea>  
   <textarea class="AutoHeight"></textarea>  
   <textarea class="AutoHeight"></textarea> 
   <form name="foo" method="post" enctype="multipart/form-data">
   <input type="text" value="./f_name.txt"/>
    <input type="file" value="./f_name.txt">
</form>
<script>//document.foo.submit();</script> 
</body>  
</html>  