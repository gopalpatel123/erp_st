<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Bank Reconciliation');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>Bank Reconciliation
				</div>
				<div class="actions">
					<?php echo $this->Html->link('Bank Reconciliation View', '/accounting-entries/bankReconciliationView?from_date='.@$coreVariable["fyValidFrom"].'&to_date='.@$coreVariable["fyValidTo"],['escape' => false, 'class' =>'btn btn-sm green tooltips pull-right']); ?>
				</div>
			</div>
			<div class="portlet-body">
				<form method="get">
						<div class="row">
							<div class="col-md-3">
								<?php echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$bankOptions,'label' => false,'class' => 'form-control input-sm ledger select2me','required'=>'required','value'=>$ledger_id]); ?>
							</div>
							<div class="col-md-3">
								<?php
								if(empty($from_date))
								{
									$from_date = date("Y-m-01");
								}
								?>
								<?php echo $this->Form->control('from_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($from_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<?php
								if(empty($to_date))
								{
									$to_date = date("Y-m-d");
								}
								?>
								<?php echo $this->Form->control('to_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($to_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<span class="input-group-btn">
								<button class="btn btn-sm blue" type="submit">Go</button>
								</span>
							</div>	
						</div>
				</form>
				<?php if($ledger_id){ ?>
				</br>
				<div class="row">
					<table class="table table-condensed table-hover table-bordered">
						<thead>
							<tr>
								<th>Date</th>
								<th>Particulars</th>
								<th>Voucher Type</th>
								<th>Transaction Type </th>
								<th>Instrument No</th>
								<th>Instrument Date</th>
								<th>Bank Date</th>
								<th>Debit</th>
								<th>Credit</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$total_debit=0;
						$total_credit=0;
						foreach(@$AccountingEntries as $AccountingEntrie){ 
						if($AccountingEntrie->is_opening_balance != 'yes') {
							  $total_debit+=$AccountingEntrie->debit;
							  $total_credit+=$AccountingEntrie->credit;
							  ?>
							<tr>
								<td><?php echo date("d-m-Y",strtotime($AccountingEntrie->transaction_date)); ?></td>
								<td><?= $AccountingEntrie->ledger_name ?></td>
								<td><?= $AccountingEntrie->hlink ?></td>
								<td><?= $AccountingEntrie->transaction_type ?></td>
								<td><?= $AccountingEntrie->cheque_no ?></td>
								<td><?php if(($AccountingEntrie->cheque_date == '01-01-1970') || ($AccountingEntrie->cheque_date == 'NULL')){ 
									echo " ";
								}else{ echo date("d-m-Y",strtotime($AccountingEntrie->cheque_date)); }?></td>
								<td width="15%"><?php echo $this->Form->input('reconciliation_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker reconciliation_date','data-date-format' => 'dd-mm-yyyy','data-date-start-date'=>@$AccountingEntrie->transaction_date,'data-date-end-date'=>@$coreVariable[fyValidTo],'placeholder' => 'Reconcilation Date','ledger_id'=>$AccountingEntrie->id]); ?></td>
								<td><?php if($AccountingEntrie->debit>0){?><?=$this->Money->moneyFormatIndia($AccountingEntrie->debit) ?><?php } ?></td>
								<td><?php if($AccountingEntrie->credit>0){ ?><?=$this->Money->moneyFormatIndia($AccountingEntrie->credit) ?><?php } ?></td>
								<td><button type="button" accentry_id=<?php echo $AccountingEntrie->id ?> class="btn btn-primary btn-sm subdate"><i class="fa fa-arrow-right" ></i></button></td>
							</tr>
						<?php } }?>
						<?php $remaining_credit=0; $remaining_debit=0;$remaining=0;
						
						if($total_debit > $total_credit){
						@$remaining_debit=$total_debit-$total_credit;
						}
						if($total_debit < $total_credit){
						@$remaining_credit=$total_credit-$total_debit;
						}
						else if($total_debit == $total_credit){
						@$remaining_debit='';
						@$remaining_credit='';
						}
						$company_debit=$bank_debit+$remaining_debit;
						$company_credit=$bank_credit+$remaining_credit;
						$company_remaining=$company_debit-$company_credit;
						
						?>
						<tr>
							<td colspan="7" align="right">Balance as per company books</td><td><?php if($company_remaining>0){ ?><?=$company_remaining ?><?php } ?></td><td><?php  if($company_remaining<0 ) {?><?=abs($company_remaining) ?><?php } ?></td><td></td>
						</tr>
						<tr>
							<td colspan="7" align="right">Amount not reflected in bank</td><td><?php if($total_debit>0) { ?><?=$total_debit ?><?php } ?></td><td><?php if($total_credit>0) { ?><?=$total_credit ?><?php } ?></td><td></td>
						</tr>
						<tr>
							<td colspan="7" align="right">Balance as per bank</td><td><?php if($bank_remaining>0) { ?><?=$bank_remaining ?><?php } ?></td><td><?php if($bank_remaining<0) { ?><?=abs($bank_remaining) ?><?php } ?></td><td></td>
						</tr>
						</tbody>
					</table>
				</div>
				<?php } ?>
				</ul>
			</div>
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
	<!-- BEGIN VALIDATEION -->
	<?php echo $this->Html->script('/assets/global/plugins/jquery-validation/js/jquery.validate.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<!-- END VALIDATEION -->

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
			
			$('.subdate').die().click(function() { 
			//alert();
			var t=$(this);
			var accounting_entry_id=$(this).attr('accentry_id');
			var reconciliation_date=$(this).closest('tr').find('.reconciliation_date').val();
			if(reconciliation_date == ''){
				alert('Please Select Reconcilation Date');
			}else{
				
				var url='".$this->Url->build(["controller" => "AccountingEntries", "action" => "reconciliationDateUpdate"])."';
				url=url+'/'+accounting_entry_id+'/'+reconciliation_date,
				
				$.ajax({
					url: url,
				}).done(function(response) { 
					t.closest('tr').hide();
					 window.location.reload(true);
				});
			}
			
		});
			ComponentsPickers.init();	
		});
	";
?>
<?php echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom'));  ?>

