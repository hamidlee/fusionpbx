<?php
	//application details
		$apps[$x]['name'] = "Call Center";
		$apps[$x]['guid'] = '95788E50-9500-079E-2807-FD530B0EA370';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = '';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Call Center';
		$apps[$x]['menu'][0]['guid'] = '6C072B29-5B6C-49FC-008E-95E24C77DE99';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/call_center/v_call_center_queue.php';
		$apps[$x]['menu'][0]['groups'][] = 'agent';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'call_center_queues_view';
		$apps[$x]['permissions'][0]['groups'][] = 'agent';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'call_center_queues_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'call_center_queues_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'call_center_queues_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'call_center_agents_view';
		$apps[$x]['permissions'][0]['groups'][] = 'agent';
		$apps[$x]['permissions'][4]['groups'][] = 'admin';
		$apps[$x]['permissions'][4]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][5]['name'] = 'call_center_agents_add';
		$apps[$x]['permissions'][5]['groups'][] = 'admin';
		$apps[$x]['permissions'][5]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][6]['name'] = 'call_center_agents_edit';
		$apps[$x]['permissions'][6]['groups'][] = 'admin';
		$apps[$x]['permissions'][6]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][7]['name'] = 'call_center_agents_delete';
		$apps[$x]['permissions'][7]['groups'][] = 'admin';
		$apps[$x]['permissions'][7]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][8]['name'] = 'call_center_tiers_view';
		$apps[$x]['permissions'][0]['groups'][] = 'agent';
		$apps[$x]['permissions'][8]['groups'][] = 'admin';
		$apps[$x]['permissions'][8]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][9]['name'] = 'call_center_tiers_add';
		$apps[$x]['permissions'][9]['groups'][] = 'admin';
		$apps[$x]['permissions'][9]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][10]['name'] = 'call_center_tiers_edit';
		$apps[$x]['permissions'][10]['groups'][] = 'admin';
		$apps[$x]['permissions'][10]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][11]['name'] = 'call_center_tiers_delete';
		$apps[$x]['permissions'][11]['groups'][] = 'admin';
		$apps[$x]['permissions'][11]['groups'][] = 'superadmin';

	// CREATE TABLE v_call_center_agent 
		$apps[$x]['db'][0]['table'] = 'v_call_center_agent';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'call_center_agent_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'agent_name';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'agent_type';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'agent_call_timeout';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'agent_contact';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'agent_status';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'agent_logout';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'agent_max_no_answer';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'agent_wrap_up_time';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'agent_reject_delay_time';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][10]['description'] = '';
		$apps[$x]['db'][0]['fields'][11]['name'] = 'agent_busy_delay_time';
		$apps[$x]['db'][0]['fields'][11]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][11]['description'] = '';
		$apps[$x]['db'][0]['fields'][12]['name'] = 'agent_no_answer_delay_time';
		$apps[$x]['db'][0]['fields'][12]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][12]['description'] = '';

	// CREATE TABLE v_call_center_logs 
		$apps[$x]['db'][1]['table'] = 'v_call_center_logs';
		$apps[$x]['db'][1]['fields'][0]['name'] = 'cc_id';
		$apps[$x]['db'][1]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][1]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['description'] = '';
		$apps[$x]['db'][1]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][1]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][1]['description'] = '';
		$apps[$x]['db'][1]['fields'][2]['name'] = 'cc_queue';
		$apps[$x]['db'][1]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][2]['description'] = '';
		$apps[$x]['db'][1]['fields'][3]['name'] = 'cc_action';
		$apps[$x]['db'][1]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][3]['description'] = '';
		$apps[$x]['db'][1]['fields'][4]['name'] = 'cc_count';
		$apps[$x]['db'][1]['fields'][4]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][4]['description'] = '';
		$apps[$x]['db'][1]['fields'][5]['name'] = 'cc_agent';
		$apps[$x]['db'][1]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][5]['description'] = '';
		$apps[$x]['db'][1]['fields'][6]['name'] = 'cc_agent_system';
		$apps[$x]['db'][1]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][6]['description'] = '';
		$apps[$x]['db'][1]['fields'][7]['name'] = 'cc_agent_status';
		$apps[$x]['db'][1]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][7]['description'] = '';
		$apps[$x]['db'][1]['fields'][8]['name'] = 'cc_agent_state';
		$apps[$x]['db'][1]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][8]['description'] = '';
		$apps[$x]['db'][1]['fields'][9]['name'] = 'cc_agent_uuid';
		$apps[$x]['db'][1]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][9]['description'] = '';
		$apps[$x]['db'][1]['fields'][10]['name'] = 'cc_selection';
		$apps[$x]['db'][1]['fields'][10]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][10]['description'] = '';
		$apps[$x]['db'][1]['fields'][11]['name'] = 'cc_cause';
		$apps[$x]['db'][1]['fields'][11]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][11]['description'] = '';
		$apps[$x]['db'][1]['fields'][12]['name'] = 'cc_wait_time';
		$apps[$x]['db'][1]['fields'][12]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][12]['description'] = '';
		$apps[$x]['db'][1]['fields'][13]['name'] = 'cc_talk_time';
		$apps[$x]['db'][1]['fields'][13]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][13]['description'] = '';
		$apps[$x]['db'][1]['fields'][14]['name'] = 'cc_total_time';
		$apps[$x]['db'][1]['fields'][14]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][14]['description'] = '';
		$apps[$x]['db'][1]['fields'][15]['name'] = 'cc_epoch';
		$apps[$x]['db'][1]['fields'][15]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][15]['description'] = '';
		$apps[$x]['db'][1]['fields'][16]['name'] = 'cc_date';
		$apps[$x]['db'][1]['fields'][16]['type']['pgsql'] = 'timestamp';
		$apps[$x]['db'][1]['fields'][16]['type']['sqlite'] = 'date';
		$apps[$x]['db'][1]['fields'][16]['type']['mysql'] = 'timestamp';
		$apps[$x]['db'][1]['fields'][16]['description'] = '';
		$apps[$x]['db'][1]['fields'][17]['name'] = 'cc_agent_type';
		$apps[$x]['db'][1]['fields'][17]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][17]['description'] = '';
		$apps[$x]['db'][1]['fields'][18]['name'] = 'cc_member_uuid';
		$apps[$x]['db'][1]['fields'][18]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][18]['description'] = '';
		$apps[$x]['db'][1]['fields'][19]['name'] = 'cc_member_session_uuid';
		$apps[$x]['db'][1]['fields'][19]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][19]['description'] = '';
		$apps[$x]['db'][1]['fields'][20]['name'] = 'cc_member_cid_name';
		$apps[$x]['db'][1]['fields'][20]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][20]['description'] = '';
		$apps[$x]['db'][1]['fields'][21]['name'] = 'cc_member_cid_number';
		$apps[$x]['db'][1]['fields'][21]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][21]['description'] = '';
		$apps[$x]['db'][1]['fields'][22]['name'] = 'cc_agent_called_time';
		$apps[$x]['db'][1]['fields'][22]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][22]['description'] = '';
		$apps[$x]['db'][1]['fields'][23]['name'] = 'cc_agent_answered_time';
		$apps[$x]['db'][1]['fields'][23]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][23]['description'] = '';
		$apps[$x]['db'][1]['fields'][24]['name'] = 'cc_member_joined_time';
		$apps[$x]['db'][1]['fields'][24]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][24]['description'] = '';
		$apps[$x]['db'][1]['fields'][25]['name'] = 'cc_member_leaving_time';
		$apps[$x]['db'][1]['fields'][25]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][25]['description'] = '';
		$apps[$x]['db'][1]['fields'][26]['name'] = 'cc_bridge_terminated_time';
		$apps[$x]['db'][1]['fields'][26]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][26]['description'] = '';
		$apps[$x]['db'][1]['fields'][27]['name'] = 'cc_hangup_cause';
		$apps[$x]['db'][1]['fields'][27]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][27]['description'] = '';

	// CREATE TABLE v_call_center_queue 
		$apps[$x]['db'][2]['table'] = 'v_call_center_queue';
		$apps[$x]['db'][2]['fields'][0]['name'] = 'call_center_queue_id';
		$apps[$x]['db'][2]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][2]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][2]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][2]['fields'][0]['description'] = '';
		$apps[$x]['db'][2]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][2]['fields'][1]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][1]['description'] = '';
		$apps[$x]['db'][2]['fields'][2]['name'] = 'queue_name';
		$apps[$x]['db'][2]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][2]['description'] = '';
		$apps[$x]['db'][2]['fields'][3]['name'] = 'queue_extension';
		$apps[$x]['db'][2]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][3]['description'] = '';
		$apps[$x]['db'][2]['fields'][4]['name'] = 'queue_strategy';
		$apps[$x]['db'][2]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][4]['description'] = '';
		$apps[$x]['db'][2]['fields'][5]['name'] = 'queue_moh_sound';
		$apps[$x]['db'][2]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][5]['description'] = '';
		$apps[$x]['db'][2]['fields'][6]['name'] = 'queue_record_template';
		$apps[$x]['db'][2]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][6]['description'] = '';
		$apps[$x]['db'][2]['fields'][7]['name'] = 'queue_time_base_score';
		$apps[$x]['db'][2]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][7]['description'] = '';
		$apps[$x]['db'][2]['fields'][8]['name'] = 'queue_max_wait_time';
		$apps[$x]['db'][2]['fields'][8]['type'] = 'numeric';
		$apps[$x]['db'][2]['fields'][8]['description'] = '';
		$apps[$x]['db'][2]['fields'][9]['name'] = 'queue_max_wait_time_with_no_agent';
		$apps[$x]['db'][2]['fields'][9]['type'] = 'numeric';
		$apps[$x]['db'][2]['fields'][9]['description'] = '';
		$apps[$x]['db'][2]['fields'][10]['name'] = 'queue_tier_rules_apply';
		$apps[$x]['db'][2]['fields'][10]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][10]['description'] = '';
		$apps[$x]['db'][2]['fields'][11]['name'] = 'queue_tier_rule_wait_second';
		$apps[$x]['db'][2]['fields'][11]['type'] = 'numeric';
		$apps[$x]['db'][2]['fields'][11]['description'] = '';
		$apps[$x]['db'][2]['fields'][12]['name'] = 'queue_tier_rule_no_agent_no_wait';
		$apps[$x]['db'][2]['fields'][12]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][12]['description'] = '';
		$apps[$x]['db'][2]['fields'][13]['name'] = 'queue_discard_abandoned_after';
		$apps[$x]['db'][2]['fields'][13]['type'] = 'numeric';
		$apps[$x]['db'][2]['fields'][13]['description'] = '';
		$apps[$x]['db'][2]['fields'][14]['name'] = 'queue_abandoned_resume_allowed';
		$apps[$x]['db'][2]['fields'][14]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][14]['description'] = '';
		$apps[$x]['db'][2]['fields'][15]['name'] = 'queue_tier_rule_wait_multiply_level';
		$apps[$x]['db'][2]['fields'][15]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][15]['description'] = '';
		$apps[$x]['db'][2]['fields'][16]['name'] = 'queue_cid_prefix';
		$apps[$x]['db'][2]['fields'][16]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][16]['description'] = '';
		$apps[$x]['db'][2]['fields'][17]['name'] = 'queue_description';
		$apps[$x]['db'][2]['fields'][17]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][17]['description'] = '';

	// CREATE TABLE v_call_center_tier 
		$apps[$x]['db'][3]['table'] = 'v_call_center_tier';
		$apps[$x]['db'][3]['fields'][0]['name'] = 'call_center_tier_id';
		$apps[$x]['db'][3]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][3]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][3]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][3]['fields'][0]['description'] = '';
		$apps[$x]['db'][3]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][3]['fields'][1]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][1]['description'] = '';
		$apps[$x]['db'][3]['fields'][2]['name'] = 'agent_name';
		$apps[$x]['db'][3]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][2]['description'] = '';
		$apps[$x]['db'][3]['fields'][3]['name'] = 'queue_name';
		$apps[$x]['db'][3]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][3]['description'] = '';
		$apps[$x]['db'][3]['fields'][4]['name'] = 'tier_level';
		$apps[$x]['db'][3]['fields'][4]['type'] = 'numeric';
		$apps[$x]['db'][3]['fields'][4]['description'] = '';
		$apps[$x]['db'][3]['fields'][5]['name'] = 'tier_position';
		$apps[$x]['db'][3]['fields'][5]['type'] = 'numeric';
		$apps[$x]['db'][3]['fields'][5]['description'] = '';

?>
