<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

  <div class="text-right accordion-buttons">
    <button class="btn btn-default expand-all">Expand All</button>
    <button class="btn btn-default collapse-all">Collapse All</button>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Account Status
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body" id="accordion-1">
        <?php include('account-status.php'); ?>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Resolution And Collection
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
        <?php include('resolution-collection.php'); ?>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Penalty And Interest
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <?php include('penalty-interest.php'); ?>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFour">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Wages And Income
        </a>
      </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div class="panel-body">
        <?php include('wages-income.php'); ?>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFive">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
          CSED Calculations
        </a>
      </h4>
    </div>
    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
      <div class="panel-body">
        <?php include('csed-calculations.php'); ?>
      </div>
    </div>
  </div> 
  
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingSix">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
          Tax Case Intake
        </a>
      </h4>
    </div>
    <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
      <div class="panel-body">
        <?php include('case-intake.php'); ?>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default" id="pps_accordion">
    <div class="panel-heading" role="tab" id="headingSeven">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
          PPS Information
        </a>
      </h4>
    </div>
    <div id="collapseSeven" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSeven">
      <div class="panel-body">
        <?php include('pps-information.php'); ?>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default" id="spouse_accordion">
    <div class="panel-heading" role="tab" id="headingEight">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
          PPS Spouse Information
        </a>
      </h4>
    </div>
    <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
      <div class="panel-body">
        <?php include('pps-spouse-information.php'); ?>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default" id="pps_premium_accordion">
    <div class="panel-heading" role="tab" id="headingFourteen">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseFourteen" aria-expanded="true" aria-controls="collapseFourteen">
          PPS Premium
        </a>
      </h4>
    </div>
    <div id="collapseFourteen" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFourteen">
      <div class="panel-body">
        <?php include('pps-premium.php'); ?>
      </div>
    </div>
  </div>

  <div class="panel panel-default" id="pps_premium_spouse_accordion">
    <div class="panel-heading" role="tab" id="headingFifteen">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseFifteen" aria-expanded="true" aria-controls="collapseFifteen">
          PPS Premium Spouse
        </a>
      </h4>
    </div>
    <div id="collapseFifteen" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFifteen">
      <div class="panel-body">
        <?php include('pps-premium-spouse.php'); ?>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default" id="pps_state_accordion">
    <div class="panel-heading" role="tab" id="headingNine">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
          PPS State Personal
        </a>
      </h4>
    </div>
    <div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNine">
      <div class="panel-body">
        <?php include('pps-state-personal.php'); ?>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTen">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
          Assets
        </a>
      </h4>
    </div>
    <div id="collapseTen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTen">
      <div class="panel-body">
        <?php include('assets.php'); ?>
      </div>
    </div>
  </div>

  <!-- <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingEight">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
          Income
        </a>
      </h4>
    </div>
    <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
      <div class="panel-body">
        <?php //include('income.php'); ?>
      </div>
    </div>
  </div> -->
  
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingEleven">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
          Expenses
        </a>
      </h4>
    </div>
    <div id="collapseEleven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEleven">
      <div class="panel-body">
        <?php include('expenses.php'); ?>
      </div>
    </div>
  </div>  
  
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwelve">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
          Documents
        </a>
      </h4>
    </div>
    <div id="collapseTwelve" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwelve">
      <div class="panel-body">
        <?php include('docstar.php'); ?>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThirteen">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
          Summary And Notes
        </a>
      </h4>
    </div>
    <div id="collapseThirteen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThirteen">
      <div class="panel-body">
        <?php include('summary-notes.php'); ?>
      </div>
    </div>
  </div>
  
  <!--<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingEleven">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
          PPS Sugar
        </a>
      </h4>
    </div>
    <div id="collapseEleven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEleven">
      <div class="panel-body">
        <?php //include('pps-sugar.php'); ?>
      </div>
    </div>
  </div> -->

</div>