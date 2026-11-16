<table id="BO_acc_services_table" class="table table-bordered caselist " cellspacing="0" width="100%" style="display: block; overflow-x: auto;">
    <thead>
        <tr>
            <th style="width: 10%;">Sale Type</th>
            <th style="width: 10%;">Related To</th>
            <th style="width: 35%;">Service Name</th>
            <th style="width: 10%;">Description</th>
            <th style="width: 10%;">Years</th>
            <th style="width: 6%;">Qty</th>
            <th style="width: 10%;">List Price</th>
            <th style="width: 12%;">Billing Approved Date</th>
            <th>Module Linking</th>
        </tr>
    </thead>
    
    <tbody class="BO_acc_services_tbody">
    </tbody>
</table>

<div class="modal" tabindex="-1" role="dialog" style="display:none;" id = 'tax_prep_record_list'>
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:inherit">
      <div class="modal-header">
        <h5 class="modal-title" style = "margin-left: 230px;">Tax Preperation</h5>
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="tax_prep_service_add" style = "margin:20px; color:black"></div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id = "get-tax-prep-field-nos" onclick= "add_rows(this)">Create Row(s)</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id = "close-tax-prep-modal">Close</button>
      </div>
    </div>
  </div>
</div>