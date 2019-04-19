<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Edit');
?>
<div class="row">
	<div class="col-md-9">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Edit Goods Recieve Note</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($grn,['onsubmit'=>'return checkValidation()']) ?>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Voucher No :</label>&nbsp;&nbsp;
							<?= h('#'.str_pad($grn->voucher_no, 4, '0', STR_PAD_LEFT)) ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Transaction Date <span class="required">*</span></label>
							<?php echo $this->Form->control('transaction_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y'),'required'=>'required']); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Reference No</label>
							<?php echo $this->Form->control('reference_no', ['label' => false,'class' => 'form-control input-sm ','placeholder'=>'Reference No.', 'autofocus'=>'autofocus']); ?>
						</div>	
					</div>
					<div class="col-md-3">
						<div class="form-group">
						<label>Supplier</label>
							<?php echo $this->Form->control('supplier_ledger_id',['empty'=>'-Select Supplier-', 'class'=>'form-control input-sm supplier_ledger_id select2me','label'=>false, 'options' => $partyOptions,'required'=>'required']);
							?>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="table-responsive">
						<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
							<thead>
								<tr>
									<td><label>Item<label></td>
									<td><label>Quantity<label></td>
									<td><label>Purchase Rate Per Unit<label></td>
									<td><label>Sale Rate Per Unit<label></td>
									<td><label>Action<label></td>
									
								</tr>
							</thead>
							<tbody id='main_tbody' class="tab">
								<?php $i=0; foreach($grn->grn_rows as $grnrow):?>
								<tr class="main_tr" class="tab">
									<td width="15%">
										<?php echo $this->Form->input('item_id', ['empty'=>'---Select---','options'=>$itemOptions,'label' => false,'class' => 'form-control input-medium ','required'=>'required','value'=>$grnrow->item_id]); 
										
										echo $this->Form->input('grn_rows.'.$i.'.id',['type'=>'hidden','value'=>$grnrow->id]);
										?>
									</td>
									<td width="25%" >
										<?php echo $this->Form->input('quantity', ['label' => false,'class' => 'form-control input-sm numberOnly rightAligntextClass','placeholder'=>'Qty','required','value'=>$grnrow->quantity]); ?>
									</td>
									<td width="25%">
										<?php echo $this->Form->input('purchase_rate', ['label' => false,'class' => 'form-control input-sm total numberOnly rightAligntextClass','required'=>'required','placeholder'=>'Purchase Rate','required','value'=>$grnrow->purchase_rate]); ?>	
									</td>
									<td width="25%">
										<?php echo $this->Form->input('sale_rate', ['label' => false,'class' => 'form-control input-sm total numberOnly rightAligntextClass','required'=>'required','placeholder'=>'Sale Rate','required','value'=>$grnrow->sale_rate]); ?>	
									</td>
									<td align="center">
										<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
									</td>
								</tr>
								<?php endforeach; $i++;?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" >	
										<button type="button" class="add_row btn btn-default input-sm"><i class="fa fa-plus"></i> Add row</button>
									</td>
									<td>
										<?php echo $this->Form->input('total_purchase', ['label' => false,'class' => 'form-control input-sm rightAligntextClass','placeholder'=>'0.00','readonly']); ?>
									</td>
									<td>
										<?php echo $this->Form->input('total_sale', ['label' => false,'class' => 'form-control input-sm rightAligntextClass','placeholder'=>'0.00','readonly']); ?>
									</td>
									<td></td>
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
			<td width="15%">
				<?php echo $this->Form->input('item_id', ['empty'=>'---Select---','options'=>$itemOptions,'label' => false,'class' => 'form-control input-medium ','required'=>'required']); ?>
			</td>
			<td width="25%" >
				<?php echo $this->Form->input('quantity', ['label' => false,'class' => 'form-control input-sm numberOnly rightAligntextClass','placeholder'=>'Qty','required']); ?>
			</td>
			<td width="25%">
				<?php echo $this->Form->input('purchase_rate', ['label' => false,'class' => 'form-control input-sm total numberOnly rightAligntextClass','required'=>'required','placeholder'=>'Purchase Rate','required']); ?>	
			</td>
			<td width="25%">
				<?php echo $this->Form->input('sale_rate', ['label' => false,'class' => 'form-control input-sm total numberOnly rightAligntextClass','required'=>'required','placeholder'=>'Sale Rate','required']); ?>	
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
		$('.delete-tr').die().live('click',function() 
		{
			$(this).closest('tr').remove();
			rename_rows();
			calculate();
		});

		ComponentsPickers.init();
	
		
		calculate();
		
		function calculate()
		{ 
			total_purchase=0;
			total_sale=0;
			
			$('#main_table tbody#main_tbody tr.main_tr').each(function()
			{ 
				var purchase_rate=parseFloat($(this).find('td:nth-child(3) input').val());
				if(!purchase_rate){ purchase_rate=0; }
				total_purchase=total_purchase+purchase_rate;
				var sale_rate=parseFloat($(this).find('td:nth-child(4) input').val());
				if(!sale_rate){ sale_rate=0; }
				total_sale=total_sale+sale_rate;
			});
			$('input[name=total_purchase]').val(total_purchase.toFixed(2));
			$('input[name=total_sale]').val(total_sale.toFixed(2));
		}
		
		$('.total').die().live('keyup',function() { 
			calculate();
		});
		
		
		
	
		
		$('.add_row').click(function(){
			add_row();
		}) ;


		function add_row()
		{
			var tr=$('#sample_table tbody tr.main_tr').clone();
			$('#main_table tbody#main_tbody').append(tr);
			rename_rows();
			calculate();
		}


		rename_rows();

		function rename_rows()
		{
			var i=0;
			$('#main_table tbody#main_tbody tr.main_tr').each(function()
			{ 
				$(this).find('td:nth-child(1) select').select2().attr({name:'grn_rows['+i+'][item_id]', id:'grn_rows-'+i+'-item_id'});
				$(this).find('td:nth-child(2) input').attr({name:'grn_rows['+i+'][quantity]', id:'grn_rows-'+i+'-quantity'});
				$(this).find('td:nth-child(3) input').attr({name:'grn_rows['+i+'][purchase_rate]', id:'grn_rows-'+i+'-purchase_rate'});
				$(this).find('td:nth-child(4) input').attr({name:'grn_rows['+i+'][sale_rate]', id:'grn_rows-'+i+'-sale_rate'});

				i++;
			});
			calculate();
		}
	});	
	function checkValidation()
	{
	        $('.submit').attr('disabled','disabled');
	        $('.submit').text('Submiting...');
    }
";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>