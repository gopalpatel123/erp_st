<?php
$url_excel="/?".$url; 
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Bank Reconciliation');
?>


<?php
	if($status=='excel'){
		$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="BankReconciliation_report_".$date.'_'.$time;
	//$from_date=date('d-m-Y',strtotime($from_date));
	//$to_date=date('d-m-Y',strtotime($to_date));
	
	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" ); 
	echo '<table border="1"><tr style="font-size:14px;"><td colspan="9" align="center" style="text-align:center;">'.$companies->name .'<br/>' .$companies->address .',<br/>'. $companies->state->name .'</span><br/>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>'.  $companies->phone_no . ' | Mobile : '. $companies->mobile .'<br/> GSTIN NO:'.
				$companies->gstin .'</span></td></tr></table>';
	}

 ?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
			<?php if($status!='excel'){ ?>
				<div class="caption">
					<i class="fa fa-cogs"></i>Bank Reconciliation
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/accounting-entries/bankReconciliationView/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
			<div class="portlet-body">
				<form method="get">
						<div class="row">
							<div class="col-md-3">
								<?php echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$bankOptions,'label' => false,'class' => 'form-control input-sm ledger select2me','required'=>'required','value'=>$ledger_id]); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->control('from_date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($from_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->control('to_date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($to_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<span class="input-group-btn">
								<button class="btn btn-sm blue" type="submit">Go</button>
								</span>
							</div>	
						</div>
				</form>
				<?php } ?>
				<?php if($ledger_id){ ?>
				<br/>
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
						<?php 	$total_debit=0;
								$total_credit=0; 
							foreach(@$AccountingEntries as $AccountingEntrie){ 
							if($AccountingEntrie->is_opening_balance != 'yes') {
								$total_debit+=$AccountingEntrie->debit;
								$total_credit+=$AccountingEntrie->credit
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
								<td width="15%"><?php echo date("d-m-Y",strtotime($AccountingEntrie->reconciliation_date)) ?></td>
								<td><?php if($AccountingEntrie->debit>0){ ?><?= $AccountingEntrie->debit ?><?php } ?></td>
								<td><?php if($AccountingEntrie->credit>0){ ?><?= $AccountingEntrie->credit ?><?php } ?></td>
								<td><button type="button" accentry_id=<?php echo $AccountingEntrie->id ?> reconciliation_date=<?php echo '0000-00-00' ?> class="btn btn-primary btn-sm subdate"><i class="fa fa-arrow-left" ></i></button></td>
							</tr>
						<?php } }?>
							<tr><td colspan="7" align="right"></td><td><?php if($total_debit>0) { ?><?=$total_debit ?><?php } ?></td><td><?php if($total_credit>0) { ?><?=$total_credit ?><?php } ?></td><td></td></tr>
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
			var reconciliation_date='yes';
			
				var url='".$this->Url->build(["controller" => "AccountingEntries", "action" => "reconciliationDateUpdate"])."';
				url=url+'/'+accounting_entry_id+'/'+reconciliation_date,
				
				$.ajax({
					url: url,
				}).done(function(response) { 
					t.closest('tr').hide();
					 window.location.reload(true);
				});
			
		});
			ComponentsPickers.init();	
		});
	";
?>
<?php echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom'));  ?>

