<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Create Inter Location stock Transfer Voucher');
?>
<div class="row">
	<div class="col-md-9">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Create Inter Location stock Transfer Voucher Edit</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($intraLocationStockTransferVoucher,['onsubmit'=>'return checkValidation()']) ?>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Voucher No :</label>&nbsp;&nbsp;
								<?= h(str_pad($intraLocationStockTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT)) ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Transaction Date <span class="required">*</span></label>
								<?php echo $this->Form->control('transaction_date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y')]); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Transfer From</label>
								<?php echo $this->Form->control('transfer_from_location_id',['options'=>$TransferFromLocations,'class'=>'form-control input-sm transfer_from','label'=>false,'empty'=>'--select--','autofocus']); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Transfer To</label>
								<?php echo $this->Form->control('transfer_to_location_id',['options'=>$TransferToLocations,'class'=>'form-control input-sm transfer_to','label'=>false,'empty'=>'--select--']); ?>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="table-responsive">
							<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
								<thead>
									<tr align="center">
										<td><label>Sr<label></td>
										<td><label>Item<label></td>
										<td><label>Qty<label></td>
										<td></td>
									</tr>
								</thead>
								<tbody id='main_tbody' class="tab">
								
								<?php 
								$i=0; foreach($intraLocationStockTransferVoucher->intra_location_stock_transfer_voucher_rows as $intra_location_stock_transfer_voucher_row)
								{ ?>
								<tr class="main_tr" class="tab">
									<td width="7%"><?php echo $i+1;?></td>
									<td width="50%">
									<input type="hidden" name="" class="outStock" value="0">
				                    <input type="hidden" name="" class="totStock " value="0">
										<?php echo $this->Form->control('item_id', ['options' => $itemOptions,'label' => false,'class' => 'form-control input-sm itemStock','required'=>'required','value'=>$intra_location_stock_transfer_voucher_row->item_id]); 
										echo $this->Form->control('intra_location_stock_transfer_voucher_rows.'.$i.'.id', ['value'=>$intra_location_stock_transfer_voucher_row->id,'type'=>'hidden']);
										?>
										<span class="itemQty" style="color:red ;font-size:10px;"></span>
										</td>
									
									<td width="25%" >
										<?php echo $this->Form->input('quantity', ['label' => false,'class' => 'form-control input-sm rightAligntextClass numberOnly quantity','placeholder'=>'Quantity','value'=>$intra_location_stock_transfer_voucher_row->quantity,'required']); ?>
									</td>
									<td align="center">
										<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
									</td>	
								</tr>
								<?php $i++; }?>	
								</tbody>
								<tfoot>
									<tr>
										<td>	
											<button type="button" class="add_row btn btn-default input-sm"><i class="fa fa-plus"></i> Add row</button>
										</td>
										<td colspan="3" >
											<div class="form-group">
											<label>Narration </label>
											<?php echo $this->Form->control('narration',['class'=>'form-control input-sm ','label'=>false,'placeholder'=>'Narration','rows'=>'2','value'=>$intraLocationStockTransferVoucher->narration]); ?>
											</div>
										</td>
									</tr>
								</tfoot>
							</table>
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

<table id="sample_table" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab">
			<td width="7%"></td>
			<td width="50%">
			    <input type="hidden" name="" class="outStock" value="0">
				<input type="hidden" name="" class="totStock " value="0">
				<?php echo $this->Form->control('item_id', ['options' => $itemOptions,'label' => false,'class' => 'form-control input-sm itemStock','required'=>'required']); ?>
				<span class="itemQty" style="color:red ;font-size:10px;"></span>
				</td>
			
			<td width="25%" >
				<?php echo $this->Form->input('quantity', ['label' => false,'class' => 'form-control input-sm rightAligntextClass numberOnly quantity','placeholder'=>'Quantity','required']); ?>
			</td>
			<td align="center">
				<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>

<?php
	$js="
	$(document).ready(function() { 
	$('.itemStock').die().live('change',function(){
		var itemQ=$(this).closest('tr');
		var itemId=$(this).val();
		var url='".$this->Url->build(["controller" => "IntraLocationStockTransferVouchers", "action" => "ajaxItemQuantity"])."';
		url=url+'/'+itemId
		$.ajax({
			url: url,
			type: 'GET'
			//dataType: 'text'
		}).done(function(response) {
			var fetch=$.parseJSON(response);
			var text=fetch.text;
			var type=fetch.type;
			var mainStock=fetch.mainStock;
			itemQ.find('.itemQty').html(text);
			itemQ.find('.totStock').val(mainStock);
			if(type=='true')
			{
				itemQ.find('.outStock').val(1);
			}
			else{
				itemQ.find('.outStock').val(0);
			}
		});	
		});
		
		$('.delete-tr').die().live('click',function() 
		{
			$(this).closest('tr').remove();
			rename_rows();
		});

		$('.add_row').click(function(){
			add_row();
			
		}) ;

		function add_row()
		{
			var tr=$('#sample_table tbody tr.main_tr').clone();
			$('#main_table tbody#main_tbody').append(tr);

			rename_rows();
		}
		rename_rows();
		function rename_rows()
		{
			var i=0;
			$('#main_table tbody#main_tbody tr.main_tr').each(function(){ 
				
				$(this).find('td:nth-child(1)').html(i+1);
				$(this).find('td:nth-child(2) select').select2().attr({name:'intra_location_stock_transfer_voucher_rows['+i+'][item_id]',id:'intra_location_stock_transfer_voucher_rows-'+i+'-item_id'});	

				$(this).find('td:nth-child(3) input').attr({name:'intra_location_stock_transfer_voucher_rows['+i+'][quantity]', id:'intra_location_stock_transfer_voucher_rows-'+i+'-quantity'});
				
				i++;
			});
		}
	
		ComponentsPickers.init();
	});
	
	$( document ).ready( stockLoad );
	function stockLoad()
	{
	$('#main_table tbody#main_tbody tr.main_tr').each(function(){ 
		var itemQ=$(this).closest('tr');
		var itemId=$('option:selected', this).attr('value');
		var url='".$this->Url->build(["controller" => "IntraLocationStockTransferVouchers", "action" => "ajaxItemQuantity"])."';
		url=url+'/'+itemId
		$.ajax({
			url: url,
			type: 'GET'
			//dataType: 'text'
		}).done(function(response) {
			var fetch=$.parseJSON(response);
			var text=fetch.text;
			var type=fetch.type;
			var mainStock=fetch.mainStock;
			itemQ.find('.itemQty').html(text);
			itemQ.find('.totStock').val(mainStock);
			if(type=='true')
			{
				itemQ.find('.outStock').val(1);
			}
			else{
				itemQ.find('.outStock').val(0);
			}
		});	
		});	
	}

	function checkValidation() 
	{  
		var transfer_from  = $('.transfer_from').val();
			var transfer_to = $('.transfer_to').val();
			if(transfer_from == transfer_to)
			{
				alert('Both the transfer location are same. Change the Location and try again...');
				return false;
			} 
		var StockDB=[]; var StockInput = {};
		$('#main_table tbody#main_tbody tr.main_tr').each(function()
		{
			var stock=$(this).find('td:nth-child(2) input.totStock').val();
			var item_id=$(this).find('td:nth-child(2) select.itemStock option:selected').val();
			var quantity=parseFloat($(this).find('td:nth-child(3) input.quantity').val());
			var existingQty=parseFloat(StockInput[item_id]);
			if(!existingQty){ existingQty=0; }
			StockInput[item_id] = quantity+existingQty;
			StockDB[item_id] = stock;
		});
		
		var c=1;
		$('#main_table tbody#main_tbody tr.main_tr').each(function()
		{
			var item_id=$(this).find('td:nth-child(2) select.itemStock option:selected').val();
			if(StockInput[item_id]>StockDB[item_id]){
				c=0;
			}
		});
		if(c==0){
			alert('Error: Stock is going in minus. Please Check');
			return false;
		}
		if(confirm('Are you sure you want to submit!'))
		{
			$('.submit').attr('disabled','disabled');
			$('.submit').text('Submiting...');
			return true;
		}
		else
		{
			return false;
		}
	}
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>