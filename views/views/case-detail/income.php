<div class="example-box-wrapper details_box"  id="reports_box">
	<div class="row">
		<div class="col-md-6">
			<h5 style="text-align: center;"><b>Client</b></h5>
			<h5><div class="col-md-5">Wages:</div><div class="col-md-7"> <p class="client_wage" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5">Net Biz Income:</div>
				<div class="col-md-7"> 
					<p class="net_biz_inc" style="text-align: center;"></p> 
					<input class="form-control1" type="text" id="net_biz_income" value="">
					
					<button class="btn btn-xs btn-success edit_income_btn" title="AddNew" id="edit_biz_inc" href="javascript:;" onClick="editBizIncome(this)">  <i class="glyph-icon icon-pencil"></i> </button>
			
					<button class="btn btn-xs btn-success" id="add_biz_inc" title="Save" onclick="saveBizIncome(this);"><i class="glyph-icon icon-check"></i></button>
	
					<button class="btn btn-xs btn-danger" id="delete_biz_inc" title="Delete Row" onclick="deleteBizInc(this);"><i class="glyph-icon icon-remove"></i></button> 
				</div>
			</h5>
			<h5><div class="col-md-5"> SSI:</div><div class="col-md-7"> <p class="client_ssi" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5"> Retirement:</div><div class="col-md-7"> <p class="ret_inc" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5"> Other:</div>
				<div class="col-md-7">
					<p class="other_inc" style="text-align: center;"></p>
					<input class="form-control1" type="text" id="other_income" value="">
					
					<button class="btn btn-xs btn-success edit_income_btn" title="AddNew" id="edit_other_inc" href="javascript:;" onClick="editOtherIncome(this)">  <i class="glyph-icon icon-pencil"></i> </button>
			
					<button class="btn btn-xs btn-success" id="add_other_inc" title="Save" onclick="saveOtherIncome(this);"><i class="glyph-icon icon-check"></i></button>
	
					<button class="btn btn-xs btn-danger" id="delete_other_inc" title="Delete Row" onclick="deleteOtherInc(this);"><i class="glyph-icon icon-remove"></i></button> 
				</div>
			</h5>
			<h5><div class="col-md-5"> Total Verified Income:</div><div class="col-md-7"><p class="total_income" style="text-align: center;"></p></div></h5>															
		</div> 
		<div class="col-md-6">
			<h5 style="text-align: center;"><b>Spouse</b></h5>
			<h5><div class="col-md-5">Wages:</div><div class="col-md-7"> <p class="sp_wage" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5">Net Biz Income:</div>
				<div class="col-md-7"> 
					<p class="net_biz_inc_sp" style="text-align: center;"></p>
					<input class="form-control1" type="text" id="net_biz_income_sp" value="">
					
					<button class="btn btn-xs btn-success edit_income_btn" title="AddNew" id="edit_biz_inc_sp" href="javascript:;" onClick="editBizIncomeSP(this)">  <i class="glyph-icon icon-pencil"></i> </button>
			
					<button class="btn btn-xs btn-success" id="add_biz_inc_sp" title="Save" onclick="saveBizIncomeSP(this);"><i class="glyph-icon icon-check"></i></button>
	
					<button class="btn btn-xs btn-danger" id="delete_biz_inc_sp" title="Delete Row" onclick="deleteBizIncSP(this);"><i class="glyph-icon icon-remove"></i></button> 
				</div>
			</h5>
			<h5><div class="col-md-5"> SSI:</div><div class="col-md-7"> <p class="sp_ssi" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5"> Retirement:</div><div class="col-md-7"> <p class="sp_ret" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-5"> Other:</div>
				<div class="col-md-7">
					<p class="other-inc_sp" style="text-align: center;"></p>
					<input class="form-control1" type="text" id="other_income_sp" value="">
					
					<button class="btn btn-xs btn-success edit_income_btn" title="AddNew" id="edit_other_inc_sp" href="javascript:;" onClick="editOtherIncomeSP(this)">  <i class="glyph-icon icon-pencil"></i> </button>
			
					<button class="btn btn-xs btn-success" id="add_other_inc_sp" title="Save" onclick="saveOtherIncomeSP(this);"><i class="glyph-icon icon-check"></i></button>
	
					<button class="btn btn-xs btn-danger" id="delete_other_inc_sp" title="Delete Row" onclick="deleteOtherIncSP(this);"><i class="glyph-icon icon-remove"></i></button> 
				</div>
			</h5>	
		</div> 
	</div>
</div>