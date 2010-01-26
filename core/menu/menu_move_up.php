<?php
/* $Id$ */
/*
	menu_move_up.php
	Copyright (C) 2008, 2009 Mark J Crane
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

	1. Redistributions of source code must retain the above copyright notice,
	   this list of conditions and the following disclaimer.

	2. Redistributions in binary form must reproduce the above copyright
	   notice, this list of conditions and the following disclaimer in the
	   documentation and/or other materials provided with the distribution.

	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	POSSIBILITY OF SUCH DAMAGE.
*/
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
//require_once "config.php";

if (!ifgroup("superadmin")) {
	echo "access denied";
	return;
}

//move down more than one level at a time
//update v_menu set menuorder = (menuorder+1) where menuorder > 2 or menuorder = 2

if (count($_GET)>0) {
	$menuid = check_str($_GET["menuid"]);
	$menuorder = check_str($_GET["menuorder"]);
	$menuparentid = check_str($_GET["menuparentid"]);

	if ($menuorder != 1) {

		$_SESSION["menu"] = ""; //clear the menu session so it will rebuild with the update

		//move the current item's order number down
		$sql  = "update v_menu set ";
		$sql .= "menuorder = (menuorder+1) "; //move down
		$sql .= "where v_id = '".$v_id."' ";
		$sql .= "and menuorder = ".($menuorder-1)." ";
		$sql .= "and menuparentid  = '$menuparentid' ";
		$sql .= "or v_id = '".$v_id."' ";
		$sql .= "and menuorder = '".($menuorder-1).".0' ";
		$sql .= "and menuparentid  = '$menuparentid' ";        
		//echo $sql."<br><br>";
		$db->exec(check_sql($sql));
		//$lastinsertid = $db->lastInsertId($id);
		unset($sql);

		//move the selected item's order number up
		$sql  = "update v_menu set ";
		$sql .= "menuorder = (menuorder-1) "; //move up
		$sql .= "where v_id = '".$v_id."' ";
		$sql .= "and menuid = '$menuid' ";
		$sql .= "and menuparentid  = '$menuparentid' ";
		//echo $sql."<br><br>";
		$db->exec(check_sql($sql));
		//$lastinsertid = $db->lastInsertId($id);
		unset($sql);

	}

	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=menu_list.php?menuid=$menuid\">\n";
	echo "<div align='center'>";
	echo "Item Moved Up";
	echo "</div>";
	require_once "includes/footer.php";
	return;
}


?>
