<?php
 $url_excel="/?".$url; 
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Purchase Return Report');
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
					<span class="caption-subject font-green-sharp bold ">Purchase Return Register</span>
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/purchaseReturns/Report/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
		<?php } ?>
			<div class="portlet-body table-responsive">
				<?php 
				if(!empty($purchaseReturns->toArray()))
				{
				?>
				<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
					<thead>
						<tr>
							<th scope="col" colspan="19" style="text-align:left";>Purchase Return Register According To  <?php if($from){ ?>Date From <?=$from ?><?php } ?><?php if($to){ ?>Date To <?=$to ?> <?php } ?><?php if($party_ids){ ?> Party <?php } ?><?php  if($invoice_no){ ?> Invoice No :<?=$invoice_no ?><?php } ?> </th>
						</tr>
						<tr>
							<th scope="col" style="text-align:center";>Supplier Code</th>
							<th scope="col" style="text-align:center";>Supplier Name</th>
							<th scope="col" style="text-align:center";>Invoice No</th>
							<th scope="col" style="text-align:center";>Invoice date</th>
							<th scope="col" style="text-align:center";>HSN Code</th>
							<th scope="col" style="text-align:center";>Item Code</th>
							<th scope="col" style="text-align:center";>Item Name</th>
							<th scope="col" style="text-align:center";>Quantity</th>
							<th scope="col" style="text-align:center";>Rate Per Unit</th>
							<th scope="col" style="text-align:center";>Discount %</th>
							<th scope="col" style="text-align:center";>Discount Amt.</th>
							<th scope="col" style="text-align:center";>PNF %</th>
							<th scope="col" style="text-align:center";>PNF Amt.</th>
							<th scope="col" style="text-align:center";>Taxable Value</th>
							<th scope="col" style="text-align:center";>CGST%</th>
							<th scope="col" style="text-align:center";>CGST Amt.</th>
							<th scope="col" style="text-align:center";>SGST%</th>
							<th scope="col" style="text-align:center";>SGST Amt.</th>
							<th scope="col" style="text-align:center";>IGST%</th>
							<th scope="col" style="text-align:center";>IGST Amt.</th>
							<th scope="col" style="text-align:center";>Net Amount</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$totalqty=0;
					$totalDiscount=0;
					$totalCgst=0;
					$totalSgst=0;
					$totalIgst=0;
					$totalNet=0;
					$totalTaxablevalue=0;
					$totalDiscountPnf=0;
					foreach($purchaseReturns->toArray() as $data){
					foreach($data->purchase_return_rows as $purchaseInvoicedata)
					{
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
						if($coreVariable['company_name']=='DANGI SAREES')
						{
							$field='DS';
						}
						else if($coreVariable['company_name']=='SUNIL TEXTILES')
						{
							$field='ST';
						}
						else if($coreVariable['company_name']=='SUNIL GARMENTS')
						{
							$field='SG';
						}

					    if($data->purchase_invoice->supplier_ledger->supplier_id==0 || $data->purchase_invoice->supplier_ledger->supplier_id=='')
						{
							$customerName='Cash';
							$customerCode='-';
						}
						else{
							$customerName=$data->purchase_invoice->supplier_ledger->name;
							$customerCode=$data->purchase_invoice->supplier_ledger->supplier->id;
						}
					
						if($purchaseInvoicedata->discount_percentage>0)
						{
						   $purchaseInvoicedata->discount_percentage;
						   $totrate=$purchaseInvoicedata->quantity*$purchaseInvoicedata->rate;
						   $dis=$totrate*$purchaseInvoicedata->discount_percentage/100;
						}
						else{
						   $dis=0;
						}
						$totalDiscount+=$dis;
						
						if($purchaseInvoicedata->pnf_percentage>0)
						{
						   $purchaseInvoicedata->pnf_percentage;
						   $totrates=$purchaseInvoicedata->quantity*$purchaseInvoicedata->rate;
						   $dispnf=$totrates*$purchaseInvoicedata->pnf_percentage/100;
						}
						else{
						   $dispnf=0;
						}
						$totalDiscountPnf+=$dispnf;
						
						
						
						if($data->purchase_invoice->supplier_ledger->supplier->state_id == $data->company->state_id)
						{
						    $purchaseInvoicedata->gst_value;
							$gst=$purchaseInvoicedata->gst_value/2;
						    $cgtax=$purchaseInvoicedata->item->FirstGstFigures->tax_percentage/2;
							$cgst=$gst;
							$sgst=$gst;
							$igst=0;
							$itax=0;
						}
						else
						{
							$cgst=0;
							$sgst=0;
							$igst=$purchaseInvoicedata->gst_value;
							$itax=$purchaseInvoicedata->item->FirstGstFigures->tax_percentage;
							$cgtax=0;
						}
						$totalCgst+=$cgst;
						$totalSgst+=$sgst;
						$totalIgst+=$igst;
						$totalNet+=$purchaseInvoicedata->net_amount;
						$totalTaxablevalue+=$purchaseInvoicedata->taxable_value;
					?>
					<tr>
					<td><?=$customerCode?></td>
					<td><?=$customerName?></td>
					<td><?= $field.'/'.$financialyear.'/'. h(str_pad($data->voucher_no, 3, '0', STR_PAD_LEFT))?></td>
					<td><?=$data->transaction_date?></td>
					<td><?=$purchaseInvoicedata->item->hsn_code?></td>
					<td><?=$purchaseInvoicedata->item->item_code?></td>
					<td><?=$purchaseInvoicedata->item->name?></td>
					<td class="rightAligntextClass"><?php echo $purchaseInvoicedata->quantity; $totalqty+=$purchaseInvoicedata->quantity;?></td>
					<td class="rightAligntextClass"><?=$this->Money->moneyFormatIndia($purchaseInvoicedata->rate)?></td>
					<td class="rightAligntextClass">
					<?php if($purchaseInvoicedata->discount_percentage==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $purchaseInvoicedata->discount_percentage.'%';?><?php }?>
					</td>
					<td class="rightAligntextClass">
					<?php if($dis==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $dis;?><?php }?>
					</td>
						<td class="rightAligntextClass">
					<?php if($purchaseInvoicedata->pnf_percentage==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $purchaseInvoicedata->pnf_percentage.'%';?><?php }?>
					</td>
					<td class="rightAligntextClass">
					<?php if($dispnf==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $dispnf;?><?php }?>
					</td>
					<td class="rightAligntextClass"><?=$purchaseInvoicedata->taxable_value?></td>
					<td class="rightAligntextClass">
					<?php if($cgtax==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $cgtax.'%';?><?php }?></td>
					<td class="rightAligntextClass">
					<?php if($cgst==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $cgst;?><?php }?>
					</td>
					<td class="rightAligntextClass">
					<?php if($cgtax==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $cgtax.'%';?><?php }?>
					</td>
					<td class="rightAligntextClass">
					<?php if($sgst==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $sgst;?><?php }?>
					</td>
					<td class="rightAligntextClass">
					<?php if($itax==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $itax.'%';?><?php }?>
					</td>
					<td class="rightAligntextClass">
					<?php if($igst==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $igst;?><?php }?>
					</td>
					<td class="rightAligntextClass"><?=$this->Money->moneyFormatIndia($purchaseInvoicedata->net_amount)?></td>
					</tr>
					<?php }}?>
					<tr>
					<td colspan="7" align="right"><b>&nbsp;</b></td>
					<td colspan="1" align="right"><b><?php echo $totalqty; ?></b></td>
					<td colspan="2" align="right"><b>&nbsp;</b></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalDiscount==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $this->Money->moneyFormatIndia($totalDiscount);?><?php }?>
					</b></td>
					<td>&nbsp;</td>
					<td class="rightAligntextClass"><b>
					<?php if($totalDiscountPnf==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $this->Money->moneyFormatIndia($totalDiscountPnf);?><?php }?>
					</b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalTaxablevalue)?></b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalCgst==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalCgst;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalSgst==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalSgst;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalIgst==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalIgst;?><?php }?>
					</b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalNet)?></b></td>
					</tr>
					</tbody>
					</table>
					<?php } else { ?>
					<?php echo '<b>No Invoice Found from '.$from.' - '.$to.'</b>';?>
					<?php } ?>
</div>
</div>
</div>					
</div>
