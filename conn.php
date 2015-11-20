<?php
// 连接数据库  
$conn=@mysql_connect("XXX.XXX.XXX.XXX:3306","root","XXXXXX")  or die(mysql_error());  
@mysql_select_db('dc_hubble',$conn) or die(mysql_error());  
?>
