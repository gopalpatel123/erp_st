<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Approve Stock Transfer');
?>
<div class="row">
	<div class="col-md-9">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Approve Stock Transfer</span>
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
								<?php echo $this->Form->control('transaction_date',['class'=>'form-control input-sm ','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y'),'readonly'=>'readonly']); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Transfer From</label>
								<?php echo $this->Form->control('transfer_from_location_id',['options'=>$TransferFromLocations,'class'=>'form-control input-sm transfer_from','label'=>false,'empty'=>'--select--','autofocus','disabled'=>'disabled']); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Transfer To</label>
								<?php echo $this->Form->control('transfer_to_location_id',['options'=>$TransferToLocations,'class'=>'form-control input-sm transfer_to','label'=>false,'empty'=>'--select--','readonly'=>'readonly','disabled'=>'disabled']); ?>
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
										<td><label>Sent Qty<label></td>
										<td><label>Received Qty<label></td>
									</tr>
								</thead>
								<tbody id='main_tbody' class="tab">
								
								<?php 
								$i=0; foreach($intraLocationStockTransferVoucher->intra_location_stock_transfer_voucher_rows as $intra_location_stock_transfer_voucher_row)
								{ ?>
								<tr class="main_tr" class="tab">
									<td width="7%"><?php echo $i+1;?></td>
									<td width="50%">
										<?php echo $this->Form->control('item', ['type'=>'text','label' => false,'class' => 'form-control input-sm','required'=>'required','value'=>$intra_location_stock_transfer_voucher_row->item->item_code.' '.$intra_location_stock_transfer_voucher_row->item->name,'readonly'=>'readonly']);
										echo $this->Form->control('intra_location_stock_transfer_voucher_rows.'.$i.'.item_id', ['type'=>'hidden','required'=>'required','value'=>$intra_location_stock_transfer_voucher_row->item_id,'readonly'=>'readonly']);
										echo $this->Form->control('intra_location_stock_transfer_voucher_rows.'.$i.'.id', ['value'=>$intra_location_stock_transfer_voucher_row->id,'type'=>'hidden']);
										?>
										</td>
									
									<td width="25%" >
										<?php echo $this->Form->input('intra_location_stock_transfer_voucher_rows.'.$i.'.quantity', ['label' => false,'class' => 'form-control input-sm getQty','placeholder'=>'Quantity','value'=>$intra_location_stock_transfer_voucher_row->quantity,'required','readonly'=>'readonly']); ?>
									</td>
									
									<td width="25%" >
										<?php echo $this->Form->input('intra_location_stock_transfer_voucher_rows.'.$i.'.receive_quantity', ['label' => false,'class' => 'form-control input-sm checkQty','placeholder'=>'Receive Quantity','value'=>$intra_location_stock_transfer_voucher_row->receive_quantity,'required']); ?>
									</td>
								</tr>
								<?php $i++; }?>	
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Narration </label>
								<?php echo $this->Form->control('narration_to',['class'=>'form-control input-sm ','label'=>false,'placeholder'=>'Narration','rows'=>'2']); ?>
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
	$(document).ready(function() 
	{
		$('.checkQty').die().live('keyup',function() 
		{
			var sendQty    = parseFloat($(this).closest('tr').find('.getQty').val()); 
			var receiveQty = parseFloat($(this).val());			 
			if(receiveQty>sendQty)
			{ 
				$(this).val('');
			}
		});
			
	});	
	
	function checkValidation() 
	{
		
		if(confirm('Are you sure you want to submit!')){
			$('.submit').attr('disabled','disabled');
	        $('.submit').text('Submiting...');
			return true;
			}else{
			
			        return false;
			 }
	}
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>