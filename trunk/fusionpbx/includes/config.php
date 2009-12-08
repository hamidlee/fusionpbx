<?php
/* $Id$ */
/*
	config.php
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

//-----------------------------------------------------
// settings:
//-----------------------------------------------------

	//set the database type
		$dbtype = 'sqlite'; //sqlite, mysql, pgsql, others with a manually created PDO connection

	//sqlite: the dbfilename and dbfilepath are automatically assigned however the values can be overidden by setting the values here.
		$dbfilename = 'fusionpbx.db'; //host name/ip address + '.db' is the default database filename
		$dbfilepath = 'C:\Mark\projects\fusionpbx\fusionpbx\Program\www\localhost\fusionpbx\secure'; //the path is determined by a php variable

	//mysql: database connection information
		//$dbhost = '';
		//$dbport = '';
		//$dbname = '';
		//$dbusername = '';
		//$dbpassword = '';

	//pgsql: database connection information
		//$dbhost = ''; //set the host only if the database is not local
		//$dbport = '';
		//$dbname = '';
		//$dbusername = '';
		//$dbpassword = '';

	//set the host ip or name that the will be used to communicate with the phone system
		$host = '127.0.0.1';

	//show errors
		ini_set('display_errors', '1');
		//error_reporting (E_ALL); // Report everything
		error_reporting (E_ALL ^ E_NOTICE); // Report everything
		//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ); //hide notices and warnings
//-----------------------------------------------------
// warning: do not edit below this line
//-----------------------------------------------------

	require_once "includes/lib_php.php";
	require "includes/lib_pdo.php";
	require_once "includes/lib_functions.php";
	require_once "includes/lib_switch.php";

?>