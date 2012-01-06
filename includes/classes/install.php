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

//define the install class
	class install {

		var $result;
		var $v_id;
		var $v_domain;
		var $v_conf_dir;
		var $v_scripts_dir;
		var $v_sounds_dir;
		var $v_recordings_dir;

		function recursive_copy($src, $dst) {
			$dir = opendir($src);
			if (!$dir) {
				throw new Exception("recursive_copy() source directory '".$src."' does not exist.");
			}
			if (!is_dir($dst)) {
				if (!mkdir($dst)) {
					throw new Exception("recursive_copy() failed to create destination directory '".$dst."'");
				}
			}
			while(false !== ($file = readdir($dir))) {
				if (($file != '.') && ($file != '..')) {
					if (is_dir($src.'/'.$file)) {
						$this->recursive_copy($src.'/'.$file, $dst.'/'.$file);
					}
					else {
						if (!file_exists($dst.'/'.$file)) {
							//echo "copy(".$src."/".$file.", ".$dst."/".$file.");<br />\n";
							copy($src.'/'.$file, $dst.'/'.$file);
						}
					}
				}
			}
			closedir($dir);
		}

		function recursive_delete($dir) {
			if (strlen($dir) > 0) {
				foreach (glob($dir) as $file) {
					if (is_dir($file)) {
						rmdir($file);
						$this->recursive_delete("$file/*");
						//echo "rm dir: ".$file."\n";
					} else {
						//echo "delete file: ".$file."\n";
						unlink($file);
					}
				}
			}
		}

		function copy() {
			$this->copy_scripts();
			$this->copy_sounds();
			$this->copy_swf();
			$this->copy_phrases();
		}

		function copy_conf() {
			//make a backup copy of the conf directory
				clearstatcache();
				$src_dir = $this->v_conf_dir;
				$dst_dir = $this->v_conf_dir.'.orig';
				if (substr(strtoupper(PHP_OS), 0, 3) == "WIN") {
					$this->recursive_copy($src_dir, $dst_dir);
				}
				else {
					exec ('mv '.$src_dir.' '.$dst_dir);
					//exec ('cp -RLp '.$src_dir.' '.$dst_dir);
				}
			//remove the conf directory
				if (strlen($dst_dir)>0) {
					if (substr(strtoupper(PHP_OS), 0, 3) == "WIN") {
						$this->recursive_delete($this->v_conf_dir);
					}
					//else {
					//	exec ('rm -R '.$dst_dir);
					//}
				}
			//copy includes/templates/conf to the freeswitch/conf dir
				$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH."/includes/templates/conf";
				$dst_dir = $this->v_conf_dir;
				$this->recursive_copy($src_dir, $dst_dir);
			//create the dialplan/default.xml for single tenant or dialplan/domain.xml
				require_once "includes/classes/dialplan.php";
				$dialplan = new dialplan;
				$dialplan->v_id = $this->v_id;
				$dialplan->v_domain = $this->v_domain;
				$dialplan->v_conf_dir = $this->v_conf_dir;
				$dialplan->restore_advanced_xml();
		}

		function copy_scripts() {
			clearstatcache();
			$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/scripts';
			$dst_dir = $this->v_scripts_dir;
			if ($handle = opendir($src_dir)) {
				$i = 0;
				$files = array();
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && is_file($src_dir.'/'.$file)) {
						if (!file_exists($dst_dir.'/'.$file) ) {
							//copy the file if it does not exist in the destination directory
							if (copy($src_dir.'/'.$file, $dst_dir.'/'.$file)) {
								$this->result['copy']['scripts'][] = "copied from ".$src_dir."/".$file." to ".$dst_dir."/".$file."<br />\n";
							}
							else {
								$this->result['copy']['scripts'][] = "copy failed from ".$src_dir."/".$file." to ".$dst_dir."/".$file."<br />\n";
							}
						}
					}
				}
			}
			unset($src_dir, $dst_dir);
		}

		function copy_sounds() {
			clearstatcache();
			$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/sounds/en/us/callie/custom/8000';
			$dst_dir = $this->v_sounds_dir.'/en/us/callie/custom/8000';
			if ($handle = opendir($src_dir)) {
				$i = 0;
				$files = array();
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && is_file($src_dir.'/'.$file)) {
						if (!file_exists($dst_dir.'/'.$file) ) {
							//copy the file if it does not exist in the destination directory
							if (copy($src_dir.'/'.$file, $dst_dir.'/'.$file)) {
								$this->result['copy']['sounds']['8000'][] = "copied from ".$src_dir."/".$file." to ".$dst_dir."/".$file."<br />\n";
							}
							else {
								$this->result['copy']['sounds']['8000'][] = "copy failed from ".$src_dir."/".$file." to ".$dst_dir."/".$file."<br />\n";
							}
						}
					}
				}
			}

			$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/sounds/en/us/callie/custom/16000';
			$dst_dir = $this->v_sounds_dir.'/en/us/callie/custom/16000';
			if ($handle = opendir($src_dir)) {
				$i = 0;
				$files = array();
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && is_file($src_dir.'/'.$file)) {
						if (!file_exists($dst_dir.'/'.$file) ) {
							//copy the file if it does not exist in the destination directory
							if (copy($src_dir.'/'.$file, $dst_dir.'/'.$file)) {
								$this->result['copy']['sounds']['16000'][] = "copied from ".$src_dir."/".$file." to ".$dst_dir."/".$file."<br />\n";
							}
							else {
								$this->result['copy']['sounds']['16000'][] = "copy failed from ".$src_dir."/".$file." to ".$dst_dir."/".$file."<br />\n";
							}
						}
					}
				}
			}
		}

		function copy_swf() {
			clearstatcache();
			$file = "slim.swf";
			$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/htdocs';
			$dst_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/mod/recordings';
			if (copy($src_dir.'/'.$file, $dst_dir.'/'.$file)) {
				$this->result['copy']['swf'][] = "copied from ".$src_dir."/".$file." to ".$dst_dir."/".$file."<br />\n";
			}
			else {
				$this->result['copy']['swf'][] = "copy failed from ".$src_dir."/".$file." to ".$dst_dir."/".$file."<br />\n";
			}
		}

		function copy_phrases() {
			clearstatcache();
			$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH."/includes/templates/conf/lang";
			$dst_dir = $this->v_conf_dir."/lang";
			$this->recursive_copy($src_dir, $dst_dir);
		}

	}

//how to use the class
	//include "includes/classes/install.php";
	//$install = new install;
	//$install->v_id = $v_id;
	//$install->v_conf_dir = $v_conf_dir;
	//$install->v_scripts_dir = $v_scripts_dir;
	//$install->v_sounds_dir = $v_sounds_dir;
	//$install->v_recordings_dir = $v_recordings_dir;
	//$install->copy();
	//print_r($install->result);
?>