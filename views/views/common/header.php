<?php
$current_url = base_url(uri_string());
//to exit if not loged_in
$ci = &get_instance();
$ci->load->model('DBmodel');
$ci->DBmodel->check_isvalidated();
//echo $vaaa;
//die;
//page editor auth.
//$auth_page_editor=$ci->DBmodel->auth_page_editor();
//end #page-content {	min-height: auto !important;	}

$user_type = $this->session->userdata('user_typeid');
session_write_close();

$moduleName = "";
$pageUrl = $_SERVER['REQUEST_URI'];
// if(!empty($pageUrl)){
$moduleUrl = explode('/', $pageUrl);
// if (array_key_exists(2,$moduleUrl)){
$moduleName = $moduleUrl[2];
// }
// }
$parentLiStyle = 'style="margin-top: 12px; font-size: medium;"';
$highlightStyle = 'style="font-weight: bold;color: #a7451f;"';
$caselistStyle = $AssignmentsStyle = $writingassignmentStyle = $coronauploaderStyle = $anthemuploaderStyle = $BackOfficeStyle = $PpsAssignmentStyle = $TTStyle = "";
$caselistLiStyle = $AssignmentsLiStyle = $writingassignmentLiStyle = $coronauploaderLiStyle = $anthemuploaderLiStyle = $BackOfficeLiStyle = $PpsAssignmentLiStyle = $TTLiStyle = $parentLiStyle;

if ($moduleName == "caselist" || $moduleName == "casedetail") {
	$caselistStyle = $highlightStyle;
	$caselistLiStyle = 'style="margin-top: 12px; font-size: medium;border-radius: 4px;"';
} else if ($moduleName == "CaseAssignment" || $moduleName == "ReadyForAssignment") {
	$AssignmentsStyle = $highlightStyle;
	$AssignmentsLiStyle = 'style="margin-top: 12px; font-size: medium;border-radius: 4px;"';
} else if ($moduleName == "writingassignment") {
	$writingassignmentStyle = $highlightStyle;
	$writingassignmentLiStyle = 'style="margin-top: 12px; font-size: medium;border-radius: 4px;"';
} else if ($moduleName == "CoronaUploader") {
	$coronauploaderStyle = $highlightStyle;
	$coronauploaderLiStyle = 'style="margin-top: 12px; font-size: medium;border-radius: 4px;"';
} else if ($moduleName == "Tagging") {
	$TTStyle = $highlightStyle;
	$TTLiStyle = 'style="margin-top: 12px; font-size: medium;border-radius: 4px;"';
} else if ($moduleName == "BackOffice" || $moduleName == "BackOfficeDetail") {
	$BackOfficeStyle = $highlightStyle;
	$BackOfficeLiStyle = 'style="margin-top: 12px; font-size: medium;border-radius: 4px;"';
} else if ($moduleName == "PpsAssignment") {
	$PpsAssignmentStyle = $highlightStyle;
	$PpsAssignmentLiStyle = 'style="margin-top: 12px; font-size: medium;border-radius: 4px;"';
} else if ($moduleName == "anthemuploader") {
	$anthemuploaderStyle = $highlightStyle;
	$anthemuploaderLiStyle = 'style="margin-top: 12px; font-size: medium;border: 1px solid;border-radius: 4px;"';
}
//print $this->session->userdata('username'); border: 1px solid;
//print $user_type;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<style>
		/* Loading Spinner */
		#page-content-wrapper ul {
			display: inline-block;
		}

		#page-content-wrapper li {
			padding: 0 5px;
		}

		#page-content-wrapper .col-sm-2 {
			width: 26.66666667%;
		}

		.spinner {
			margin: 0;
			width: 70px;
			height: 18px;
			margin: -35px 0 0 -9px;
			position: absolute;
			top: 50%;
			left: 50%;
			text-align: center
		}

		.spinner>div {
			width: 18px;
			height: 18px;
			background-color: #333;
			border-radius: 100%;
			display: inline-block;
			-webkit-animation: bouncedelay 1.4s infinite ease-in-out;
			animation: bouncedelay 1.4s infinite ease-in-out;
			-webkit-animation-fill-mode: both;
			animation-fill-mode: both
		}

		.spinner .bounce1 {
			-webkit-animation-delay: -.32s;
			animation-delay: -.32s
		}

		.spinner .bounce2 {
			-webkit-animation-delay: -.16s;
			animation-delay: -.16s
		}

		@-webkit-keyframes bouncedelay {

			0%,
			80%,
			100% {
				-webkit-transform: scale(0.0)
			}

			40% {
				-webkit-transform: scale(1.0)
			}
		}

		@keyframes bouncedelay {

			0%,
			80%,
			100% {
				transform: scale(0.0);
				-webkit-transform: scale(0.0)
			}

			40% {
				transform: scale(1.0);
				-webkit-transform: scale(1.0)
			}
		}

		/* Style the tab */
		.tab {
			overflow: hidden;
			border: 1px solid #ccc;
			background-color: #f1f1f1;
		}

		/* Style the buttons that are used to open the tab content */
		.tab button {
			background-color: inherit;
			float: left;
			border: none;
			outline: none;
			cursor: pointer;
			padding: 14px 16px;
			transition: 0.3s;
		}

		/* Change background color of buttons on hover */
		.tab button:hover {
			background-color: #ddd;
		}

		/* Create an active/current tablink class */
		.tab button.active {
			background-color: #ccc;
		}

		/* Style the tab content */
		.tabcontent {
			display: none;
			padding: 6px 12px;
			border: 1px solid #ccc;
			border-top: none;
		}

		.tabcontent {
			animation: fadeEffect 1s;
			/* Fading effect takes 1 second */
		}

		*,
		*:before,
		*:after {
			box-sizing: border-box;
		}

		.tabset>input[type="radio"] {
			position: absolute;
			left: -200vw;
		}

		.tabset .tab-panel {
			display: none;
		}

		.tabset>input:first-child:checked~.tab-panels>.tab-panel:first-child,
		.tabset>input:nth-child(3):checked~.tab-panels>.tab-panel:nth-child(2),
		.tabset>input:nth-child(5):checked~.tab-panels>.tab-panel:nth-child(3),
		.tabset>input:nth-child(7):checked~.tab-panels>.tab-panel:nth-child(4),
		.tabset>input:nth-child(9):checked~.tab-panels>.tab-panel:nth-child(5),
		.tabset>input:nth-child(11):checked~.tab-panels>.tab-panel:nth-child(6),
		.tabset>input:nth-child(13):checked~.tab-panels>.tab-panel:nth-child(7),
		.tabset>input:nth-child(15):checked~.tab-panels>.tab-panel:nth-child(8),
		.tabset>input:nth-child(17):checked~.tab-panels>.tab-panel:nth-child(9),
		.tabset>input:nth-child(19):checked~.tab-panels>.tab-panel:nth-child(10),
		.tabset>input:nth-child(21):checked~.tab-panels>.tab-panel:nth-child(11),
		.tabset>input:nth-child(23):checked~.tab-panels>.tab-panel:nth-child(12),
		.tabset>input:nth-child(25):checked~.tab-panels>.tab-panel:nth-child(13),
		.tabset>input:nth-child(27):checked~.tab-panels>.tab-panel:nth-child(14),
		.tabset>input:nth-child(29):checked~.tab-panels>.tab-panel:nth-child(15),
		.tabset>input:nth-child(31):checked~.tab-panels>.tab-panel:nth-child(16) {
			display: block;
		}

		.tabset>label {
			position: relative;
			display: inline-block;
			padding: 15px 15px 25px;
			border: 1px solid transparent;
			border-bottom: 0;
			cursor: pointer;
			font-weight: 600;
		}

		.tabset>label::after {
			content: "";
			position: absolute;
			left: 15px;
			bottom: 10px;
			width: 22px;
			height: 4px;
			background: #8d8d8d;
		}

		.tabset>label:hover,
		.tabset>input:focus+label {
			color: #06c;
		}

		.tabset>label:hover::after,
		.tabset>input:focus+label::after,
		.tabset>input:checked+label::after {
			background: #06c;
		}

		.tabset>input:checked+label {
			border-color: #ccc;
			border-bottom: 1px solid #fff;
			margin-bottom: -1px;
			border-radius: 5px 5px 0 0;
		}

		.tab-panel {
			padding: 30px 0;
			border-top: 1px solid #ccc;
			color: black;
		}

		.tabset label {
			margin-bottom: initial;
		}

		#accordion,
		#tab_view {
			clear: both;
			padding: 1rem 0;
		}

		.accordion-buttons {
			margin: .5rem 0
		}

		/* Go from zero to full opacity */
		@keyframes fadeEffect {
			from {
				opacity: 0;
			}

			to {
				opacity: 1;
			}
		}

		.tab_pps_tbody td {
			width: 23px !important;
			vertical-align: middle !important;
		}

		.tab_all_case_pps_tbody td {
			width: 23px !important;
			vertical-align: middle !important;
		}

		.tab_all_case_pps_tbody select {
			width: 120px;
			vertical-align: middle !important;
		}

		.all_case_pps_tbody td {
			width: 23px !important;
			vertical-align: middle !important;
		}

		.all_case_pps_tbody select {
			width: 120px;
			vertical-align: middle !important;
		}

		.tab_pps_tbody input,
		select {
			width: 90px;
			vertical-align: middle !important;
		}

		.tab_all_case_pps_tbody_sp td {
			width: 23px !important;
			vertical-align: middle !important;
		}

		.tab_all_case_pps_tbody_sp select {
			width: 120px;
			vertical-align: middle !important;
		}

		.tab_all_case_pps_tbody_state td {
			width: 23px !important;
			vertical-align: middle !important;
		}

		.tab_all_case_pps_tbody_state select {
			width: 120px;
			vertical-align: middle !important;
		}

		#tab_all_case_pps_table_sp {
			display: block;
			overflow-x: auto;
		}

		#tab_all_case_pps_table_sp th {
			width: 10% !important;
			vertical-align: middle !important;
		}

		#tab_all_case_pps_table_state {
			display: block;
			overflow-x: auto;
		}

		#tab_all_case_pps_table_state th {
			width: 10% !important;
			vertical-align: middle !important;
		}

		#pps_sugar_table {
			display: block;
			overflow-x: auto;
		}

		#tab_all_case_pps_table {
			display: block;
			overflow-x: auto;
		}

		#tab_all_case_pps_table th {
			width: 10% !important;
			vertical-align: middle !important;
		}


		#pps_table,
		#tab_pps_table {
			display: block;
			overflow-x: auto;
		}

		#tab_pps_table th {
			width: 23px !important;
			vertical-align: middle !important;
		}

		#spouse_pps_table,
		#tab_spouse_pps_table {
			display: block;
			overflow-x: auto;
		}

		#tab_spouse_pps_table th {
			width: 23px !important;
			vertical-align: middle !important;
		}


		#tab_pps_state_pers_table {
			display: block;
			overflow-x: auto;
		}

		#tab_pps_state_pers_table th {
			width: 20px !important;
			vertical-align: middle !important;
		}


		#pps_state_pers_table {
			display: block;
			overflow-x: auto;
		}

		#pps_state_pers_table th {
			width: 20px !important;
			vertical-align: middle !important;
		}

		/*button#deleterow {
			margin-top: 10px;
			margin-left: 14px;
		}*/

		.pps_tbody td {
			width: 90px !important;
			vertical-align: middle !important;
		}

		.pps_tbody input,
		select {
			width: 110px;
			vertical-align: middle !important;
		}

		.spouse_pps_tbody td {
			width: 90px !important;
			vertical-align: middle !important;
		}

		.spouse_pps_tbody input,
		select {
			width: 110px;
			vertical-align: middle !important;
		}

		.tab_spouse_pps_tbody td {
			width: 90px !important;
			vertical-align: middle !important;
		}

		.tab_spouse_pps_tbody input,
		select {
			width: 110px;
			vertical-align: middle !important;
		}

		.tab_pps_state_pers_tbody td {
			width: 80px !important;
			vertical-align: middle !important;
		}

		.tab_pps_state_pers_tbody input,
		select {
			width: 100px;
			vertical-align: middle !important;
		}

		.pps_state_pers_tbody td {
			width: 80px !important;
			vertical-align: middle !important;
		}

		.pps_state_pers_tbody input,
		select {
			width: 100px;
			vertical-align: middle !important;
		}

		.box-comment {
			border: 0px solid !important;
		}

		div#lastcontact {
			border-bottom: 1px solid #cfe3f0;
		}

		.tf_tab {
			width: 60%;
		}

		.tf {
			width: 60%;
		}

		p:last-child {
			border-bottom: none;
			word-wrap: break-word;
		}

		.assets_tab .td {
			text-align: center;
		}

		.assets_tab .col1 {
			text-align: left !important;
		}

		.rec_tbody .td {
			text-align: center;
		}

		.activity_tbody .td {
			text-align: center;
		}

		#review_box {
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}

		#note h4 {
			padding: 16px 0 18px;
		}

		#note p#note_msg2 {
			margin-top: 105px;
		}

		#note p#note_msg {
			margin-top: 105px;
		}

		.csed_tbody .col5 {
			text-align: center !important;
		}

		.edit_income_btn {
			display: block;
			float: right;
			position: relative;
			right: 93px;
			top: -23px;
		}

		#pps_note_box {
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}

		#note_pps h4 {
			padding: 16px 0 18px;
		}

		#note_pps2 h4 {
			padding: 16px 0 18px;
		}

		#tab_pps_state_pers_note_pps h4 {
			padding: 16px 0 18px;
		}

		#pps_state_pers_note_pps h4 {
			padding: 16px 0 18px;
		}

		#note_pps p#note_pps_msg {
			margin-top: 114px;
			margin-bottom: 5px;
		}

		#note_txt_pps {
			margin-top: 7px;
			margin-bottom: 8px;
		}

		#note_pps p#tab_note_pps_msg {
			margin-top: 50px;
		}

		#tab_note_txt_pps {
			margin-top: 7px;
			margin-bottom: 8px;
		}

		#tab_state_pers_note_txt_pps {
			margin-top: 7px;
			margin-bottom: 8px;
		}

		#state_pers_note_txt_pps {
			margin-top: 7px;
			margin-bottom: 8px;
		}

		#spouse_pps_note_box {
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}

		#spouse_note_pps h4 {
			padding: 16px 0 18px;
		}

		#spouse_note_pps_pps2 h4 {
			padding: 16px 0 18px;
		}

		#spouse_note_pps {
			margin-top: 50px;
		}

		#note_txt_pps_spouse {
			margin-top: 7px;
		}

		#spouse_note_pps p#tab_note_pps_msg {
			margin-top: 114px;
		}

		#tab_note_txt_pps {
			margin-top: 7px;
		}

		#tab_spouse_pps_note_box {
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}

		#tab_spouse_note_pps2 h4 {
			padding: 16px 0 18px;
		}

		#tab_spouse_note_pps2 p#note_spouse_pps_msg {
			margin-top: 50px;
		}

		#tab_note_txt_pps_spouse {
			margin-top: 7px;
			margin-bottom: 15px;
		}


		#btnSavePPS {
			margin-top: 7px;
		}

		#btnSavePPSNote {
			margin-top: 7px;
		}

		#btnSaveSpousePPS {
			margin-top: -59px;
		}

		#btnSavePPSSpouseTab {
			margin-top: 7px;
		}

		#btnSavePPSStatePers {
			margin-top: 7px;
		}

		#btnSavePPSStatePersNote {
			margin-top: 7px;
		}

		.compl_sum {
			display: block;
			float: right;
			position: relative;
			right: 0px;
			top: -2px;
		}

		.edit_oth_exp1 {
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}

		.edit_oth_exp2 {
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}

		.edit_oth_exp3 {
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}

		.edit_oth_exp4 {
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}

		.tab_edit_oth_exp1 {
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}

		.tab_edit_oth_exp2 {
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}

		.tab_edit_oth_exp3 {
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}

		.tab_edit_oth_exp4 {
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}


		input.form-control.input_oth_exp1 {
			width: 63%;
			margin-top: -8px;
			margin-left: 96px;
			height: 30px;
		}

		input.form-control.input_oth_exp2 {
			width: 63%;
			margin-top: -8px;
			margin-left: 96px;
			height: 30px;
		}

		input.form-control.input_oth_exp3 {
			width: 63%;
			margin-top: -8px;
			margin-left: 96px;
			height: 30px;
		}

		input.form-control.input_oth_exp4 {
			width: 63%;
			margin-top: -8px;
			margin-left: 96px;
			height: 30px;
		}

		input.form-control.tab_input_oth_exp1 {
			width: 63%;
			margin-top: -8px;
			margin-left: 100px;
			height: 30px;
		}

		input.form-control.tab_input_oth_exp2 {
			width: 63%;
			margin-top: -8px;
			margin-left: 100px;
			height: 30px;
		}

		input.form-control.tab_input_oth_exp3 {
			width: 63%;
			margin-top: -8px;
			margin-left: 100px;
			height: 30px;
		}

		input.form-control.tab_input_oth_exp4 {
			width: 63%;
			margin-top: -8px;
			margin-left: 100px;
			height: 30px;
		}

		input#save_expenses {
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}

		input#save_summary {
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}

		input#tab_save_summary {
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}

		input.form-control.input_di {
			margin-top: -9px;
			width: 79%;
			height: 32px;
			margin-left: 42px;
		}

		input.form-control.input_cr_fee {
			margin-top: -9px;
			width: 79%;
			height: 32px;
			margin-left: 42px;
		}

		input.form-control.add_service_fee {
			margin-top: -9px;
			width: 65%;
			height: 32px;
			margin-left: 72px;
		}

		input.form-control.input_sc {
			margin-top: -9px;
			width: 70%;
			height: 32px;
			margin-left: 67px;
		}

		input.form-control.tab_input_di {
			margin-top: -9px;
			width: 68%;
			height: 32px;
			margin-left: 71px;
		}

		input.form-control.tab_input_cr_fee {
			margin-top: -9px;
			width: 68%;
			height: 32px;
			margin-left: 71px;
		}

		input.form-control.tab_input_anthem_fee {
			margin-top: -9px;
			width: 68%;
			height: 32px;
			margin-left: 71px;
		}

		/* Added on 23-11-23 https://trello.com/c/RVmA8RNV/691-tra-anthem-fee-field-name-updates */
		input.form-control.tab_input_service_partner_fee {
			margin-top: -9px;
			width: 68%;
			height: 32px;
			margin-left: 71px;
		}

		/* End added on 23-11-23 */
		input.form-control.tab_add_service_fee {
			margin-top: -9px;
			width: 68%;
			height: 32px;
			margin-left: 71px;
		}

		input.form-control.tab_input_cr_recmd {
			margin-top: -9px;
			width: 68%;
			height: 32px;
			margin-left: 71px;
		}

		input.form-control.input_cr_recmd {
			margin-top: -9px;
			width: 80%;
			height: 32px;
			margin-left: 41px;
		}

		input.form-control.tab_input_sc {
			margin-top: -9px;
			width: 70%;
			height: 32px;
			margin-left: 67px;
		}

		select#recmd {
			width: 76%;
			margin-left: 56px;
			margin-top: -9px;
		}

		select#tab_recmd {
			width: 76%;
			margin-left: 56px;
			margin-top: -9px;
		}

		select#ac_stat {
			width: 67%;
			margin-left: 70px;
			margin-top: -9px;
		}

		select#tab_ac_stat {
			width: 69%;
			margin-left: 68px;
			margin-top: -9px;
		}

		select#compliant {
			margin-top: -9px;
			margin-left: 36px;
			width: 83%;
		}

		select#tab_compliant {
			margin-top: -9px;
			margin-left: 34px;
			width: 85%;
		}

		select#analysis1 {
			width: 100%;
			margin-left: 11px;
			margin-top: -9px;
		}

		select#tab_analysis1 {
			width: 100%;
			margin-left: 11px;
			margin-top: -9px;
		}

		select#analysis2 {
			width: 100%;
			margin-left: 11px;
			margin-top: -9px;
		}

		select#tab_analysis2 {
			width: 100%;
			margin-left: 11px;
			margin-top: -9px;
		}

		select#analysis3 {
			width: 100%;
			margin-left: 11px;
			margin-top: -9px;
		}

		select#tab_analysis3 {
			width: 100%;
			margin-left: 11px;
			margin-top: -9px;
		}

		/* Added on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record */
		select#service_source {
			margin-top: -9px;
			margin-left: 34px;
			width: 85%;
		}

		/* end Added on 23-11-2023 */
		input#save_pps_data {
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}

		input#save_tab_pps_info_data,
		input#save_tab_pps_details_data,
		input#save_tab_pps_cp_data,
		input#save_tab_pps_sr_data,
		input#save_tab_pps_rp_data,
		input#save_tab_spouse_pps_info_data,
		input#save_tab_spouse_pps_data,
		input#save_tab_spouse_pps_cp_data,
		input#save_tab_spouse_pps_sr_data,
		input#save_tab_spouse_pps_rp_data {
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}

		input#save_tab_pps_state_pers_data {
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}

		input#save_pps_state_pers_data {
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}

		input#save_spouse_pps_data {
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}

		/*input#save_tab_spouse_pps_data{
			width: 6%;
			background: #2cb31c;
			color: #fff;
			margin-left: 50%;
			margin-top: 15px;
		}*/

		input.form-control1.casenum {
			color: #000;
			font-weight: bold;
			height: 33px;
			width: 12% !important;
			margin-left: 5px;
		}

		input.form-control1.clientname {
			color: #000;
			font-weight: bold;
			height: 33px;
			width: 23% !important;
			/* Updated on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements */
			margin-left: 5px;
		}

		input.form-control1.casestatus {
			color: #000;
			font-weight: bold;
			height: 33px;
			width: 23% !important;
		}

		p#tab_note_pps_msg {
			/*float: left;*/
			margin-top: 116px;
			margin-bottom: 10px;
		}

		.close_rec {
			style="float: right !important"
		}

		select#case_status {
			width: 188px;
			margin-top: -35px;
			margin-left: 56%;
			/* 60 Updated on 22-12-2023 https://trello.com/c/MC2tSrcs/800-taxstatus-deep-link */
		}

		button#refresh_caseid_data {
			margin-left: 6px;
			/* -80 Updated on 22-12-2023 https://trello.com/c/MC2tSrcs/800-taxstatus-deep-link */
			margin-right: 5px;
		}

		p#tab_note_pps_msg_spouse {
			margin-top: 115px;
			margin-bottom: 10px;
		}

		p#note_spouse_pps_msg {
			margin-top: 115px;
			margin-bottom: 10px;
		}

		#div#example {
			margin-right: 20px;
			margin-left: 14px;
		}

		textarea#note_txt2 {
			margin-bottom: 10px;
		}

		#div#tabsumnote {
			margin-right: 20px;
			margin-left: 14px;
		}

		textarea#note_txt {
			margin-bottom: 10px;
		}

		.past_notes {
			margin-left: 15px;
			margin-bottom: 10px;
			width: 81% !important;
		}

		.editnote {
			margin-right: 14%;
			float: right;
			margin-top: -8%;
		}

		.savenote {
			margin-right: 14%;
			float: right;
			margin-top: -8%;
		}

		.teditnote {
			margin-right: 14%;
			float: right;
			margin-top: -8%;
		}

		.tsavenote {
			margin-right: 14%;
			float: right;
			margin-top: -8%;
		}

		.tdeletenote {
			margin-right: 11%;
			float: right;
			margin-top: -8%;
		}

		.deletenote {
			margin-right: 11%;
			float: right;
			margin-top: -8%;
		}

		#div#pps_notes {
			margin-right: 20px;
			margin-left: 14px;
		}

		textarea#note_txt_pps {
			margin-bottom: 10px;
		}

		.past_pps_notes {
			margin-left: 15px;
			margin-bottom: 5px !importent;
			width: 81% !important;
		}

		.editppsnote {
			margin-left: 85%;
			margin-top: -15%;
		}

		.saveppsnote {
			margin-left: 87%;
			margin-top: -15%;
		}

		.teditppsnote {
			margin-left: 85%;
			margin-top: -15%;
		}

		.tsaveppsnote {
			margin-left: 87%;
			margin-top: -15%;
		}

		.deleteppsnote {
			margin-left: 87.5%;
			margin-top: -18%;
		}

		.tdeleteppsnote {
			margin-left: 87.5%;
			margin-top: -18%;
		}

		#div#pps_spouse_notes {
			margin-right: 20px;
			margin-left: 14px;
		}

		textarea#note_txt_pps_spouse {
			margin-bottom: 10px;
			margin-top: -37px;
		}

		.past_pps_spouse_notes {
			margin-left: 15px;
			margin-bottom: 5px !importent;
			width: 81% !important;
		}

		.editppsspousenote {
			margin-left: 85%;
			margin-top: -15%;
		}

		.saveppsspousenote {
			margin-left: 87%;
			margin-top: -15%;
		}

		.teditppsspousenote {
			margin-left: 85%;
			margin-top: -15%;
		}

		.tsaveppsspousenote {
			margin-left: 87%;
			margin-top: -15%;
		}

		.deleteppsspousenote {
			margin-left: 87.5%;
			margin-top: -18%;
		}

		.tdeleteppsspousenote {
			margin-left: 87.5%;
			margin-top: -18%;
		}

		.oth_expense_input_tab_text {
			margin-top: -9px;

		}

		.oth_expense_input_text {
			margin-top: -9px;

		}

		.oth_expense_input_tab_val {
			margin-top: -9px;
			width: 130px !important;
		}

		.tab_expense_input_v {
			margin-top: -9px;
			width: 130px !important;

		}

		.expense_input_v {
			margin-top: -9px;
			width: 130px !important;

		}

		.oth_expense_input_val {
			margin-top: -9px;
			width: 130px !important;
		}

		.expense_input {
			margin-top: -9px;

		}

		.tab_expense_input {
			margin-top: -9px;
		}

		.expand-all {
			margin-right: 5px;
		}

		#view_cr {
			margin-left: 73%;
			/* 77 Updated on 22-12-2023 https://trello.com/c/MC2tSrcs/800-taxstatus-deep-link */
			margin-top: -55px;
		}

		#services_table {
			display: block;
			overflow-x: auto;
		}

		#services_table th {

			vertical-align: middle !important;
		}

		.services_tbody td {
			min-width: 90px;
			text-align: center;
			vertical-align: middle !important;
		}

		#acc_services_table {
			display: block;
			overflow-x: auto;
		}

		#acc_services_table th {
			width: 23px !important;
			vertical-align: middle !important;
		}

		.acc_services_tbody td {
			min-width: 90px;
			text-align: center;
			vertical-align: middle !important;
		}

		.caselist td,
		th {
			width: 14%;
		}

		.past_pps_notessp {
			margin-left: 15px;
			margin-bottom: 5px !importent;
			width: 81% !important;
		}

		.teditppsnotesp {
			margin-left: 85%;
			margin-top: -15%;
		}

		.tdeleteppsnotesp {
			margin-left: 87.5%;
			margin-top: -18%;
		}

		#tab_pps_state_pers_note_box {
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}

		#pps_state_pers_note_box {
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}

		#btnSavePPSStatePers {
			margin-top: 7px;
		}

		#btnSavePPSStatePersAcc {
			margin-top: 7px;
		}

		.tsaveppsnotesp {
			margin-left: 87%;
			margin-top: -18%;
		}

		.editppsnotesp {
			margin-left: 85%;
			margin-top: -15%;
		}

		.saveppsnotesp {
			margin-left: 87%;
			margin-top: -18%;
		}

		.btn-pps-edit {
			background: #00ceb4;
			color: #fff;
		}

		.btn-pps-save {
			background: red;
			color: #fff;
		}

		#tab_delete_extra_exp {
			margin-top: -26px;
			float: right;
		}

		#delete_extra_exp {
			margin-top: -26px;
			float: right;
		}

		.assignment_cases {
			margin-left: 14px !important;
			margin-top: 7px !important;
			zoom: 1.6;
		}

		#assign {
			width: 50%;
		}

		#selected_assign_user_id {
			width: 30%;
			float: left !important;
		}

		#final_assign {
			width: 50%;
			margin-top: 20px;
		}

		#final_assign_user_id {
			width: 30%;
			float: left !important;
		}

		#batch_id {
			width: 30%;
			float: left !important;
		}

		#selected_user_id {
			width: 30%;
			float: left !important;
		}

		#mass_assign_to {
			margin-left: 5%;
		}

		#mass_assign_to_btn {
			margin-left: 5%;
		}

		#complete_assignment {
			float: right !important;
		}

		#reg_js_img {
			position: fixed;
			top: 45%;
			left: 40%;
			z-index: 1111;
		}

		#load_img {
			position: fixed;
			top: 45%;
			left: 40%;
			z-index: 1111;
		}

		#test_load_img {
			position: fixed;
			top: 45%;
			left: 40%;
			z-index: 1111;
		}

		#load_imgcsv {
			position: fixed;
			top: 45%;
			left: 40%;
			z-index: 1111;
		}

		table.case_assign td {
			color: #000;
		}

		table.so_assign td {
			color: #000;
		}

		#cid {
			margin: 0;
		}

		.dt-buttons {
			float: left;
			padding-right: 20px;
		}

		#datatable-tabletools_length select {
			width: 8%;
		}

		a.paginate_button {
			background: #00ceb4;
			padding: 5px 10px;
			border: 1px solid #e9e9e9;
			margin: 2px;
			color: #fff;
			cursor: pointer;
		}

		a.paginate_button.current {
			background: red;
		}

		a.paginate_button:hover {
			color: #fff;
			text-decoration: none;
		}

		.case_assign {
			display: block;
			overflow-x: auto;
		}

		.case_assign th {
			min-width: 130px !important;
			vertical-align: middle !important;
		}

		#so_assign_tbody td {
			min-width: 130px !important;
			vertical-align: middle !important;
		}

		#write_assign_tbody td {
			min-width: 130px !important;
			vertical-align: middle !important;
		}

		div#instruct_pps2 {
			padding: 16px 0 18px;
			margin-bottom: 10px;
		}

		textarea#tab_pps_instruct_note {
			margin-bottom: 10px;
		}

		textarea#pps_instruct_note {
			margin-bottom: 10px;
		}

		textarea#tab_pps_spouse_instruct_note {
			margin-bottom: 10px;
		}

		textarea#pps_spouse_instruct_note {
			margin-bottom: 10px;
		}

		textarea#tab_pps_state_instruct_note {
			margin-bottom: 10px;
		}

		textarea#pps_state_instruct_note {
			margin-bottom: 10px;
		}

		.editppsinstruct {
			margin-top: 65px;
			margin-left: 20px;
			color: #fff;
		}

		.saveppsinstruct {
			margin-top: 65px;
			margin-left: 20px;
			color: #fff;
		}

		input.form-control.tab_prelim_fee {
			margin-top: -9px;
			width: 68%;
			height: 32px;
			margin-left: 71px;
		}

		input.form-control.prelim_fee {
			margin-top: -9px;
			width: 70%;
			height: 32px;
			margin-left: 66px;
		}

		.pps_instruct_list {
			min-width: 220px;
		}

		#tab_ths_summary_table {
			display: block;
			overflow-x: auto;
		}

		#tab_ths_summary_table th {
			width: 23px !important;
			vertical-align: middle !important;
		}

		.tab_services {
			display: inline-block;
			padding: 5px;
			width: 19.7%;
			vertical-align: middle;
			text-align: left;
		}

		#loader {
			position: fixed;
			top: 45%;
			left: 40%;
			z-index: 1111;
		}

		#child_services_table {
			display: block;
			overflow-x: auto;
		}

		#child_services_table th {
			width: 10% !important;
			vertical-align: middle !important;
		}

		.child_services_tbody select {
			width: 150px;
			vertical-align: middle !important;
		}

		.child_services_tbody input {
			width: 30;
			vertical-align: middle !important;
		}

		.child_services_tbody td {
			width: 23px !important;
			vertical-align: middle !important;
		}

		#assign_div {
			width: 50%;
		}

		#mass_assign_to_btn_pps {
			margin-left: 5%;
		}

		#selected_user_id_pps {
			width: 30%;
			float: left !important;
		}

		table.pps_assign td {
			color: #000;
		}

		.pps_assign_tbody td {
			width: 23px !important;
			vertical-align: middle !important;
		}

		.pps_assign th {
			width: 10% !important;
			vertical-align: middle !important;
		}

		button#view_tax_status {
			margin-left: -71px;
		}

		button#mycase_transcript {
			margin-left: -176px;
			margin-right: 75px;
		}
		
		button#mycase_sync {
			margin-left: -200px;
			margin-right: 180px;
		}

		/* Added on 22-12-2023 https://trello.com/c/MC2tSrcs/800-taxstatus-deep-link */
	</style>
	<!-- width: 23px !important;   -->
	<meta charset="UTF-8">
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
	<title><?php if (isset($title)) echo $title; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<!-- Favicons -->
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/icons/CtaxLogo.ico">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css">
	<!-- HELPERS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/helpers/animate.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/helpers/backgrounds.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/helpers/typography.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/helpers/utils.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/helpers/colors.css">
	<!-- ELEMENTS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/buttons.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/content-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/forms.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/images.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/info-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/invoice.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/loading-indicators.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/menus.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/panel-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/response-messages.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/responsive-tables.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/ribbon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/social-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/tables.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/tile-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/elements/timeline.css">

	<!-- ICONS -->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icons/fontawesome/fontawesome.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icons/linecons/linecons.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icons/spinnericon/spinnericon.css">

	<!-- WIDGETS -->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/accordion-ui/accordion.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/calendar/calendar.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/carousel/carousel.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/chosen/chosen.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/colorpicker/colorpicker.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/datatable/datatable.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/datepicker/datepicker.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/datepicker-ui/datepicker.css">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/dialog/dialog.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/dropdown/dropdown.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/dropzone/dropzone.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/file-input/fileinput.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/input-switch/inputswitch.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/input-switch/inputswitch-alt.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/ionrangeslider/ionrangeslider.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/jcrop/jcrop.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/jgrowl-notifications/jgrowl.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/loading-bar/loadingbar.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/maps/vector-maps/vectormaps.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/markdown/markdown.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/modal/modal.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/multi-select/multiselect.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/multi-upload/fileupload.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/nestable/nestable.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/noty-notifications/noty.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/popover/popover.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/pretty-photo/prettyphoto.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/progressbar/progressbar.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/range-slider/rangeslider.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/slidebars/slidebars.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/slider-ui/slider.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/summernote-wysiwyg/summernote-wysiwyg.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/tabs-ui/tabs.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/theme-switcher/themeswitcher.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/timepicker/timepicker.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/tocify/tocify.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/tooltip/tooltip.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/touchspin/touchspin.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/uniform/uniform.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/wizard/wizard.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/widgets/xeditable/xeditable.css">

	<!-- SNIPPETS -->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/snippets/chat.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/snippets/files-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/snippets/login-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/snippets/notification-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/snippets/progress-box.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/snippets/todo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/snippets/user-profile.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/snippets/mobile-navigation.css">

	<!-- APPLICATIONS -->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/applications/mailbox.css">

	<!-- Admin theme -->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/themes/admin/layout.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/themes/admin/color-schemes/default.css?v=<?php echo time(); ?>">

	<!-- Components theme -->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/themes/components/default.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/themes/components/border-radius.css">

	<!-- Admin responsive -->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/helpers/responsive-elements.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/helpers/admin-responsive.css">

	<!-- Custom CSS
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/custom.css"> -->

	<!-- JS Core -->

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/jquery-core.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/jquery-ui-core.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/jquery-ui-widget.js"></script>
	<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/jquery-ui-mouse.js"></script>-->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/jquery-ui-position.js"></script>
	<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/transition.js"></script>-->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/modernizr.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/jquery-cookie.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/widgets/jgrowl-notifications/jgrowl.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>	 -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js-core/sweetalert.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

	<script type="text/javascript">
		$(window).load(function() {
			setTimeout(function() {
				$('#loading').fadeOut(400, "linear");
			}, 300);
		});
	</script>

</head>

<body class="nav-md">
	<!--<div id="sb-site">-->
	<div id="loading">
		<div class="spinner">
			<div class="bounce1"></div>
			<div class="bounce2"></div>
			<div class="bounce3"></div>
		</div>
	</div>
	<div id="page-wrapper">

		<div id="page-header" class="bg-blue font-inverse main-header wow fadeInDown">
			<div id="mobile-navigation">
				<a href="<?php //echo base_url().'Reports'; 
							?>" class="logo-content-small" title="CTax Reporting"></a>
			</div>
			<div id="header-logo" class="logo-bg">
				<a href="<?php //echo base_url().'Reports'; 
							?>" class="logo-content-big" title="CTax Reporting">
					Reports
				</a>

			</div>
			<div id="header-nav-left">
				<div class="navbar navbar-inverse" role="banner">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

					</div>
					<div class="collapse navbar-collapse">

						<ul class="nav navbar-nav navbar-right">
							<?php if ($no_header != 1) { ?>
								<li <?php echo $caselistLiStyle; ?>>
									<a href="<?php echo base_url(); ?>index.php/caselist/records" title="Cases">
										<span <?php echo $caselistStyle; ?>>Case List</span>
									</a>
								</li>
								<?php
								if ($user_role == 1)
								//if($user_type == '1')
								{ ?>

									<!-- <li style="margin-top: 12px; font-size: medium;">
										<a href="<?php //echo base_url();
													?>index.php/CaseAssignment/records" title="Users">

											<span>Case Assignment</span>
										</a> $AssignmentsLiStyle
										</li> -->

									<li <?php echo $AssignmentsLiStyle; ?> class="dropdown">
										<a href="#" title="Assignments">
											<span <?php echo $AssignmentsStyle; ?>>Assignments</span>
										</a>

										<ul class="dropdown-menu">
											<li style="margin-top: 12px; font-size: medium;">
												<a href="<?php echo base_url(); ?>index.php/CaseAssignment/records" title="Case Assignments">
													<span>Sales Assignment</span>
												</a>
											</li>
											<li style="margin-top: 12px; font-size: medium;">
												<a href="<?php echo base_url(); ?>index.php/ReadyForAssignment/records" title="Ready for Assignment">
													<span>Ready for Assignment</span>
												</a>
											</li>
										</ul>
									</li>

								<?php }

								if ($write_assignment_role == 1)
								//if($user_type == '1')
								{ ?>

									<li <?php echo $writingassignmentLiStyle; ?>>
										<a href="<?php echo base_url(); ?>index.php/writingassignment/records" title="Users">
											<span <?php echo $writingassignmentStyle; ?>>Writing Assignment</span>
										</a>
									</li>

								<?php }
								if ($user_role == 1 || $pps_assign_role == 1) { ?>
									<li <?php echo $PpsAssignmentLiStyle; ?>>
										<a href="<?php echo base_url(); ?>index.php/PpsAssignment/records" title="PPSAssignment">
											<span <?php echo $PpsAssignmentStyle; ?>>PPS Assignment</span>
										</a>
									</li>

								<?php }
								if ($user_role == 1  || $back_office_admin == 1) { ?>
									<li <?php echo $BackOfficeLiStyle; ?>>
										<a href="<?php echo base_url(); ?>index.php/BackOffice/records" title="BackOffice">

											<span <?php echo $BackOfficeStyle; ?>>Back Office Assignment</span>
										</a>
									</li>
								<?php }
								if ($user_role == 1) { ?>
									<li <?php echo $coronauploaderLiStyle; ?>>
										<a href="<?php echo base_url(); ?>index.php/CoronaUploader/records" title="CoronaUploader">
											<span <?php echo $coronauploaderStyle; ?>>Corona CR Uploader</span>
										</a>
									</li>

									<li <?php echo $anthemuploaderLiStyle; ?>>
										<a href="<?php echo base_url(); ?>index.php/anthemuploader/records" title="AnthemUploader">
											<span <?php echo $anthemuploaderStyle; ?>>Anthem</span>
										</a>
									</li>

									<li <?php echo $TTLiStyle; ?>>
										<a href="<?php echo base_url(); ?>index.php/Tagging/records" title="TT">
											<span <?php echo $TTStyle; ?>>TT</span>
										</a>
									</li>

							<?php }
							} ?>

						</ul>
					</div>
				</div>

			</div><!-- #header-nav-left -->

			<div id="header-nav-right">
				<!--<a href="#" class="hdr-btn" id="fullscreen-btn" title="Fullscreen">
							<i class="glyph-icon icon-arrows-alt"></i>
						</a> -->
				<a class="hdr-btn login_icon" title="Logout" style="border-radius: 0px; margin: 0px 0px 0 0;" href="<?php echo site_url('login/logout'); ?>"> <i class="glyph-icon icon-power-off"></i> </a>

			</div><!-- #header-nav-right -->

			<!--</div>-->