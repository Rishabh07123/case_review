<div class="example-box-wrapper details_box"  id="reports_box">
	<div class="row">
		<div class="col-md-6">
			<h5 style="text-align: center;"><b>Client</b></h5>
			<h5><div class="col-md-5">Wages:</div><div class="col-md-7"> <p class="client_wage" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5">Net Biz Income:</div>
				<div class="col-md-7"> 
					<p class="tab_net_biz_inc" style="text-align: center;"></p> 
					<input class="form-control1" type="text" id="tab_net_biz_income" value="">
					
					<button class="btn btn-xs btn-success edit_income_btn" title="AddNew" id="tab_edit_biz_inc" href="javascript:;" onClick="editBizIncomeTab(this)">  <i class="glyph-icon icon-pencil"></i> </button>
			
					<button class="btn btn-xs btn-success" id="tab_add_biz_inc" title="Save" onclick="saveBizIncomeTab(this);"><i class="glyph-icon icon-check"></i></button>
	
					<button class="btn btn-xs btn-danger" id="tab_delete_biz_inc" title="Delete Row" onclick="deleteBizIncTab(this);"><i class="glyph-icon icon-remove"></i></button> 
				</div>
			</h5>
			<h5><div class="col-md-5"> SSI:</div><div class="col-md-7"> <p class="client_ssi" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5"> Retirement:</div><div class="col-md-7"> <p class="ret_inc" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5"> Other:</div>
				<div class="col-md-7">
					<p class="tab_other_inc" style="text-align: center;"></p>
					<input class="form-control1" type="text" id="tab_other_income" value="">
					
					<button class="btn btn-xs btn-success edit_income_btn" title="AddNew" id="tab_edit_other_inc" href="javascript:;" onClick="editOtherIncomeTab(this)">  <i class="glyph-icon icon-pencil"></i> </button>
			
					<button class="btn btn-xs btn-success" id="tab_add_other_inc" title="Save" onclick="saveOtherIncomeTab(this);"><i class="glyph-icon icon-check"></i></button>
	
					<button class="btn btn-xs btn-danger" id="tab_delete_other_inc" title="Delete Row" onclick="deleteOtherIncTab(this);"><i class="glyph-icon icon-remove"></i></button> 
				</div>
			</h5>
			<h5><div class="col-md-5"> Total Verified Income:</div><div class="col-md-7"><p class="total_income" style="text-align: center;"></p></div></h5>															
		</div> 
		<div class="col-md-6">
			<h5 style="text-align: center;"><b>Spouse</b></h5>
			<h5><div class="col-md-5">Wages:</div><div class="col-md-7"> <p class="sp_wage" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5">Net Biz Income:</div>
				<div class="col-md-7"> 
					<p class="tab_net_biz_inc_sp" style="text-align: center;"></p>
					<input class="form-control1" type="text" id="tab_net_biz_income_sp" value="">
					
					<button class="btn btn-xs btn-success edit_income_btn" title="AddNew" id="tab_edit_biz_inc_sp" href="javascript:;" onClick="editBizIncomeSPTab(this)">  <i class="glyph-icon icon-pencil"></i> </button>
			
					<button class="btn btn-xs btn-success" id="tab_add_biz_inc_sp" title="Save" onclick="saveBizIncomeSPTab(this);"><i class="glyph-icon icon-check"></i></button>
	
					<button class="btn btn-xs btn-danger" id="tab_delete_biz_inc_sp" title="Delete Row" onclick="deleteBizIncSPTab(this);"><i class="glyph-icon icon-remove"></i></button> 
				</div>
			</h5>
			<h5><div class="col-md-5"> SSI:</div><div class="col-md-7"> <p class="sp_ssi" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5"> Retirement:</div><div class="col-md-7"> <p class="sp_ret" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5"> Other:</div>
				<div class="col-md-7">
					<p class="tab_other-inc_sp" style="text-align: center;"></p>
					<input class="form-control1" type="text" id="tab_other_income_sp" value="">
					
					<button class="btn btn-xs btn-success edit_income_btn" title="AddNew" id="tab_edit_other_inc_sp" href="javascript:;" onClick="editOtherIncomeSPTab(this)">  <i class="glyph-icon icon-pencil"></i> </button>
			
					<button class="btn btn-xs btn-success" id="tab_add_other_inc_sp" title="Save" onclick="saveOtherIncomeSPTab(this);"><i class="glyph-icon icon-check"></i></button>
	
					<button class="btn btn-xs btn-danger" id="tab_delete_other_inc_sp" title="Delete Row" onclick="deleteOtherIncSPTab(this);"><i class="glyph-icon icon-remove"></i></button> 
				</div>
			</h5>	
		</div> 
	</div>
</div>