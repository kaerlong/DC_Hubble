<?php
require "conn.php";

$str = file_get_contents("php://input");  
$arr=array();  

parse_str($str,$arr);

if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) 
{ 
	$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]; 
} 
elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) 
{ 
	$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"]; 
}
elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]) 
{ 
	$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
} 
elseif (getenv("HTTP_X_FORWARDED_FOR")) 
{ 
	$ip = getenv("HTTP_X_FORWARDED_FOR"); 
} 
elseif (getenv("HTTP_CLIENT_IP")) 
{ 
	$ip = getenv("HTTP_CLIENT_IP"); 
} 
elseif (getenv("REMOTE_ADDR"))
{ 
	$ip = getenv("REMOTE_ADDR"); 
} 
else 
{ 
	$ip = "Unknown"; 
}

$get_ip = mysql_escape_string($ip);
$get_type = mysql_escape_string("add_data");

if($arr["pwd"] == "XXXXXX")
{
	$get_info = mysql_escape_string("OK");
	$nameid = mysql_escape_string($arr["nameid"]);
	$name = mysql_escape_string($arr["name"]);
	if ($arr["flag"] == "TMP")
	{
		$tmp = mysql_escape_string($arr["tmp"]);
		$sqlstr = "insert into tmp(nameid,name,tmp) values('".$nameid."','".$name."','".$tmp."')";
		mysql_query($sqlstr) or die(mysql_error());
	}
	else if ($arr["flag"] == "DHT")
	{
		$dhtt = mysql_escape_string($arr["dht"]);
		$dhth = mysql_escape_string($arr["dhh"]);
		$sqlstr = "insert into dht(nameid,name,dhtt,dhth) values('".$nameid."','".$name."','".$dhtt."','".$dhth."')";
		mysql_query($sqlstr) or die(mysql_error());
	}
	else if ($arr["flag"] == "REN")
	{
		$ren = mysql_escape_string($arr["ren"]);
		$sqlstr = "insert into ren(nameid,name,ren) values('".$nameid."','".$name."','".$ren."')";
		mysql_query($sqlstr) or die(mysql_error());
	}
	else if ($arr["flag"] == "SMK")
        {
                $smk = mysql_escape_string($arr["smk"]);
                $sqlstr = "insert into smoke(nameid,name,smoke) values('".$nameid."','".$name."','".$smk."')";
                mysql_query($sqlstr) or die(mysql_error());
        }
}
else
{
	$get_info = mysql_escape_string("INVALID");
}

$sqlstr = "insert into ip(type,ip,info) values('".$get_type."','".$get_ip."','".$get_info."')";
mysql_query($sqlstr) or die(mysql_error());

?>
