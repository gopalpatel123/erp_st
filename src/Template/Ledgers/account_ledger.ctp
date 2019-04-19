<?php
 $url_excel="/?".$url; 

/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Account Ledger report');
?>
<?php
	if($status=='excel'){
		$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Invoice_report_".$date.'_'.$time;
	//$from_date=date('d-m-Y',strtotime($from_date));
	//$to_date=date('d-m-Y',strtotime($to_date));
	
	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" ); 
	echo '<table border="1"><tr style="font-size:14px;"><td colspan="5" align="center" style="text-align:center;">'.$companies->name .'<br/>' .$companies->address .',<br/>'. $companies->state->name .'</span><br/>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>'.  $companies->phone_no . ' | Mobile : '. $companies->mobile .'<br/> GSTIN NO:'.
				$companies->gstin .'</span></td></tr></table>';
	}

 
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
		<?php if($status!='excel'){ ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Account Ledger</span>
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Ledgers/AccountLedger'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
		
			<div class="portlet-body">
				<div class="row">
					<form method="GET" >
						<div class="col-md-3">
							<div class="form-group">
								<label>Ledgers</label>
								<?php 
								echo $this->Form->input('ledger_id', ['options'=>$ledgers,'label' => false,'class' => 'form-control input-sm select2me' ,'value'=>$ledger_id, 'required'=>'required']); 
								?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>From Date</label>
								<?php 
								if(@$from_date=='1970-01-01')
								{
									$from_date = '';
								}
								elseif(!empty($from_date))
								{
									$from_date = date("d-m-Y",strtotime(@$from_date));
								}
								else{
								    $from_date = date("01-m-Y");
							   	}
								echo $this->Form->control('from_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','value'=>@$from_date,'data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'required'=>'required']); ?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>To Date</label>
								<?php 
								if(@$to_date=='1970-01-01')
								{
									$to_date = '';
								}
								elseif(!empty($to_date))
								{
									$to_date = date("d-m-Y",strtotime(@$to_date));
								}
								else{
									$to_date = date("d-m-Y");
								}
								echo $this->Form->control('to_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','value'=>@$to_date,'data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'required'=>'required']); ?>
							</div>
						</div>
						<div class="col-md-2" >
								<div class="form-group" style="padding-top:22px;"> 
									<button type="submit" class="btn btn-xs blue input-sm srch"> Go</button>
								</div>
						</div>	
					</form>
				</div>
				<?php } ?>
				<?php
				if(!empty($AccountingLedgers))
				{
				?>
					<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
						<thead>
							<tr>
								<th colspan="4">
								<span style="float:left";>
								<?php if(@$AccountingLedger->ledger->name){?>
									<?php echo 'Account Ledger of '; echo @$id= $AccountingLedger->ledger->name; echo ' '; echo 'Date from '; echo $from_date; echo ' to '; echo $to_date;?>
									<?php } ?>		</span>
								<span style="float:right";><b>Opening Balance</b></span></th>
								<th style="text-align:right";>
								<?php
									if(!empty($opening_balance))
									{
										echo $opening_balance.' '. $opening_balance_type;
									} 
								?>
								</th>
								
							</tr>
							<tr>
								<th width="10%" scope="col">Date</th>
								<th width="20%" scope="col" style="text-align:center";>Voucher Type</th>
								<th width="20%" scope="col" style="text-align:center";>Voucher No</th>
								<th width="25%" scope="col" style="text-align:center";>Debit</th>
								<th width="25%" scope="col" style="text-align:center";>Credit</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($AccountingLedgers))
						{
							$total_credit=0;$total_debit=0;
							//pr($AccountingLedgers->toArray());     exit;
						foreach($AccountingLedgers as $AccountingLedger)
						{   
							$id= $AccountingLedger->id;
						?>
							<tr>
								<td><?php echo date("d-m-Y",strtotime($AccountingLedger->transaction_date)); ?></td>
								<td>
								<?php 
								if(!empty($AccountingLedger->is_opening_balance=='yes')){
									echo 'Opening Balance';
									@$voucher_no='-';
									@$url_link='-';
								}
								else if(!empty($AccountingLedger->purchase_voucher_id)){
									@$voucher_no=$AccountingLedger->purchase_voucher->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'PurchaseVouchers','action' => 'view', $AccountingLedger->purchase_voucher_id],['target'=>'_blank']); 
									echo 'Purchase Vouchers';
								}
								else if(!empty($AccountingLedger->purchase_invoice_id)){
									echo 'Purchase Invoices';
									@$voucher_no=$AccountingLedger->purchase_invoice->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'PurchaseInvoices','action' => 'view', $AccountingLedger->purchase_invoice_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->purchase_return_id)){
									echo 'Purchase Returns';
									@$voucher_no=$AccountingLedger->purchase_return->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'PurchaseReturns','action' => 'view', $AccountingLedger->purchase_return_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->sales_invoice_id)){
									echo 'Sales Invoices';
									@$voucher_no=$AccountingLedger->sales_invoice->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'SalesInvoices','action' => 'sales-invoice-bill', $AccountingLedger->sales_invoice_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->sale_return_id)){
									echo 'Sales Returns';
									@$voucher_no=$AccountingLedger->sale_return->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'SaleReturns','action' => 'view', $AccountingLedger->sale_return_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->sales_voucher_id)){
									echo 'Sales Vouchers';
									@$voucher_no=$AccountingLedger->sales_voucher->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'SalesVouchers','action' => 'view', $AccountingLedger->sales_voucher_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->journal_voucher_id)){
									echo 'Journal Vouchers';
									@$voucher_no=$AccountingLedger->journal_voucher->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'JournalVouchers','action' => 'view', $AccountingLedger->journal_voucher_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->contra_voucher_id)){
									echo 'Contra Vouchers';
									@$voucher_no=$AccountingLedger->contra_voucher->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'ContraVouchers','action' => 'view', $AccountingLedger->contra_voucher_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->receipt_id)){
									echo 'Receipt Vouchers';
									@$voucher_no=$AccountingLedger->receipt->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'Receipts','action' => 'view', $AccountingLedger->receipt_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->payment_id)){
									echo 'Payment Vouchers';
									@$voucher_no=$AccountingLedger->payment->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'Payments','action' => 'view', $AccountingLedger->payment_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->credit_note_id)){
									echo 'Credit Note Vouchers';
									@$voucher_no=$AccountingLedger->credit_note->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'CreditNotes','action' => 'view', $AccountingLedger->credit_note_id],['target'=>'_blank']);
								}
								else if(!empty($AccountingLedger->debit_note_id)){
									echo 'Debit Note Vouchers';
									@$voucher_no=$AccountingLedger->debit_note->voucher_no;
									@$url_link=$this->Html->link($voucher_no,['controller'=>'DebitNotes','action' => 'view', $AccountingLedger->debit_note_id],['target'=>'_blank']);
								}
								?>
								</td>
								<td class="rightAligntextClass"><?php echo $url_link; ?></td>
								<td style="text-align:right";>
								<?php 
									if(!empty($AccountingLedger->total_debit_sum))
									{
										echo $AccountingLedger->total_debit_sum; 
										$total_debit +=round($AccountingLedger->total_debit_sum,2);
									}
									else
									{
										echo "-";
									}
								?>
								</td>
								<td style="text-align:right";>
								<?php 
									if(!empty($AccountingLedger->total_credit_sum))
									{
										echo $this->Money->moneyFormatIndia($AccountingLedger->total_credit_sum); 
										$total_credit +=round($AccountingLedger->total_credit_sum,2);
									}else
									{
										echo "-";
									}
								?>
								</td>
							</tr>
						<?php  } 
						} ?>
						</tbody>
						<tfoot>
							<tr>
								<td scope="col" colspan="3" style="text-align:right";><b>Total</b></td>
								<td scope="col" style="text-align:right";><?php echo $this->Money->moneyFormatIndia(@$total_debit, 2);?></td>
								<td scope="col" style="text-align:right";><?php echo $this->Money->moneyFormatIndia(@$total_credit);?></td>
							</tr>
							<tr>
								<td scope="col" colspan="4" style="text-align:right";><b>Closing Balance</b></td>
								<td scope="col" style="text-align:right";><b>
								<?php
									if($opening_balance_type='Dr'){
									@$closingBalance= $opening_balance+$total_debit-$total_credit;
									}
									else{
									@$closingBalance= $opening_balance+$total_credit-$total_debit;
									}
									if($closingBalance>0)
									{
									@$closing_bal_type='Dr';
									}
									else if($closingBalance<0){
									@$closing_bal_type='Cr';	
									}
									else{
									@$closing_bal_type='';	
									}
									echo $this->Money->moneyFormatIndia(round(abs($closingBalance),2)); echo ' '.$closing_bal_type;
								?>
								</b></td>
								
							</tr>
						</tfoot>
					</table>
				<?php } ?>
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
         ComponentsPickers.init();
	})";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>