<?php
function sql_connect($server,$user,$pwd){
	return mysql_connect($server,$user,$pwd);
}
function sql_select_db($database){
	return mysql_select_db($database);
}
function sql_query($sql){
	return mysql_query($sql);
}
function sql_fetch_array($result,$array_type=MYSQL_BOTH){
	return mysql_fetch_array($result,$array_type);
}
function sql_fetch_assoc($result){
	return mysql_fetch_assoc($result);
}
function sql_fetch_row($result){
	return mysql_fetch_row($result);
}
function sql_num_rows($result){
	return mysql_num_rows($result);
}
function sql_insert_id(){
	return mysql_insert_id();
}
function sql_affected_rows(){
	return mysql_affected_rows();
}
function sql_error(){
	return mysql_error();
}
function sql_errno(){
	return mysql_errno();
}
function sql_real_escape_string($value){
	return mysql_real_escape_string($value);
}
?>