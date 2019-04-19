<?php
 $url_excel="/?".$url; 
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'GSTR1 Report');
?>
	
<style>
table th {
    white-space: nowrap;
	font-size:12px !important;
}
table td {
	white-space: nowrap;
	font-size:11px !important;
}

<?php
	if($status=='excel'){
		$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="GSTR1_report_".$date.'_'.$time;
	//$from_date=date('d-m-Y',strtotime($from_date));
	//$to_date=date('d-m-Y',strtotime($to_date));
	
	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" ); 
	echo '<table border="1"><tr style="font-size:14px;"><td colspan="12" align="center" style="text-align:center;">'.$companies->name .'<br/>' .$companies->address .',<br/>'. $companies->state->name .'</span><br/>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>'.  $companies->phone_no . ' | Mobile : '. $companies->mobile .'<br/> GSTIN NO:'.
				$companies->gstin .'</span></td></tr></table>';
	}

 ?>

</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
		<?php if($status!='excel'){ ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">GSTR1 Report</span>
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/SalesInvoices/GstReport/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
		<?php } ?>
			<div class="portlet-body table-responsive">
			<?php if($status!='excel'){ ?>
				<form method="get">
						<div class="row">
							<div class="col-md-3">
								<?php echo $this->Form->control('from_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker from_date','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($from)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->control('to_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker to_date','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($to)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<span class="input-group-btn">
								<button class="btn blue" type="submit">Go</button>
								</span>
							</div>	
						</div>
				</form>
				<?php } ?>
				<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
					<thead>
						<tr>
							<th scope="col" colspan="12" style="text-align:center;background-color:#8594a3";>B2C </th>
						</tr>
						<tr>
							<th scope="col" style="text-align:center";>Sr.no</th>
							<th scope="col" style="text-align:center";>Party Name</th>
							<th scope="col" style="text-align:center";>GSTIn</th>
							<th scope="col" style="text-align:center";>State</th>
							<th scope="col" style="text-align:center";>Invoice No</th>
							<th scope="col" style="text-align:center";>Bill date</th>
							<th scope="col" style="text-align:center";>GST Rate</th>
							<th scope="col" style="text-align:center";>Sub Total</th>
							<th scope="col" style="text-align:center";>CGST Amt.</th>
							<th scope="col" style="text-align:center";>SGST Amt.</th>
							<th scope="col" style="text-align:center";>IGST Amt.</th>
							<th scope="col" style="text-align:center";>Total.</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$i=1;
					$totalCgstB2C=0;
					$totalSgstB2C=0;
					$totalIgstB2C=0;
					$totaltaxableB2C=0;
					$totalnetamountB2C=0;
					foreach($salesInvoicesDatas as $data){ 
						if(empty($data->party_ledger->customer->gstin)){
						foreach($data->sales_invoice_rows as $sales_invoice_rows_data) 
						{ // pr($sales_invoice_rows_data); exit;
							$date = date('Y-m-d', strtotime($data->transaction_date));
							$d = date_parse_from_format('Y-m-d',$date);
							$yr=$d["year"];$year= substr($yr, -2);
							if($d["month"]=='01' || $d["month"]=='02' || $d["month"]=='03')
							{
								$startYear=$year-1;
								$endYear=$year;
								$financialyear=$startYear.'-'.$endYear;
							}
							else
							{
								$startYear=$year;
								$endYear=$year+1;
								$financialyear=$startYear.'-'.$endYear;
							}
							$field='BFP';
					?>	<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $data->party_ledger->name; ?></td>
							<td><?php echo @$data->party_ledger->customer->gstin; ?></td>
							<td><?php echo @$data->party_ledger->customer->state->name; ?></td>
							<td><?= $field.'/'.$financialyear.'/'. h(str_pad($data->voucher_no, 3, '0', STR_PAD_LEFT))?></td>
							<td><?=$data->transaction_date?></td>
							<td><?=$sales_invoice_rows_data->gst_figure->name?></td>
							<td><?=$sales_invoice_rows_data->total_taxable_amt?></td>
							<?php if(@$data->party_ledger->customer->state_id==46 || empty(@$data->party_ledger->customer) ){ ?>
								<td><?=$sales_invoice_rows_data->gst_total/2?></td>
								<td><?=$sales_invoice_rows_data->gst_total/2?></td>
								<td></td>
								<?php
									$totalCgstB2C+=$sales_invoice_rows_data->gst_total/2;
									$totalSgstB2C+=$sales_invoice_rows_data->gst_total/2;
								?>
							<?php }else{ ?>
								<td></td>
								<td></td>
								<td><?=$sales_invoice_rows_data->gst_total;?></td>
								<?php
									$totalIgstB2C+=$sales_invoice_rows_data->gst_total;
								?>
							<?php } ?>
							<td><?=$sales_invoice_rows_data->total_net_amt?></td>
							<?php
									$totaltaxableB2C+=$sales_invoice_rows_data->total_taxable_amt;
									$totalnetamountB2C+=$sales_invoice_rows_data->total_net_amt;
							?>
						</tr>
					<?php
						}
					}
					}
					?>
					<tr>
						<td align="right" colspan="7"><b>Total</td>
						<td ><b><?php echo $totaltaxableB2C ?></td>
						<td ><b><?php echo $totalCgstB2C ?></td>
						<td ><b><?php echo $totalSgstB2C ?></td>
						<td ><b><?php echo $totalIgstB2C ?></td>
						<td ><b><?php echo $totalnetamountB2C ?></td>
					</tr>
					</tbody>
					</table>
					<br>
					<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
					<thead>
						<tr>
							<th scope="col" colspan="12" style="text-align:center;background-color:#bdc193";>B2B </th>
						</tr>
						<tr>
							<th scope="col" style="text-align:center";>Sr.no</th>
							<th scope="col" style="text-align:center";>Party Name</th>
							<th scope="col" style="text-align:center";>GSTIn</th>
							<th scope="col" style="text-align:center";>State</th>
							<th scope="col" style="text-align:center";>Invoice No</th>
							<th scope="col" style="text-align:center";>Bill date</th>
							<th scope="col" style="text-align:center";>GST Rate</th>
							<th scope="col" style="text-align:center";>Sub Total</th>
							<th scope="col" style="text-align:center";>CGST Amt.</th>
							<th scope="col" style="text-align:center";>SGST Amt.</th>
							<th scope="col" style="text-align:center";>IGST Amt.</th>
							<th scope="col" style="text-align:center";>Total.</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$i=1;
					$totalCgstB2B=0;
					$totalSgstB2B=0;
					$totalIgstB2B=0;
					$totaltaxableB2B=0;
					$totalnetamountB2B=0;
					foreach($salesInvoicesDatas as $data){
						if(!empty($data->party_ledger->customer->gstin)){
						foreach($data->sales_invoice_rows as $sales_invoice_rows_data) 
						{ // pr($sales_invoice_rows_data); exit;
							$date = date('Y-m-d', strtotime($data->transaction_date));
							$d = date_parse_from_format('Y-m-d',$date);
							$yr=$d["year"];$year= substr($yr, -2);
							if($d["month"]=='01' || $d["month"]=='02' || $d["month"]=='03')
							{
								$startYear=$year-1;
								$endYear=$year;
								$financialyear=$startYear.'-'.$endYear;
							}
							else
							{
								$startYear=$year;
								$endYear=$year+1;
								$financialyear=$startYear.'-'.$endYear;
							}
							$field='BFP';
					?>	<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $data->party_ledger->name; ?></td>
							<td><?php echo $data->party_ledger->customer->gstin; ?></td>
							<td><?php echo $data->party_ledger->customer->state->name; ?></td>
							<td><?= $field.'/'.$financialyear.'/'. h(str_pad($data->voucher_no, 3, '0', STR_PAD_LEFT))?></td>
							<td><?=$data->transaction_date?></td>
							<td><?=$sales_invoice_rows_data->gst_figure->name?></td>
							<td><?=$sales_invoice_rows_data->total_taxable_amt?></td>
							<?php if($data->party_ledger->customer->state_id==46){ ?>
								<td><?=$sales_invoice_rows_data->gst_total/2?></td>
								<td><?=$sales_invoice_rows_data->gst_total/2?></td>
								<td></td>
								<?php
									$totalCgstB2B+=$sales_invoice_rows_data->gst_total/2;
									$totalSgstB2B+=$sales_invoice_rows_data->gst_total/2;
								?>
							<?php }else{ ?>
								<td></td>
								<td></td>
								<td><?=$sales_invoice_rows_data->gst_total;?></td>
								<?php
									$totalIgstB2B+=$sales_invoice_rows_data->gst_total;
								?>
							<?php } ?>
							<td><?=$sales_invoice_rows_data->total_net_amt?></td>
							<?php
									$totaltaxableB2B+=$sales_invoice_rows_data->total_taxable_amt;
									$totalnetamountB2B+=$sales_invoice_rows_data->total_net_amt;
							?>
						</tr>
					<?php
							}
						}
					}
					?>
					<tr>
						<td align="right" colspan="7"><b>Total</td>
						<td ><b><?php echo $totaltaxableB2B ?></td>
						<td ><b><?php echo $totalCgstB2B ?></td>
						<td ><b><?php echo $totalSgstB2B ?></td>
						<td ><b><?php echo $totalIgstB2B ?></td>
						<td ><b><?php echo $totalnetamountB2B ?></td>
					</tr>
					</tbody>
					</table>
					<br>
					
					<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
					<thead>
						<tr>
							<th scope="col" colspan="10" style="text-align:center; background-color:#979aa0";><b>STATE WISE B2C SUPPLIES</th>
						</tr>
						<tr>
							<th scope="col" style="text-align:center";>Sr.no</th>
							<th scope="col" style="text-align:center";>Ship to state</th>
							<!--<th scope="col" style="text-align:center";>Transaction Type</th>-->
							<th scope="col" style="text-align:center";>GST Rate</th>
							<th scope="col" style="text-align:center";>Invoice Amount</th>
							<th scope="col" style="text-align:center";>Tax Exclusive Gross</th>
							<th scope="col" style="text-align:center";>Total Tax Amount</th>
							<th scope="col" style="text-align:center";>CGST</th>
							<th scope="col" style="text-align:center";>SGST</th>
							<th scope="col" style="text-align:center";>IGST</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$i=1;
					$totalCgst=0;
					$totalSgst=0;
					$totalIgst=0;
					$totaltaxable=0;
					$totalnetamount=0;
					foreach($StateWiseTaxableAmt as $key=>$datas){ 
						foreach($datas as $key1=>$dt) 
						{   
						
					?>	<tr>
							<td><?php echo $i++; ?></td>
							<td>
								<?php 
								if(empty($StateName[$key])){ echo "RAJASTHAN"; } else {  echo $StateName[$key]; }?>
								
							</td>
							<!--<td><?php echo "Cash"; ?></td>-->
							<td><?php echo $GstFiguresDatas[$key1]; ?></td>
							<td><?php echo $StateWiseTaxableAmt[$key][$key1]+$StateWiseGst[$key][$key1]; 
								$totalnetamount+=$StateWiseTaxableAmt[$key][$key1]+$StateWiseGst[$key][$key1];
							?></td>
							<td><?php echo $StateWiseTaxableAmt[$key][$key1]; 
								$totaltaxable+=$StateWiseTaxableAmt[$key][$key1];
							?></td>
							<td><?php echo $StateWiseGst[$key][$key1]; 
							
							?></td>
							<?php if($key==46 || empty($key)){ ?>
							<td><?php echo $StateWiseGst[$key][$key1]/2; 
							$totalCgst+=$StateWiseGst[$key][$key1]/2;
							?></td>
							<td><?php echo $StateWiseGst[$key][$key1]/2; 
							$totalSgst+=$StateWiseGst[$key][$key1]/2;
							?></td>
							<td>
							<?php }else{ ?>
							<td></td>
							<td></td>
							<td><?php echo $StateWiseGst[$key][$key1]; 
							$totalIgst+=$StateWiseGst[$key][$key1];
							?></td>
							<?php } ?>
							
						</tr>
					<?php
						}
					}
					?>
					<tr>
						<td align="right" colspan="3"><b>Total</td>
						<td ><b><?php echo $totalnetamount ?></td>
						<td ><b><?php echo $totaltaxable ?></td>
						<td ><b><?php echo $totalIgst ?></td>
						<td ><b><?php echo $totalCgst ?></td>
						<td ><b><?php echo $totalSgst ?></td>
						<td ><b><?php echo $totalIgst ?></td>
					</tr>
					</tbody>
					</table>
					<br>
					<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
						<thead>
							
							<tr>
								<th scope="col" style="text-align:center";></th>
								<th scope="col" style="text-align:center";>Invoice Amount</th>
								<th scope="col" style="text-align:center";>Tax Exclusive Gross</th>
								<th scope="col" style="text-align:center";>Total Tax Amount</th>
								<th scope="col" style="text-align:center";>CGST</th>
								<th scope="col" style="text-align:center";>SGST</th>
								<th scope="col" style="text-align:center";>IGST</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>B2C</td>
								<td ><?php echo $totalnetamountB2C; ?></td>
								<td ><?php echo $totaltaxableB2C; ?></td>
								<td ><?php echo $totalCgstB2C+$totalSgstB2C+$totalIgstB2C; ?></td>
								<td ><?php echo $totalCgstB2C; ?></td>
								<td ><?php echo $totalSgstB2C; ?></td>
								<td ><?php echo $totalIgstB2C; ?></td>
						
							</tr>
							<tr>
								<td>B2B</td>
								<td ><?php echo $totalnetamountB2B ?></td>
								<td ><?php echo $totaltaxableB2B ?></td>
								<td ><?php echo $totalCgstB2B+$totalSgstB2B+$totalIgstB2B; ?></td>
								<td ><?php echo $totalCgstB2B ?></td>
								<td ><?php echo $totalSgstB2B ?></td>
								<td ><?php echo $totalIgstB2B ?></td>
						
							</tr>
							<tr>
							<td>Total</td>
							<td><b><?php echo $totalnetamountB2B+$totalnetamountB2C; ?></td>
							<td><b><?php echo $totaltaxableB2B+$totaltaxableB2C; ?></td>
							<td><b><?php echo $totalCgstB2C+$totalSgstB2C+$totalIgstB2C+$totalCgstB2B+$totalSgstB2B+$totalIgstB2B; ?></td>
							<td><b><?php echo $totalCgstB2C+$totalCgstB2B; ?></td>
							<td><b><?php echo $totalSgstB2C+$totalSgstB2B; ?></td>
							<td><b><?php echo $totalIgstB2C+$totalIgstB2B; ?></td>
							
							</tr>
						</tbody>
						
					</table>
					<br>
					<!--<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
						<thead>
							<tr>
								<th scope="col" style="text-align:center";>Description</th>
								<th scope="col" style="text-align:center";>HSN Code</th>
								<th scope="col" style="text-align:center";>UQC</th>
								<th scope="col" style="text-align:center";>Quantity</th>
								<th scope="col" style="text-align:center";>Invoice Amount</th>
								<th scope="col" style="text-align:center";>Tax Exclusive Gross</th>
								<th scope="col" style="text-align:center";>Total Tax Amount</th>
								<th scope="col" style="text-align:center";>CGST</th>
								<th scope="col" style="text-align:center";>SGST</th>
								<th scope="col" style="text-align:center";>IGST</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						foreach ($hsn as $hsn1){
							if($hsn1){ //pr($hsn1);pr($hsnIgst[$hsn1]); pr($hsngst[$hsn1]); exit;
							$tinvoice=$taxable_value[$hsn1]+@$hsngst[$hsn1]+@$hsnIgst[$hsn1];
							$ttax=@$hsngst[$hsn1]+@$hsnIgst[$hsn1];
						?>
							<tr>
								<td></td>
								<td style="text-align:center;"><?= h($hsn1) ?></td>
								<td style="text-align:center;"><?= h($unit[$hsn1]) ?></td>
								<td style="text-align:center;"><?= h(@$quantity[$hsn1]) ?></td>
								<td style="text-align:center;"><?= h(round(@$tinvoice,2)) ?></td>
								<td style="text-align:center;"><?= h(round($taxable_value[$hsn1],2)) ?></td>
								<td style="text-align:center;"><?= h(round($ttax,2)) ?></td>
								<td style="text-align:center;"><?= h(round(@$hsngst[$hsn1]/2,2)) ?></td>
								<td style="text-align:center;"><?= h(round(@$hsngst[$hsn1]/2,2)) ?></td>
								<td style="text-align:center;"><?= h(round(@$hsnIgst[$hsn1],2)) ?></td>
								
						
							</tr>
						<?php } } ?>
							
						</tbody>
					</table>-->
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
			$('.group_name').die().live('click',function(e){
				   var current_obj=$(this);
				   var group_id=$(this).attr('group_id');
				   var child=$(this).attr('child');
				   var status=$(this).attr('status');
				   var parent=$(this).attr('parent');
					if(child == 'yes' && status=='open' && parent=='no')
					{
						current_obj.attr('status','open');
						current_obj.attr('child','no');
						current_obj.closest('tr').next().remove();
						
					}else if(status=='open' && parent=='yes')
					{ 
						current_obj.attr('status','close');
						current_obj.attr('child','no');
						current_obj.closest('tr').next().remove();
						
						
					} else{  
						var from_date = $('.from_date').val();
						var to_date = $('.to_date').val(); 
						var url='".$this->Url->build(['controller'=>'AccountingEntries','action'=>'firstSubGroupsPnl']) ."';
						url=url+'/'+group_id +'/'+from_date+'/'+to_date,
						$.ajax({
							url: url,
						}).done(function(response) { 
							current_obj.attr('status','open');
							current_obj.attr('child','yes');
							 current_obj.addClass('group_a');
							current_obj.closest('tr').find('span').addClass('group_a');
							var a='<tr><td colspan=2>'+response+'</td></tr>';
							$(a).insertAfter(current_obj.closest('tr'));
						});	
					}  
		  
			});	
			
		

			ComponentsPickers.init();

			
		});
	";
?>
<?php echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom'));  ?>

