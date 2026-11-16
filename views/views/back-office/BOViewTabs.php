<div class="tabs tabset" id="tab_view">			
  <!-- Tab 1 -->

  <input type="radio" name="tabset" id="BO_RStab" aria-controls="AS">
  <label for="BO_RStab">Resolutions</label> 
  <!-- Tab 2 -->
  <input type="radio" name="tabset" id="BO_Txtab" aria-controls="RC">
  <label for="BO_Txtab">Tax Prep</label> 
  <!-- Tab 3 -->
  <input type="radio" name="tabset" id="BO_AStab" aria-controls="PI">
  <label for="BO_AStab">Miscellaneous Resolution Services</label> 
  <!-- Tab 4 -->
  <input type="radio" name="tabset" id="BO_Mnttab" aria-controls="CP">
  <label for="BO_Mnttab">Tax Assurance Program</label>	
  
  <div class="tab-panels" style="height: 49vh;" >
  
    <section id="AS" class="tab-panel">
      <div class="example-box-wrapper">
        <?php include('resolution.php'); ?>
      </div>
    </section> 
    
    <section id="RC" class="tab-panel">
      <div class="example-box-wrapper">
        <?php include('tax-prep.php'); ?>
      </div>
    </section> 
    
    <section id="PI" class="tab-panel">
      <div class="example-box-wrapper">
        <?php include('ancillary-services.php'); ?>
      </div>
    </section> 
    
    <section id="CP" class="tab-panel">
      <div class="example-box-wrapper">
      <?php include('monitoring.php'); ?>
      </div>
    </section>	 			
    
  </div>
</div>

