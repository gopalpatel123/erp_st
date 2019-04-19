<style>

.rightAligntextClasses{
	text-align:right !important;
}
</style>
<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Create Stock Journal');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Create Stock Journal</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($stockJournal,['onsubmit'=>'return checkValidation()']) ?>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Voucher No :</label>&nbsp;&nbsp;
								<?= h('#'.str_pad($voucher_no, 4, '0', STR_PAD_LEFT)) ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Reference No </label>
								<?php echo $this->Form->control('reference_no',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'Reference No','autofocus']); ?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Transaction Date <span class="required">*</span></label>
								<?php echo $this->Form->control('transaction_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y')]); ?>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
					    <div class="table-responsive">
							<table width="100%" class="table  table-bordered" >
								<tr>
									<td width="50%">
										<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
											<thead>
												<tr><td align="center" colspan="6">Inward</td></tr>
												<tr align="center">
													<td ><label>Sr<label></td>
													<td><label>Item<label></td>
													<td><label>Qty<label></td>
													<td><label>Rate<label></td>
													<td><label>Amount<label></td>
													<td></td>
												</tr>
											</thead>
											<tbody id='main_tbody' class="tab">

											</tbody>
											<tfoot>
												<tr>
													<td colspan="4" >	
														<button type="button" class="add_inward btn btn-default input-sm"><i class="fa fa-plus"></i> Add row</button>
													</td>
													<td width="25%"><?php echo $this->Form->input('inward_amount_total', ['label' => false,'class' => 'form-control input-sm rightAligntextClass','id'=>'total_inward','placeholder'=>'Total','type'=>'text']); ?></td>
													<td></td>
												</tr>
											</tfoot>
										</table>
									</td>
									<td width="50%">
										<table id="main_table2" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
											<thead>
												<tr><td align="center" colspan="6">Outward</td></tr>
												<tr align="center">
													<td><label>Sr<label></td>
													<td><label>Item<label></td>
													<td><label>Qty<label></td>
													<td><label>Rate<label></td>
													<td><label>Amount<label></td>
													<td></td>
												</tr>
											</thead>
											<tbody id='main_tbody2' class="tab">

											</tbody>
											<tfoot>
												<tr>
													<td colspan="4">
														<button type="button" class="add_outward btn btn-default input-sm"><i class="fa fa-plus"></i> Add row</button>
													</td>
													<td width="25%"><?php echo $this->Form->input('outward_amount_total', ['label' => false,'class' => 'form-control rightAligntextClass input-sm','id'=>'total_outward','placeholder'=>'Total']); ?></td>
													<td></td>
												</tr>
											</tfoot>
										</table>
									</td>
								</tr>
							</table>
					    </div>
				    </div>
				  
				    <div class="row">
					<div class="col-md-4">
							<div class="form-group">
								<label>Narration </label>
								<?php echo $this->Form->control('narration',['class'=>'form-control input-sm ','label'=>false,'placeholder'=>'Narration','rows'=>'2']); ?>
							</div>
						</div>
				    </div>
			</div>
			<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit']) ?>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>


<!-- BEGIN PAGE LEVEL STYLES -->
	<!-- BEGIN COMPONENTS PICKERS -->
	<?php echo $this->Html->css('/assets/global/plugins/clockface/css/clockface.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<!-- END COMPONENTS PICKERS -->

	<!-- BEGIN COMPONENTS DROPDOWNS -->
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-select/bootstrap-select.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/select2/select2.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/jquery-multi-select/css/multi-select.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<!-- END COMPONENTS DROPDOWNS -->
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
	<!-- BEGIN COMPONENTS PICKERS -->
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/clockface/js/clockface.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-daterangepicker/moment.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<!-- END COMPONENTS PICKERS -->
	
	<!-- BEGIN COMPONENTS DROPDOWNS -->
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-select/bootstrap-select.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/select2/select2.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<!-- END COMPONENTS DROPDOWNS -->
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<!-- BEGIN COMPONENTS PICKERS -->
	<?php echo $this->Html->script('/assets/admin/pages/scripts/components-pickers.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<!-- END COMPONENTS PICKERS -->

	<!-- BEGIN COMPONENTS DROPDOWNS -->
	<?php echo $this->Html->script('/assets/global/scripts/metronic.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/layout.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/quick-sidebar.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/demo.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/pages/scripts/components-dropdowns.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<!-- END COMPONENTS DROPDOWNS -->
<!-- END PAGE LEVEL SCRIPTS -->

<?php
	$js="
	$(document).ready(function() {
	  $('.inward_calculation').die().live('keyup',function(){
		  inward_amt_calc();
	  });
	  $('.inward_reverseCalculation').die().live('keyup',function(){
		   inward_reverce_amt_calc();
	  });
	  function inward_amt_calc()
	  {   
		  $('#main_table tbody#main_tbody tr.main_tr').each(function()
		  {
			  var amount=0;
			  var qty  = parseFloat($(this).find('td:nth-child(3) input').val());
			  var rate = parseFloat($(this).find('td:nth-child(4) input').val());
			  amount   = qty*rate;
			  if(amount){
				  $(this).find('td:nth-child(5) input').val(amount.toFixed(2));
		      }
		  });
		  inward_amount_total();
	  }
	  
	  function inward_reverce_amt_calc()
	  {   
		  $('#main_table tbody#main_tbody tr.main_tr').each(function()
		  {
			  var rate=0;
			  var qty  = parseFloat($(this).find('td:nth-child(3) input').val());
			  var amount = parseFloat($(this).find('td:nth-child(5) input').val());
			  var rate = amount/qty;
			  if(qty){
				  if(rate){
					  $(this).find('td:nth-child(4) input').val(rate.toFixed(2));
				  }
			  }
		  });
		  inward_amount_total();
	  }
	  
	  $('.outward_calculation').die().live('keyup',function(){
		  outward_amt_calc();
	  });
	  $('.outward_reverseCalculation').die().live('keyup',function(){
		   outward_reverce_amt_calc();
	  });
	  function outward_amt_calc()
	  {
		  $('#main_table2 tbody#main_tbody2 tr.main_tr').each(function(){
			  var amount=0;
			  var qty  = parseFloat($(this).find('td:nth-child(3) input').val());
			  var rate = parseFloat($(this).find('td:nth-child(4) input').val());
			  amount   = qty*rate;
			  if(amount){
				  $(this).find('td:nth-child(5) input').val(amount.toFixed(2));
		      }
		  });
		  outward_amount_total()
	 }
	  
	  function outward_reverce_amt_calc()
	  {   
		  $('#main_table2 tbody#main_tbody2 tr.main_tr').each(function(){
			  var rate=0;
			  var qty  = parseFloat($(this).find('td:nth-child(3) input').val());
			  var amount = parseFloat($(this).find('td:nth-child(5) input').val());
			  var rate = amount/qty;
			  if(qty){
				  if(rate){
					  $(this).find('td:nth-child(4) input').val(rate.toFixed(2));
				  }
			  }
		  });
		  outward_amount_total()
	  }
	  
	  function inward_amount_total()
	  {
		  var inward_total=0
		  $('#main_table tbody#main_tbody tr.main_tr').each(function()
		  {
			 var amount = parseFloat($(this).find('td:nth-child(5) input').val());
			 if(amount){
					  inward_total = inward_total+amount;
			 }
		});
		  $('#total_inward').val(inward_total.toFixed(2)); 
	  }
	  
	  function outward_amount_total()
	  {   var outward_total=0;
		  $('#main_table2 tbody#main_tbody2 tr.main_tr').each(function(){
			  var amount = parseFloat($(this).find('td:nth-child(5) input').val());
			  if(amount){
				  outward_total = outward_total+amount;
			  }
			  
		  });
		  $('#total_outward').val(outward_total.toFixed(2));
	  }
	  
	  $('.delete-tr').die().live('click',function() 
	  {
		$(this).closest('tr').remove();
		inward_amount_total();
		outward_amount_total();
      });
	  
		ComponentsPickers.init();
    
	
	$('.add_inward').click(function(){
				add_row_inward();
				
		});
		
	
	
			
	$('.add_outward').click(function(){
				add_row_outward();
		});
		});
		
			function add_row_inward(){
				var tr=$('#sample_table tbody tr.main_tr').clone();
				$('#main_table tbody#main_tbody').append(tr);
				
				rename_inward_rows();
			}
	function add_row_outward(){
				var tr=$('#sample_table tbody tr.main_tr').clone();
				$('#main_table2 tbody#main_tbody2').append(tr);
				rename_outward_rows();
				
			}
			
	function rename_inward_rows(){
		var i=0;
		 $('#main_table tbody#main_tbody tr.main_tr').each(function(){ 
		  $(this).find('td:nth-child(1)').html(i+1);
		  $(this).find('td:nth-child(2) select').select2().attr({name:'inwards['+i+'][item_id]', id:'inwards-'+i+'-item_id'});
		  $(this).find('td:nth-child(3) input').attr({name:'inwards['+i+'][quantity]',id:'inwards-'+i+'-quantity',class:'inward_reverseCalculation   form-control input-sm numberOnly'});		
		  $(this).find('td:nth-child(4) input').attr({name:'inwards['+i+'][rate]', id:'inwards-'+i+'-rate',class:'inward_calculation form-control input-sm numberOnly'});
		  $(this).find('td:nth-child(5) input').attr({name:'inwards['+i+'][amount]', id:'inwards-'+i+'-amount',class:'inward_reverseCalculation form-control input-sm numberOnly'});
			
			i++;
			});
		}
		
	function rename_outward_rows(){
		 var j=0;
		  $('#main_table2 tbody#main_tbody2 tr.main_tr').each(function(){ 
		   $(this).find('td:nth-child(1)').html(j+1);
		   $(this).find('td:nth-child(2) select').select2().attr({name:'outwards['+j+'][item_id]', id:'outwards-'+j+'-item_id'});
		   $(this).find('td:nth-child(3) input').attr({name:'outwards['+j+'][quantity]',id:'outwards-'+j+'-quantity',class:'outward_reverseCalculation   form-control input-sm numberOnly'});		
		   $(this).find('td:nth-child(4) input').attr({name:'outwards['+j+'][rate]', id:'outwards-'+j+'-rate',class:'outward_calculation form-control input-sm numberOnly'});
		   $(this).find('td:nth-child(5) input').attr({name:'outwards['+j+'][amount]', id:'outwards-'+j+'-amount',class:'outward_reverseCalculation form-control input-sm numberOnly'});
				j++;
			});
			}
	
	function checkValidation()
	{
	   var inward_rowCount = $('#main_table tbody#main_tbody tr.main_tr').length;
	   var outward_rowCount = $('#main_table2 tbody#main_tbody2 tr.main_tr').length;
	   var total_tr = inward_rowCount+outward_rowCount;
	   if(total_tr)
	   {
			if(confirm('Are you sure you want to submit!')){
			 $('.submit').attr('disabled','disabled');
	        $('.submit').text('Submiting...');
			return true;
			}else{
			
			        return false;
			 }
	   }
	   else
	   {
		   alert('No item was selected.');
		   return false;   
	   }
    }
	
	
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>
<table id="sample_table" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab ">
			<td width="7%"></td>
			<td>
				<?php echo $this->Form->input('item_id', ['empty'=>'--Select--','options'=>$itemOptions,'label' => false,'class' => 'form-control input-medium','required'=>'required']); ?>
			</td>
			<td width="15%" align="right">
				<?php echo $this->Form->input('quantity', ['label' => false,'class' => 'form-control numberOnly','id'=>'check','required'=>'required','placeholder'=>'Qty','style'=>'text-align:right;']); ?>
			</td>
			<td width="20%" >
				<?php echo $this->Form->input('rate', ['label' => false,'class' => 'form-control input-sm numberOnly rightAligntextClasses','required'=>'required','placeholder'=>'Rate','style'=>'text-align:right;']); ?>
			</td>
			<td width="25%" >
				<?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm numberOnly rightAligntextClasses','required'=>'required','placeholder'=>'Amount','style'=>'text-align:right;']); ?>	
			</td>
			<td align="center">
				<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>