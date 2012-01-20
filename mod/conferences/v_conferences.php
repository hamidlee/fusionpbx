<?php
/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2008-2012
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('conferences_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "includes/header.php";
require_once "includes/paging.php";

//get the http get values
	$order_by = $_GET["order_by"];
	$order = $_GET["order"];

//find the conference extensions from the dialplan include details

	//define the conference array
	$conference_array = array ();

	$sql = "";
	$sql .= "select * from v_dialplan_details ";
	$sql .= "where domain_uuid = '$domain_uuid' ";
	if (permission_exists('conferences_add') && permission_exists('conferences_edit')) {
		//allow users with the conferences_add or conferences_edit permission to all conference rooms
	}
	else {
		//find the assigned users
			$sql .= "and field_data like 'conference_user_list%' and field_data like '%|".$_SESSION['username']."|%' ";
	}
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$x = 0;
	$result = $prep_statement->fetchAll();
	foreach ($result as &$row) {
		$dialplan_uuid = $row["dialplan_uuid"];
		$field_type = $row["field_type"];
		if (permission_exists('conferences_add') && permission_exists('conferences_edit')) {
			if ($field_type == "conference") {
				$conference_array[$x]['dialplan_uuid'] = $dialplan_uuid;
				$x++;
			}
		}
		else {
			$conference_array[$x]['dialplan_uuid'] = $dialplan_uuid;
			$x++;
		}
	}
	unset ($prep_statement);
	//foreach ($conference_array as &$row) {
	//	echo "--".$row['dialplan_uuid']."--<br />\n";
	//}

//begin the form
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "<td align=\"center\">\n";
	echo "<br />";

	echo "	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "	<tr>\n";
	echo "	<td align='left'>\n";
	echo "		<span class=\"vexpl\"><span class=\"red\">\n";
	echo "			<strong>Conferences</strong>\n";
	echo "		</span></span>\n";
	echo "	</td>\n";
	echo "	<td align='right'>\n";
	echo "		&nbsp;\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "	<td align='left' colspan='2'>\n";
	echo "		<span class=\"vexpl\">\n";
	echo "			Conferences is used to setup conference rooms with a name, description, and optional pin number.\n";
	if (permission_exists('conferences_active_view')) {
		echo "			Show <a href='".PROJECT_PATH."/mod/conferences_active/v_conferences_active.php'>Active Conferences</a> and then select a conference to monitor and interact with it.\n";
	}
	echo "		</span>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "	</table>";

	echo "	<br />";
	echo "	<br />";

	$sql = "";
	$sql .= " select * from v_dialplan ";
	if (count($conference_array) == 0) {
		//when there are no conferences do this to hide all remaining entries
		$sql .= " where domain_uuid = '$domain_uuid' ";
		$sql .= " and context = 'hide' ";
	}
	else {
		$x = 0;
		foreach ($conference_array as &$row) {
			if ($x == 0) {
				$sql .= " where domain_uuid = '$domain_uuid' \n";
				$sql .= " and dialplan_uuid = '".$row['dialplan_uuid']."' \n";
			}
			else {
				$sql .= " or domain_uuid = '$domain_uuid' \n";
				$sql .= " and dialplan_uuid = '".$row['dialplan_uuid']."' \n";
			}
			$x++;
		}
	}
	if (strlen($order_by)> 0) {
		$sql .= "order by $order_by $order ";
	}
	else {
		$sql .= "order by dialplan_order, extension_name asc ";
	}
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$result = $prep_statement->fetchAll();
	$num_rows = count($result);
	unset ($prep_statement, $result, $sql);

	$rows_per_page = 20;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; } 
	list($paging_controls, $rows_per_page, $var_3) = paging($num_rows, $param, $rows_per_page); 
	$offset = $rows_per_page * $page;

	$sql = "";
	$sql .= " select * from v_dialplan ";
	if (count($conference_array) == 0) {
		//when there are no conferences do this to hide all remaining entries
		$sql .= " where domain_uuid = '$domain_uuid' ";
		$sql .= " and context = 'hide' ";
	}
	else {
		$x = 0;
		foreach ($conference_array as &$row) {
			if ($x == 0) {
				$sql .= " where domain_uuid = '$domain_uuid' \n";
				$sql .= " and dialplan_uuid = '".$row['dialplan_uuid']."' \n";
			}
			else {
				$sql .= " or domain_uuid = '$domain_uuid' \n";
				$sql .= " and dialplan_uuid = '".$row['dialplan_uuid']."' \n";
			}
			$x++;
		}
	}
	if (strlen($order_by)> 0) { $sql .= "order by $order_by $order "; } else { $sql .= "order by dialplan_order, extension_name asc "; }
	$sql .= " limit $rows_per_page offset $offset ";
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$result = $prep_statement->fetchAll();
	$result_count = count($result);
	unset ($prep_statement, $sql);

	$c = 0;
	$row_style["0"] = "row_style0";
	$row_style["1"] = "row_style1";

	echo "<div align='center'>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	echo thorder_by('extension_name', 'Conference Name', $order_by, $order);
	echo "<th>Tools</th>\n";
	if (permission_exists('conferences_add')) {
		echo thorder_by('dialplan_order', 'Order', $order_by, $order);
	}
	echo thorder_by('enabled', 'Enabled', $order_by, $order);
	echo thorder_by('descr', 'Description', $order_by, $order);
	if (permission_exists('conferences_add')) {
		echo "<td align='right' width='42'>\n";
		echo "	<a href='v_conferences_edit.php' alt='add'>$v_link_label_add</a>\n";
	}
	else {
		echo "<td align='right' width='21'>\n";
	}
	echo "</td>\n";
	echo "<tr>\n";

	if ($result_count > 0) {
		foreach($result as $row) {
			$extension_display_name = $row['extension_name'];
			$extension_display_name = str_replace("-", " ", $extension_display_name);
			$extension_display_name = str_replace("_", " ", $extension_display_name);

			echo "<tr >\n";
			echo "	<td valign='top' class='".$row_style[$c]."'>&nbsp;&nbsp;".$extension_display_name."</td>\n";
			echo "	<td valign='top' class='".$row_style[$c]."'><a href='".PROJECT_PATH."/mod/conferences_active/v_conference_interactive.php?c=".$row['extension_name']."'>view</a></td>\n";
			if (permission_exists('conferences_add')) {
				echo "   <td valign='top' class='".$row_style[$c]."'>&nbsp;&nbsp;".$row['dialplan_order']."</td>\n";
			}
			echo "	<td valign='top' class='".$row_style[$c]."'>&nbsp;&nbsp;".$row['enabled']."</td>\n";
			echo "	<td valign='top' class='row_stylebg' width='30%'>".$row['descr']."&nbsp;</td>\n";
			echo "	<td valign='top' align='right'>\n";
			if (permission_exists('conferences_edit')) {
				echo "		<a href='v_conferences_edit.php?id=".$row['dialplan_uuid']."' alt='edit'>$v_link_label_edit</a>\n";
			}
			if (permission_exists('conferences_delete')) {
				echo "		<a href='v_conferences_delete.php?id=".$row['dialplan_uuid']."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			}
			echo "	</td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $row_count);
	} //end if results

	echo "<tr>\n";
	echo "<td colspan='6'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$paging_controls</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	if (permission_exists('conferences_add')) {
		echo "			<a href='v_conferences_edit.php' alt='add'>$v_link_label_add</a>\n";
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td colspan='5' align='left'>\n";
	echo "<br />\n";
	if ($v_path_show) {
		echo $v_dialplan_default_dir;
	}
	echo "</td>\n";
	echo "</tr>\n";

	echo "</table>";
	echo "</div>";
	echo "<br><br>";
	echo "<br><br>";

	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</div>";
	echo "<br><br>";

//show the footer
	require_once "includes/footer.php";

?>
