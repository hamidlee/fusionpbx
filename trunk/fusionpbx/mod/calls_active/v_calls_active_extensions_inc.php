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
	Copyright (C) 2010
	All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('extensions_active_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//http get and set variables
	if (strlen($_GET['url']) > 0) {
		$url = $_GET['url'];
	}
	if (strlen($_GET['rows']) == 0) {
		$_GET['rows'] = 0;
	}

//define variables
	$c = 0;
	$rowstyle["0"] = "rowstyle1";
	$rowstyle["1"] = "rowstyle1";

//get the user status
	if ($_SESSION['user_status_display'] == "false") {
		//hide the user_status when it is set to false
	}
	else {
		$sql = "";
		$sql .= "select e.extension, u.username, u.user_status, e.user_list ";
		$sql .= "from v_users as u, v_extensions as e ";
		$sql .= "where e.v_id = '$v_id' ";
		$sql .= "and u.v_id = '$v_id' ";
		$sql .= "and u.usercategory = 'user' ";
		if ($db_type == "sqlite") {
			$sql .= "and e.user_list like '%|' || u.username || '|%' ";
		}
		if ($db_type == "pgsql") {
			$sql .= "and e.user_list like '%|' || u.username || '|%' ";
		}
		if ($db_type == "mysql") {
			$sql .= "and e.user_list like CONCAT('%|', u.username, '|%'); ";
		}
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$x = 0;
		$result = $prepstatement->fetchAll(PDO::FETCH_NAMED);
		foreach ($result as &$row) {
			if (strlen($row["user_status"]) > 0) {
				$user_array[$row["extension"]]['username'] = $row["username"];
				$user_array[$row["extension"]]['user_status'] = $row["user_status"];
				$username_array[$row["username"]]['user_status'] = $row["user_status"];
				if ($row["username"] == $_SESSION["username"]) {
					$user_status = $row["user_status"];
				}
			}
			$x++;
		}
		unset ($prepstatement, $x);
	}

//create the event socket connection
	$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);

//get information over event socket
	if (!$fp) {
		$msg = "<div align='center'>Connection to Event Socket failed.<br /></div>"; 
		echo "<div align='center'>\n";
		echo "<table width='40%'>\n";
		echo "<tr>\n";
		echo "<th align='left'>Message</th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td class='rowstyle1'><strong>$msg</strong></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}
	else {
		//get the agent list from event socket
			$switch_cmd = 'callcenter_config agent list';
			$event_socket_str = trim(event_socket_request($fp, 'api '.$switch_cmd));
			$agent_array = csv_to_named_array($event_socket_str, '|');
		//set the status on the user_array by using the extension as the key
			foreach ($agent_array as $row) {
				if (count($_SESSION['domains']) == 1) {
					//get the extension status from the call center agent list
					preg_match('/user\/(\d{2,7})/', $row['contact'], $matches);
					$extension = $matches[1];
					$user_array[$extension]['username'] = $tmp[0];
					if ($user_array[$extension]['user_status'] != "Do Not Disturb") {
						$user_array[$extension]['user_status'] = $row['status'];
					}
				} else {
					$tmp = explode('@',$row["name"]);
					if ($tmp[1] == $v_domain) {
						//get the extension status from the call center agent list
						preg_match('/user\/(\d{2,7})/', $row['contact'], $matches);
						$extension = $matches[1];
						$user_array[$extension]['username'] = $tmp[0];
						if ($user_array[$extension]['user_status'] != "Do Not Disturb") {
							$user_array[$extension]['user_status'] = $row['status'];
						}
					}
				}
			}

		//send the api command over event socket
			$switch_cmd = 'valet_info';
			$valet_xml_str = trim(event_socket_request($fp, 'api '.$switch_cmd));

		//parse the xml
			try {
				$valet_xml = new SimpleXMLElement($valet_xml_str);
			}
			catch(Exception $e) {
				//echo $e->getMessage();
			}
			$valet_xml = new SimpleXMLElement($valet_xml_str);
			foreach ($valet_xml as $row) {
				$valet_name = (string) $row->attributes()->name;
				//echo "valet_name: ".$valet_name."<br />\n";
				foreach ($row->extension as $row2) {
					$extension = (string) $row2;
					$uuid = (string) $row2->attributes()->uuid;
					$valet_array[$uuid]['name'] = $valet_name;
					$valet_array[$uuid]['extension'] = $extension;
				}
			}

		//get information over event socket
			$switch_cmd = 'show channels';
		//send the event socket command 
			$csv = trim(event_socket_request($fp, 'api '.$switch_cmd));
		//prepare the string
			$channels_array = explode("\n\n",$csv);
		//get the named array
			$channels_array = csv_to_named_array($channels_array[0], ",");

		//remove other domains
			$x = 1;
			foreach ($channels_array as $row) {
				//set the original array id
					$channels_array[$x]['x'] = $x;
				//unset domains that are not related to this tenant
					$temp_array = explode("@", $row['presence_id']);
					if ($temp_array[1] != $v_domain) {
						unset($channels_array[$x]);
					}
				$x++;
			}

		//active channels array
			foreach ($channels_array as $row) {

				//set the php variables
					foreach ($row as $key => $value) {
						$$key = $value;
					}
	
				//get the original array id
					//$x = $row['x'];

				//parse some of the php variables\
					$temp_array = explode("@", $presence_id);
					$channels_array[$x]['number'] = $temp_array[0];

				//remove the '+' because it breaks the call recording
					$channels_array[$x]['cid_num'] = $temp_array[0] = str_replace("+", "", $cid_num);

				//calculate and set the call length
					$call_length_seconds = time() - $created_epoch;
					$call_length_hour = floor($call_length_seconds/3600);
					$call_length_min = floor($call_length_seconds/60 - ($call_length_hour * 60));
					$call_length_sec = $call_length_seconds - (($call_length_hour * 3600) + ($call_length_min * 60));
					$call_length_min = sprintf("%02d", $call_length_min);
					$call_length_sec = sprintf("%02d", $call_length_sec);
					$call_length = $call_length_hour.':'.$call_length_min.':'.$call_length_sec;
					$channels_array[$x]['call_length'] = $call_length;

				//valet park
					$valet_array[$uuid]['context'] = $context;
					$valet_array[$uuid]['cid_name'] = $cid_name;
					$valet_array[$uuid]['cid_num'] = $cid_num;
					$valet_array[$uuid]['call_length'] = $call_length;
			}

		//active extensions
			//get the extension information
				if ($debug) {
					unset($_SESSION['extension_array']);
				}
				if (count($_SESSION['extension_array']) == 0) {
					$sql = "";
					$sql .= "select * from v_extensions ";
					$x = 0;
					$range_array = $_GET['range'];
					foreach($range_array as $tmp_range) {
						$tmp_range = str_replace(":", "-", $tmp_range);
						$tmp_array = explode("-", $tmp_range);
						$tmp_min = $tmp_array[0];
						$tmp_max = $tmp_array[1];
						if ($x == 0) {
							$sql .= "where v_id = '$v_id' ";
							$sql .= "and extension >= $tmp_min ";
							$sql .= "and extension <= $tmp_max ";
							$sql .= "and enabled = 'true' ";
						}
						else {
							$sql .= "or v_id = '$v_id' ";
							$sql .= "and extension >= $tmp_min ";
							$sql .= "and extension <= $tmp_max ";
							$sql .= "and enabled = 'true' ";
						}
						$x++;
					}
					if (count($range_array) == 0) {
						$sql .= "where v_id = '$v_id' ";
						$sql .= "and enabled = 'true' ";
					}
					$sql .= "order by extension asc ";
					$prepstatement = $db->prepare(check_sql($sql));
					$prepstatement->execute();
					$result = $prepstatement->fetchAll(PDO::FETCH_NAMED);
					foreach ($result as &$row) {
						if ($row["enabled"] == "true") {
							$extension = $row["extension"];
							$extension_array[$extension]['v_id'] = $row["v_id"];
							$extension_array[$extension]['extension'] = $row["extension"];

							//$extension_array[$extension]['password'] = $row["password"];
							$extension_array[$extension]['user_list'] = $row["user_list"];
							$extension_array[$extension]['mailbox'] = $row["mailbox"];
							//$vm_password = $row["vm_password"];
							//$vm_password = str_replace("#", "", $vm_password); //preserves leading zeros
							//$_SESSION['extension_array'][$extension]['vm_password'] = $vm_password;
							$extension_array[$extension]['accountcode'] = $row["accountcode"];
							$extension_array[$extension]['effective_caller_id_name'] = $row["effective_caller_id_name"];
							$extension_array[$extension]['effective_caller_id_number'] = $row["effective_caller_id_number"];
							$extension_array[$extension]['outbound_caller_id_name'] = $row["outbound_caller_id_name"];
							$extension_array[$extension]['outbound_caller_id_number'] = $row["outbound_caller_id_number"];
							$extension_array[$extension]['vm_enabled'] = $row["vm_enabled"];
							$extension_array[$extension]['vm_mailto'] = $row["vm_mailto"];
							$extension_array[$extension]['vm_attach_file'] = $row["vm_attach_file"];
							$extension_array[$extension]['vm_keep_local_after_email'] = $row["vm_keep_local_after_email"];
							$extension_array[$extension]['user_context'] = $row["user_context"];
							$extension_array[$extension]['callgroup'] = $row["callgroup"];
							$extension_array[$extension]['auth_acl'] = $row["auth_acl"];
							$extension_array[$extension]['cidr'] = $row["cidr"];
							$extension_array[$extension]['sip_force_contact'] = $row["sip_force_contact"];
							//$extension_array[$extension]['enabled'] = $row["enabled"];
							$extension_array[$extension]['effective_caller_id_name'] = $row["effective_caller_id_name"];
						}
					}
					$_SESSION['extension_array'] = $extension_array;
				}

			//get a list of assigned extensions for this user
				include "v_calls_active_assigned_extensions_inc.php";

			//list all extensions
				if ($_SESSION['active_extensions_list_display'] == "false" && !ifgroup("superadmin")) {
					//hide the list when active_extensions_list_display is set to false, unless it's the superadmin
				}
				else {
					echo "<table width='100%' border='0' cellpadding='5' cellspacing='0'>\n";
					echo "<tr>\n";
					echo "<td valign='top'>\n";

					echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
					echo "<tr>\n";
					echo "<th width='50px;'>Ext</th>\n";
					if ($_SESSION['user_status_display'] == "false") {
						//hide the user_status when it is set to false
					}
					else {
						echo "<th>Status</th>\n";
					}
					echo "<th>Time</th>\n";
					if (ifgroup("admin") || ifgroup("superadmin")) {
						if (strlen(($_GET['rows'])) == 0) {
							echo "<th>CID Name</th>\n";
							echo "<th>CID Number</th>\n";
							echo "<th>Dest</th>\n";
							echo "<th>App</th>\n";
							echo "<th>Secure</th>\n";
						}
					}
					echo "<th>Name</th>\n";
					if (ifgroup("admin") || ifgroup("superadmin")) {
						if (strlen(($_GET['rows'])) == 0) {
							echo "<th>Options</th>\n";
						}
					}
					echo "</tr>\n";
					$x = 1;
					foreach ($_SESSION['extension_array'] as $row) {
						$v_id = $row['v_id'];
						$extension = $row['extension'];
						$enabled = $row['enabled'];
						$effective_caller_id_name = $row['effective_caller_id_name'];

						$found_extension = false;
						foreach ($channels_array as $row) {
							//set the php variables
								foreach ($row as $key => $value) {
									$$key = $value;
								}
							//check to see if the extension is found in the channel array
								if ($number == $extension) {
									$found_extension = true;
									break;
								}
						}

						if ($found_extension) {
							if ($application == "conference") { 
								$alt_color = "background-image: url('".PROJECT_PATH."/images/background_cell_active.gif";
							}
							switch ($application) {
							case "conference":
								$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_conference.gif');\"";
								break;
							case "fifo":
								$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_fifo.gif');\"";
								break;
							case "valet_park":
								$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_fifo.gif');\"";
								break;
							default:
								$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_active.gif');\"";
							}
							echo "<tr>\n";
							echo "<td class='".$rowstyle[$c]."' $style_alternate>$extension</td>\n";
							if ($_SESSION['user_status_display'] == "false") {
								//hide the user_status when it is set to false
							}
							else {
								echo "<td class='".$rowstyle[$c]."' $style_alternate>".$user_array[$extension]['user_status']."&nbsp;</td>\n";
							}
							echo "<td class='".$rowstyle[$c]."' $style_alternate width='20px;'>".$call_length."</td>\n";
							if (ifgroup("admin") || ifgroup("superadmin")) {
								if (strlen(($_GET['rows'])) == 0) {
									if (strlen($url) == 0) {
										echo "<td class='".$rowstyle[$c]."' $style_alternate>".$cid_name."</td>\n";
										echo "<td class='".$rowstyle[$c]."' $style_alternate>".$cid_num."</td>\n";
									}
									else {
										echo "<td class='".$rowstyle[$c]."' $style_alternate><a href='".$url."cid_name=".$cid_name."&cid_num=".$cid_num."' style='color: #444444;' target='_blank'>".$cid_name."</a></td>\n";
										echo "<td class='".$rowstyle[$c]."' $style_alternate><a href='".$url."cid_name=".$cid_name."&cid_num=".$cid_num."' style='color: #444444;' target='_blank'>".$cid_num."</a></td>\n";
									}
								}
							}
							if (ifgroup("admin") || ifgroup("superadmin")) {
								if (strlen(($_GET['rows'])) == 0) {
									echo "<td class='".$rowstyle[$c]."' $style_alternate>\n";
									echo "".$dest."<br />\n";
									echo "</td>\n";
									echo "<td class='".$rowstyle[$c]."' $style_alternate>\n";
									if ($application == "fifo") {
										echo "queue &nbsp;\n";
									}
									else {
										echo $application." &nbsp;\n";
									}
									echo "</td>\n";
									echo "<td class='".$rowstyle[$c]."' $style_alternate>\n";
									echo "".$secure."<br />\n";
									echo "</td>\n";
								}
							}
						}
						else {
							$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_light.gif');\"";
							echo "<tr>\n";
							echo "<td class='".$rowstyle[$c]."' $style_alternate>$extension</td>\n";
							if ($_SESSION['user_status_display'] == "false") {
								//hide the user_status when it is set to false
							}
							else {
								echo "<td class='".$rowstyle[$c]."' $style_alternate>".$user_array[$extension]['user_status']."&nbsp;</td>\n";
							}
							echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
							if (ifgroup("admin") || ifgroup("superadmin")) {
								if (strlen(($_GET['rows'])) == 0) {
									echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
									echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
									echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
									echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
									echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
								}
							}
						}

						echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
						echo "	".$effective_caller_id_name."&nbsp;\n";
						echo "</td>\n";

						if (ifgroup("admin") || ifgroup("superadmin")) {
							if (strlen(($_GET['rows'])) == 0) {
								if ($found_extension) {
									echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
										//transfer
											echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_transfer_cmd(escape('$uuid')));\">transfer</a>&nbsp;\n";
										//park
											echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_park_cmd(escape('$uuid')));\">park</a>&nbsp;\n";
										//hangup
											echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"confirm_response = confirm('Do you really want to hangup this call?');if (confirm_response){send_cmd('v_calls_exec.php?cmd=uuid_kill%20'+(escape('$uuid')));}\">hangup</a>&nbsp;\n";
										//record start/stop
											$tmp_file = $v_recordings_dir."/archive/".date("Y")."/".date("M")."/".date("d")."/".$uuid.".wav";
											if (file_exists($tmp_file)) {
												//stop
												echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_record_cmd(escape('$uuid'), 'active_extensions_', escape('$cid_num'))+'&uuid='+escape('$uuid')+'&action=record&action2=stop&prefix=active_extensions_&name='+escape('$cid_num'));\">stop record</a>&nbsp;\n";
											}
											else {
												//start
												echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_record_cmd(escape('$uuid'), 'active_extensions_', escape('$cid_num'))+'&uuid='+escape('$uuid')+'&action=record&action2=start&prefix=active_extensions_');\">start record</a>&nbsp;\n";
											}
										echo "	&nbsp;";
									echo "</td>\n";
								}
								else {
									echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
									echo "	&nbsp;";
									echo "</td>\n";
								}
							}
						}
						echo "</tr>\n";

						if ($y == $_GET['rows'] && $_GET['rows'] > 0) {
							$y = 0;
							echo "</table>\n";

							echo "</td>\n";
							echo "<td valign='top'>\n";

							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
							echo "<tr>\n";
							echo "<th>Ext</th>\n";
							if ($_SESSION['user_status_display'] == "false") {
								//hide the user_status when it is set to false
							}
							else {
								echo "<th>Status</th>\n";
							}
							echo "<th>Time</th>\n";
							if (ifgroup("admin") || ifgroup("superadmin")) {
								if ($_GET['rows'] == 0) {
									echo "<th>CID Name</th>\n";
									echo "<th>CID Number</th>\n";
									echo "<th>Dest</th>\n";
									echo "<th>App</th>\n";
									echo "<th>Secure</th>\n";
								}
							}
							echo "<th>Name</th>\n";
							if (ifgroup("admin") || ifgroup("superadmin")) {
								if ($_GET['rows'] == 0) {
									echo "<th>Options</th>\n";
								}
							}
							echo "</tr>\n";
						}
						$y++;
						if ($c==0) { $c=1; } else { $c=0; }
					}

				echo "</table>\n";

				echo "<br /><br />\n";

				//valet park
					echo "<table width='100%' border='0' cellpadding='5' cellspacing='0'>\n";
					echo "<tr>\n";
					echo "<th valign='top'>Park Extension</th>\n";
					echo "<th valign='top'>Time</th>\n";
					echo "<th valign='top'>CID Name</th>\n";
					echo "<th valign='top'>CID Number</th>\n";
					echo "</tr>\n";
					foreach ($valet_array as $row) {
						if (strlen($row['extension']) > 0) {
							if ($row['context'] == $v_domain || $row['context'] == "default") {
								echo "<tr>\n";
								echo "<td valign='top' class='".$rowstyle[$c]."' >*".$row['extension']."</td>\n";
								echo "<td valign='top' class='".$rowstyle[$c]."' >".$row['call_length']."</td>\n";
								echo "<td valign='top' class='".$rowstyle[$c]."' >".$row['cid_name']."</td>\n";
								echo "<td valign='top' class='".$rowstyle[$c]."' >".$row['cid_num']."</td>\n";
								echo "</tr>\n";
							}
						}
					}
					echo "<table>\n";
			}

		echo "<br /><br />\n";

		if ($user_status == "Available (On Demand)") {
			$user_status = "Available_On_Demand";
		}
		$user_status = str_replace(" ", "_", $user_status);
		echo "<span id='db_user_status' style='visibility:hidden;'>$user_status</span>\n";
		echo "<div id='cmd_reponse'>\n";
		echo "</div>\n";
	}
?>
