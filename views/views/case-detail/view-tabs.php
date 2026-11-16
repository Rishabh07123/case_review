<div class="tabs tabset" id="tab_view">			
  <!-- Tab 1 commented on 04-04-2024 https://trello.com/c/C3JoLfbl/1214-cr-portal-hide-certain-tabs-rearrange-order -->
  <!-- <input type="radio" name="tabset" id="AStab" aria-controls="AS">
  <label for="AStab">Account Status</label>  -->
  <!-- Tab 2 commented on 04-04-2024 https://trello.com/c/C3JoLfbl/1214-cr-portal-hide-certain-tabs-rearrange-order -->
  <!-- <input type="radio" name="tabset" id="RCtab" aria-controls="RC">
  <label for="RCtab">Resolution & Collection</label>  -->
  <!-- Tab 3 commented on 04-04-2024 https://trello.com/c/C3JoLfbl/1214-cr-portal-hide-certain-tabs-rearrange-order -->
  <!-- <input type="radio" name="tabset" id="PItab" aria-controls="PI">
  <label for="PItab">Penalty & Interest</label>  -->
  <!-- Tab 4 commented on 04-04-2024 https://trello.com/c/C3JoLfbl/1214-cr-portal-hide-certain-tabs-rearrange-order -->
  <!-- <input type="radio" name="tabset" id="CPtab" aria-controls="CP">
  <label for="CPtab">Wages & Income</label>	 -->
  <!-- Tab 5 commented on 04-04-2024 https://trello.com/c/C3JoLfbl/1214-cr-portal-hide-certain-tabs-rearrange-order -->
  <!-- <input type="radio" name="tabset" id="CSEDtab" aria-controls="CSED">
  <label for="CSEDtab">CSED Calculations</label>  -->
  <!-- Tab 6 -->
  <input type="radio" name="tabset" id="TaxCaseIntake" aria-controls="TaxCaseIntake" checked>
  <label for="TaxCaseIntake">Tax Case Intake</label>

   <!-- Tab 15 Added on 08-12-2023 https://trello.com/c/6f8jcha1/742-display-new-opportunity-fields-discovery-part-1-2-tabs-in-cr-portal -->
   <input type="radio" name="tabset" id="Opportunitytab" aria-controls="Opportunitytab">
  <label for="Opportunitytab" id="Opportunity">Opportunity</label> 
  <!-- end Added on 08-12-2023 -->
  <!-- Tab 7 -->
  <input type="radio" name="tabset" id="PPStab" aria-controls="PPStab">
  <label for="PPStab" id="PPStab_label">PPS Information</label>
  <!-- Tab 8 -->
  <input type="radio" name="tabset" id="PPSspousetab" aria-controls="PPSspousetab">
  <label for="PPSspousetab" id="PPSspousetab_label">PPS Spouse Information</label>
  <!-- Tab 14 -->
  <input type="radio" name="tabset" id="PPSPremiumtab" aria-controls="PPSPremiumtab">
  <label for="PPSPremiumtab" id="PPSPremiumtab_label">PPS Premium</label>
  <!-- Tab 15 -->
  <input type="radio" name="tabset" id="PPSPremiumSpousetab" aria-controls="PPSPremiumSpousetab">
  <label for="PPSPremiumSpousetab" id="PPSPremiumSpousetab_label">PPS Premium Spouse</label>
  <!-- Tab 9 -->
  <input type="radio" name="tabset" id="PPSstatepersonaltab" aria-controls="PPSstatepersonaltab">
  <label for="PPSstatepersonaltab" id="PPSstatepersonaltab_label">PPS State Personal</label>
  <!-- Tab 10 -->
  <input type="radio" name="tabset" id="ASSETStab" aria-controls="Assets">
  <label for="ASSETStab">Assets</label>
  <!-- Tab 11 -->
  <input type="radio" name="tabset" id="EXPENSEtab" aria-controls="Expenses">
  <label for="EXPENSEtab">Expenses </label>  
  <!-- Tab 12 -->
  <input type="radio" name="tabset" id="DOCSTARtab" aria-controls="DocstarDocs">
  <label for="DOCSTARtab">Documents </label>  
  <!-- Tab 13 -->
  <input type="radio" name="tabset" id="Summarytab" aria-controls="Summary">
  <label for="Summarytab">Summary & Notes</label>  
  
  <!-- Tab 14 commented on 04-04-2024 https://trello.com/c/C3JoLfbl/1214-cr-portal-hide-certain-tabs-rearrange-order -->
  <!-- <input type="radio" name="tabset" id="ThsSummarytab" aria-controls="ThsSummarytab">
  <label for="ThsSummarytab" id="ThsSummary_label">THS Summary</label>   -->
  
  <input type="radio" name="tabset" id="CaseReviewtab" aria-controls="CaseReviewtab">
  <label for="CaseReviewtab" id="CaseReview">Case Review</label> 
 

  <div class="tab-panels">
  
    <!-- <section id="AS" class="tab-panel">
      <h3>Account Status</h3>
      <div class="example-box-wrapper">
        <?php //include('account-status.php'); ?>
      </div>
    </section> 
    
    <section id="RC" class="tab-panel">
      <h3>Resolution And Collection</h3>
      <div class="example-box-wrapper">
        <?php //include('resolution-collection.php'); ?>
      </div>
    </section> 
    
    <section id="PI" class="tab-panel">
      <h3>Penalty And Interest Dashboard</h3>
      <div class="example-box-wrapper">
        <?php //include('penalty-interest.php'); ?>
      </div>
    </section> 
    
    <section id="CP" class="tab-panel">
      <h3>Wages And Income</h3>
      <div class="example-box-wrapper">
        <?php //include('wages-income.php'); ?>
      </div>
    </section>	 			
    
    <section id="CSED" class="tab-panel">
      <h3>CSED Calculations</h3>
      <div class="example-box-wrapper">
        <?php //include('csed-calculations.php'); ?>
      </div>
    </section>  -->
    
	<section id="TaxCaseIntake" class="tab-panel"> 
      <h3>Tax Case Intake</h3>
      <?php include('tab-case-intake.php'); ?>
    </section>

     <!-- Tab 15 Added on 08-12-2023 https://trello.com/c/6f8jcha1/742-display-new-opportunity-fields-discovery-part-1-2-tabs-in-cr-portal -->
     <section id="Opportunity" class="tab-panel"> 
      <?php include('opportunity-tab.php'); ?>
    </section>
    <!-- end added on 08-12-2023 -->
	
    <section id="PPStab" class="tab-panel">
      <h3>PPS Information</h3>
      <div class="example-box-wrapper pps_box">
        <?php include('tab-pps-information.php'); ?>
      </div>
    </section>
    
	<section id="PPSspousetab" class="tab-panel">
      <h3>PPS Spouse Information</h3>
      <div class="example-box-wrapper pps_spouse_box">
        <?php include('tab-spouse-pps-information.php'); ?>
      </div>
    </section>
	
	<section id="PPSPremiumtab" class="tab-panel">
      <h3>PPS Premium</h3>
      <div class="example-box-wrapper pps_spouse_box">
        <?php include('tab-pps-premium.php'); ?>
      </div>
    </section>

	<section id="PPSPremiumSpousetab" class="tab-panel">
      <h3>PPS Premium Spouse</h3>
      <div class="example-box-wrapper pps_premium_spouse_box">
        <?php include('tab-pps-premium-spouse.php'); ?>
      </div>
    </section>
	
	<section id="PPSstatepersonaltab" class="tab-panel">
      <h3>PPS State Personal</h3>
      <div class="example-box-wrapper pps_state_personal_box">
        <?php include('tab-pps-state-personal.php'); ?>
      </div>
    </section>
		
	<section id="Assets" class="tab-panel"> 
      <h3>Assets</h3>
      <?php include('assets.php'); ?>
    </section>	
	
	<section id="Expenses" class="tab-panel"> 
      <h3>Expenses</h3> <font style="color: red;">* Please hit save button at bottom after entering values</font>
      <?php include('tab-expenses.php'); ?>
    </section>	
	
	<section id="DocstarDocs" class="tab-panel"> 
      <h3>Documents</h3> 
      <?php include('tab-docstar.php'); ?>
    </section>	
	
    <section id="Summary" class="tab-panel"> 
      <h3>Summary</h3>
      <?php include('tab-summary-notes.php'); ?>
    </section>				
    
	  <!-- <section id="THSSummary" class="tab-panel"> 
      <h3>THS Summary</h3>
      <?php //include('tab-ths-summary.php'); ?>
    </section> -->
    
    <section id="CaseReview" class="tab-panel"> 
      <h3>Case Review</h3>
      <?php include('tab-case-review.php'); ?>
    </section>

   
	
  </div>
</div>