<?php 
$current_url=base_url(uri_string());
//to exit if not loged_in
$ci =&get_instance();
$ci->load->model('DBmodel');
//$ci->DBmodel->check_isvalidated();
//print_r($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Loading Spinner */
		#page-content-wrapper ul {
			display: inline-flex;
		}
		#page-content-wrapper li {
			padding: 0 5px;
		}
		#page-content-wrapper .col-sm-2 {
			width: 26.66666667%;
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
			animation: fadeEffect 1s; /* Fading effect takes 1 second */
		}
		
		*,
		*:before,
		*:after {
		  box-sizing: border-box;
		}

		.tabset > input[type="radio"] {
		  position: absolute;
		  left: -200vw;
		}

		.tabset .tab-panel {
		  display: none;
		}

		.tabset > input:first-child:checked ~ .tab-panels > .tab-panel:first-child,
		.tabset > input:nth-child(3):checked ~ .tab-panels > .tab-panel:nth-child(2),
		.tabset > input:nth-child(5):checked ~ .tab-panels > .tab-panel:nth-child(3),
		.tabset > input:nth-child(7):checked ~ .tab-panels > .tab-panel:nth-child(4),
		.tabset > input:nth-child(9):checked ~ .tab-panels > .tab-panel:nth-child(5),
		.tabset > input:nth-child(11):checked ~ .tab-panels > .tab-panel:nth-child(6),
		.tabset > input:nth-child(13):checked ~ .tab-panels > .tab-panel:nth-child(7),
		.tabset > input:nth-child(15):checked ~ .tab-panels > .tab-panel:nth-child(8),
		.tabset > input:nth-child(17):checked ~ .tab-panels > .tab-panel:nth-child(9),
		.tabset > input:nth-child(19):checked ~ .tab-panels > .tab-panel:nth-child(10) {
		  display: block;
		}

		.tabset > label {
		  position: relative;
		  display: inline-block;
		  padding: 15px 15px 25px;
		  border: 1px solid transparent;
		  border-bottom: 0;
		  cursor: pointer;
		  font-weight: 600;
		}

		.tabset > label::after {
		  content: "";
		  position: absolute;
		  left: 15px;
		  bottom: 10px;
		  width: 22px;
		  height: 4px;
		  background: #8d8d8d;
		}

		.tabset > label:hover,
		.tabset > input:focus + label {
		  color: #06c;
		}

		.tabset > label:hover::after,
		.tabset > input:focus + label::after,
		.tabset > input:checked + label::after {
		  background: #06c;
		}

		.tabset > input:checked + label {
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
			from {opacity: 0;}
			to {opacity: 1;}
		}
		.tab_pps_tbody td{
			width: 23px !important;  
			vertical-align: middle !important;
		}
		.tab_pps_tbody input,select{
			width: 90px;
			vertical-align: middle !important;
		}
		
		#pps_table,
		#tab_pps_table {
			display: block;
			overflow-x: auto;
		}
		#tab_pps_table th{
			width: 23px !important;  
			vertical-align: middle !important;
		}
		
		#spouse_pps_table,
		#tab_spouse_pps_table {
			display: block;
			overflow-x: auto;
		}
		#tab_spouse_pps_table th{
			width: 23px !important;  
			vertical-align: middle !important;
		}
		
		/*button#deleterow {
			margin-top: 10px;
			margin-left: 14px;
		}*/
		
		.pps_tbody td{
			width: 90px !important;
			vertical-align: middle !important;
		}
		.pps_tbody input,select{
			width: 110px;
			vertical-align: middle !important;
		}
		.spouse_pps_tbody td{
			width: 90px !important;
			vertical-align: middle !important;
		}
		.spouse_pps_tbody input,select{
			width: 110px;
			vertical-align: middle !important;
		}
		.tab_spouse_pps_tbody td{
			width: 90px !important;
			vertical-align: middle !important;
		}
		.tab_spouse_pps_tbody input,select{
			width: 110px;
			vertical-align: middle !important;
		}
		.box-comment {
			border: 0px solid !important;
		}
		div#lastcontact {
			border-bottom: 1px solid #cfe3f0;
		}
		.tf_tab{
			width: 60%;
		}
		.tf{
			width: 60%;
		}
		p:last-child {
			border-bottom: none;
			word-wrap: break-word;
		}
		.assets_tab .td{
			text-align: center;
		}
		.assets_tab .col1{
			text-align: left !important;
		} 
		.rec_tbody .td{
			text-align: center;
		}
		.activity_tbody .td{
			text-align: center;
		}
		#review_box{
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}
		#note h4{
			padding: 16px 0 18px;
		}
		#note p#note_msg2 {
			margin-top: 3px;
			margin-bottom: 10px;
		}
		#note p#note_msg {
			margin-top: 105px;
		}
		.csed_tbody .col5{
			text-align: center !important;
		}
		.edit_income_btn{
			display: block;
			float: right;
			position: relative;
			right: 93px;
			top: -23px;
		}
		#pps_note_box{
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}
		#note_pps h4{
			padding: 16px 0 18px;
		}
		#note_pps2 h4{
			padding: 16px 0 18px;
		}
		#note_pps p#note_pps_msg {
			margin-top: 15px;
			margin-bottom: 10px;
		}
		#note_txt_pps{			
			margin-top: 7px;
			margin-bottom: 8px;
		}
		#note_pps p#tab_note_pps_msg {
			margin-top: 50px;
		}
		#tab_note_txt_pps{			
			margin-top: 7px;
			margin-bottom: 8px;
		}
		
		#spouse_pps_note_box{
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}
		#spouse_note_pps h4{
			padding: 16px 0 18px;
		}
		#spouse_note_pps_pps2 h4{
			padding: 16px 0 18px;
		}
		#spouse_note_pps {
			margin-top: 50px;
		}
		#note_txt_pps_spouse{			
			margin-top: 7px;
		}
		#spouse_note_pps p#tab_note_pps_msg {
			margin-top: 114px;
		}
		#tab_note_txt_pps{			
			margin-top: 7px;
		}
		
		#tab_spouse_pps_note_box{
			padding: 0;
			border: 1px solid #cfe3f0;
			width: 100%;
			margin-bottom: 10px;
			margin-left: 3px;
		}
		#tab_spouse_note_pps2 h4{
			padding: 16px 0 18px;
		}
		#tab_spouse_note_pps2 p#note_spouse_pps_msg {
			margin-top: 50px;
		}
		#tab_note_txt_pps_spouse{			
			margin-top: 7px;
			margin-bottom: 15px;
		}
		
		
		#btnSavePPS{			
			margin-top: 7px;
		}
		#btnSaveSpousePPS{			
			margin-top: -59px;
		}
		#btnSavePPSSpouseTab{			
			margin-top: 7px;
		}
		.compl_sum{
			display: block;
			float: right;
			position: relative;
			right: 0px;
			top: -2px;
		}
		.edit_oth_exp1{
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}
		.edit_oth_exp2{
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}
		.edit_oth_exp3{
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}
		.edit_oth_exp4{
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}
		.tab_edit_oth_exp1{
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}
		.tab_edit_oth_exp2{
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}
		.tab_edit_oth_exp3{
			display: block;
			float: right;
			position: relative;
			right: 76px;
			top: -18px;
		}
		.tab_edit_oth_exp4{
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
			width: 70%;
			height: 32px;
			margin-left: 67px;
		}
		input.form-control.input_sc {
			margin-top: -9px;
			width: 70%;
			height: 32px;
			margin-left: 67px;
		}
		input.form-control.tab_input_di { 
			margin-top: -9px;
			width: 70%;
			height: 32px;
			margin-left: 67px;
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
			width: 62%;
			margin-left: 82px;
			margin-top: -9px;
		}
		select#tab_ac_stat {
			width: 62%;
			margin-left: 82px;
			margin-top: -9px;
		}
		select#compliant {
			margin-top: -9px;
			margin-left: 22px;
			width: 93%;
		}
		select#tab_compliant {
			margin-top: -9px;
			margin-left: 22px;
			width: 93%;
		}
		select#analysis1 {
			width: 56%;
			margin-left: 95px;
			margin-top: -9px;
		}
		select#tab_analysis1 {
			width: 56%;
			margin-left: 95px;
			margin-top: -9px;
		}
		select#analysis2 {
			width: 56%;
			margin-left: 95px;
			margin-top: -9px;
		}
		select#tab_analysis2 {
			width: 56%;
			margin-left: 95px;
			margin-top: -9px;
		}
		select#analysis3 {
			width: 56%;
			margin-left: 95px;
			margin-top: -9px;
		}
		select#tab_analysis3 {
			width: 56%;
			margin-left: 95px;
			margin-top: -9px;
		}
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
		input#save_tab_spouse_pps_rp_data{ 
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
		
		input.form-control1.casenum{
			color: #000;
			font-weight: bold;
			height: 33px;
			width: 12% !important;
			margin-left: 5px;
		}
		input.form-control1.clientname{
			color: #000;
			font-weight: bold;
			height: 33px;
			width: 20% !important;
			margin-left: 5px;
		}
		input.form-control1.casestatus{
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
		.close_rec{ style="float: right !important"}
		
		select#case_status {
			width: 188px;
			margin-top: -35px;
			margin-left: 614px;
		}
		button#refresh_caseid_data {
			margin-left: -70px;
			margin-right: 5px;
		}
		
		p#tab_note_pps_msg_spouse {
			margin-top: 115px;
			margin-bottom: 10px;
		}
		p#note_spouse_pps_msg {
			margin-top: -35px;
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
			margin-bottom: 5px !importent;
			width: 79% !important;
		}
		
		.editnote {
			margin-left: 491px;
			margin-top: -96px;
		}
		.savenote {
			margin-left: 526px;
			margin-top: -105px;
		}
		.teditnote {
			margin-left: 500px;
			margin-top: -96px;
		}
		.tsavenote {
			margin-left: 540px;
			margin-top: -105px;
		}
		
		.tdeletenote{
			margin-left: 531px;
			margin-top: -130px;
		}
		
		.deletenote{
			margin-left: 522px;
			margin-top: -130px;
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
			margin-top: -96px;
		}
		.saveppsnote {
			margin-left: 87%;
			margin-top: -105px;
		}
		.teditppsnote {
			margin-left: 85%;
			margin-top: -96px;
		}
		.tsaveppsnote {
			margin-left: 87%;
			margin-top: -105px;
		}
		
		.deleteppsnote {
			margin-left: 87.5%;
			margin-top: -129px;
		}
		.tdeleteppsnote {
			margin-left: 87.5%;
			margin-top: -129px;
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
			margin-top: -96px;
		}
		.saveppsspousenote {
			margin-left: 87%;
			margin-top: -95px;
		}
		.teditppsspousenote {
			margin-left: 85%;
			margin-top: -96px;
		}
		.tsaveppsspousenote {
			margin-left: 87%;
			margin-top: -95px;
		}
		
		.deleteppsspousenote{
			margin-left: 87.5%;
			margin-top: -129px;
		}
		.tdeleteppsspousenote{
			margin-left: 87.5%;
			margin-top: -129px;
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
			margin-left: 810px;
			margin-top: -55px;
		}
		h4#pps_heading {
			float: left;
			margin-bottom: 10px;
			margin-top: 35px;
		}
		input#cdate {
			width: 100px;
		}
    </style>
    <meta charset="UTF-8">
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
 <title><?php if(isset($title)) echo $title ;?></title>  
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Favicons -->
<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/icons/CtaxLogo.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.css">
<!-- HELPERS -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/helpers/animate.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/helpers/backgrounds.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/helpers/typography.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/helpers/utils.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/helpers/colors.css">
<!-- ELEMENTS -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/buttons.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/content-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/images.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/info-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/invoice.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/loading-indicators.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/menus.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/panel-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/response-messages.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/responsive-tables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/ribbon.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/social-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/tables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/tile-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/elements/timeline.css">

<!-- ICONS -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/icons/fontawesome/fontawesome.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/icons/linecons/linecons.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/icons/spinnericon/spinnericon.css">

<!-- WIDGETS -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/accordion-ui/accordion.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/calendar/calendar.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/carousel/carousel.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/chosen/chosen.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/colorpicker/colorpicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/datatable/datatable.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/datepicker/datepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/datepicker-ui/datepicker.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/daterangepicker/daterangepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/dialog/dialog.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/dropdown/dropdown.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/dropzone/dropzone.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/file-input/fileinput.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/input-switch/inputswitch.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/input-switch/inputswitch-alt.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/ionrangeslider/ionrangeslider.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/jcrop/jcrop.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/jgrowl-notifications/jgrowl.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/loading-bar/loadingbar.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/maps/vector-maps/vectormaps.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/markdown/markdown.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/modal/modal.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/multi-select/multiselect.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/multi-upload/fileupload.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/nestable/nestable.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/noty-notifications/noty.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/popover/popover.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/pretty-photo/prettyphoto.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/progressbar/progressbar.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/range-slider/rangeslider.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/slidebars/slidebars.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/slider-ui/slider.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/summernote-wysiwyg/summernote-wysiwyg.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/tabs-ui/tabs.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/theme-switcher/themeswitcher.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/timepicker/timepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/tocify/tocify.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/tooltip/tooltip.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/touchspin/touchspin.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/uniform/uniform.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/wizard/wizard.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/xeditable/xeditable.css">

<!-- SNIPPETS -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/snippets/chat.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/snippets/files-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/snippets/login-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/snippets/notification-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/snippets/progress-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/snippets/todo.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/snippets/user-profile.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/snippets/mobile-navigation.css">

<!-- APPLICATIONS -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/applications/mailbox.css">

<!-- Admin theme -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/themes/admin/layout.css?v=<?php echo time();?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/themes/admin/color-schemes/default.css?v=<?php echo time();?>">

<!-- Components theme -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/themes/components/default.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/themes/components/border-radius.css">

<!-- Admin responsive -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/helpers/responsive-elements.css?v=<?php echo time();?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/helpers/admin-responsive.css">

<!-- Custom CSS 
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/custom.css"> -->

    <!-- JS Core -->

    <script type="text/javascript" src="<?php echo base_url();?>assets/js-core/jquery-core.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js-core/jquery-ui-core.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js-core/jquery-ui-widget.js"></script>
    <!--<script type="text/javascript" src="<?php echo base_url();?>assets/js-core/jquery-ui-mouse.js"></script>-->
    <script type="text/javascript" src="<?php echo base_url();?>assets/js-core/jquery-ui-position.js"></script>
    <!--<script type="text/javascript" src="<?php echo base_url();?>assets/js-core/transition.js"></script>-->
    <script type="text/javascript" src="<?php echo base_url();?>assets/js-core/modernizr.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js-core/jquery-cookie.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/jgrowl-notifications/jgrowl.js"></script>

    <script type="text/javascript">
        $(window).load(function(){
            setTimeout(function() {
                $('#loading').fadeOut( 400, "linear" );
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
						<a href="<?php //echo base_url().'Reports'; ?>" class="logo-content-small" title="CTax Reporting"></a>
					</div>
					<div id="header-logo" class="logo-bg">
						<a href="<?php //echo base_url().'Reports'; ?>" class="logo-content-big" title="CTax Reporting">
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
							
						</div>
						
					</div><!-- #header-nav-left -->
					
					
					
				<!--</div>-->
						