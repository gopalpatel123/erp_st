<?php
 $url_excel="/?".$url; 
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Purchase Report');
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
	echo '<table border="1"><tr style="font-size:14px;"><td colspan="22" align="center" style="text-align:center;">'.$companies->name .'<br/>' .$companies->address .',<br/>'. $companies->state->name .'</span><br/>
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
					<span class="caption-subject font-green-sharp bold ">Purchase Register</span>
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/purchaseInvoices/Report/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
		<?php } ?>
			<div class="portlet-body table-responsive">
				<?php 
				if(!empty($PurchaseInvoices))
				{
				?>
				<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
					<thead>
						<tr>
							<th scope="col" colspan="22" style="text-align:left";>Purchase Register According To  <?php if($from){ ?>Date From <?=$from ?><?php } ?><?php if($to){ ?>Date To <?=$to ?> <?php } ?><?php if($party_ids){ ?> Party <?php } ?><?php  if($invoice_no){ ?> Invoice No :<?=$invoice_no ?><?php } ?> </th>
						</tr>
						<tr>
							<th scope="col" style="text-align:center";>Supplier Code</th>
							<th scope="col" style="text-align:center";>Supplier Name</th>
							<th scope="col" style="text-align:center";>Invoice No</th>
							<th scope="col" style="text-align:center";>Invoice date</th>
							<th scope="col" style="text-align:center";>HSN Code</th>
							<th scope="col" style="text-align:center";>Item Code</th>
							<th scope="col" style="text-align:center";>Item Name</th>
							<th scope="col" style="text-align:center";>Size</th>
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
					$total_qty=0;
					$totalDiscount=0;
					$totalCgst=0;
					$totalSgst=0;
					$totalIgst=0;
					$totalNet=0;
					$totalTaxablevalue=0;
					$totalDiscountPnf=0;
					foreach($PurchaseInvoices as $purchaseInvoices){
					$total_qty_datewise=0;
					$totalDiscount_datewise=0;
					$totalDiscountPnf_datewise=0;
					$totalCgst_datewise=0;
					$totalSgst_datewise=0;
					$totalIgst_datewise=0;
					$totalNet_datewise=0;
					$totalTaxablevalue_datewise=0;
					foreach($purchaseInvoices as $data){
					foreach($data->purchase_invoice_rows as $purchaseInvoicedata)
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

					    if($data->supplier_ledger->supplier_id==0 || $data->supplier_ledger->supplier_id=='')
						{
							$customerName='Cash';
							$customerCode='-';
						}
						else{
							$customerName=$data->supplier_ledger->name;
							$customerCode=$data->supplier_ledger->supplier->id;
						}
					
						if($purchaseInvoicedata->discount_percentage>0)
						{
						   $purchaseInvoicedata->discount_percentage;
						   $totrate=$purchaseInvoicedata->quantity*$purchaseInvoicedata->rate;
						   $dis=round($totrate*$purchaseInvoicedata->discount_percentage/100,2);
						}
						else{
						   $dis=0;
						}
						$totalDiscount+=$dis;
						$totalDiscount_datewise+=$dis;
						if($purchaseInvoicedata->pnf_percentage>0)
						{
						   $purchaseInvoicedata->pnf_percentage;
						   $totrates=$purchaseInvoicedata->quantity*$purchaseInvoicedata->rate;
						   $dispnf=round($totrates*$purchaseInvoicedata->pnf_percentage/100,2);
						}
						else{
						   $dispnf=0;
						}
						$totalDiscountPnf+=$dispnf;
						$totalDiscountPnf_datewise+=$dispnf;
						
						
						if($data->supplier_ledger->supplier->state_id == $data->company->state_id)
						{
						    $purchaseInvoicedata->gst_value;
							$gst=round($purchaseInvoicedata->gst_value/2,2);
						    $cgtax=$purchaseInvoicedata->gst_percentage/2;
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
							$itax=$purchaseInvoicedata->gst_percentage;
							$cgtax=0;
						}
						$totalCgst+=$cgst;
						$totalSgst+=$sgst;
						$totalIgst+=$igst;
						$totalNet+=$purchaseInvoicedata->net_amount;
						$totalTaxablevalue+=$purchaseInvoicedata->taxable_value;
						$total_qty+=$purchaseInvoicedata->quantity;
						
						$totalCgst_datewise+=$cgst;
						$totalSgst_datewise+=$sgst;
						$totalIgst_datewise+=$igst;
						$totalNet_datewise+=$purchaseInvoicedata->net_amount;
						$totalTaxablevalue_datewise+=$purchaseInvoicedata->taxable_value;
						$total_qty_datewise+=$purchaseInvoicedata->quantity;
					?>
					<tr>
					<td><?=$customerCode?></td>
					<td><?=$customerName?></td>
					<td><?= $field.'/'.$financialyear.'/'. h(str_pad($data->voucher_no, 3, '0', STR_PAD_LEFT))?></td>
					<td><?=$data->transaction_date?></td>
					<td><?=$purchaseInvoicedata->item->hsn_code?></td>
					<td><?=$purchaseInvoicedata->item->item_code?></td>
					<td><?=$purchaseInvoicedata->item->name?></td>
					<td><?=@$purchaseInvoicedata->item->size->name?></td>
					<td class="rightAligntextClass"><?=$purchaseInvoicedata->quantity?></td>
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
					<tr style="background-color:#BCC6CC"><td colspan="8" align="right"><b>Total</b></td>
				
					<td class="rightAligntextClass"><b><?php echo $total_qty_datewise; ?></b></td>
					<td></td><td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalDiscount_datewise==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $this->Money->moneyFormatIndia($totalDiscount_datewise);?><?php }?>
					</b></td>
					<td>&nbsp;</td>
					<td class="rightAligntextClass"><b>
					<?php if($totalDiscountPnf_datewise==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $this->Money->moneyFormatIndia($totalDiscountPnf_datewise);?><?php }?>
					</b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalTaxablevalue_datewise)?></b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalCgst_datewise==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalCgst_datewise;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalSgst_datewise==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalSgst_datewise;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalIgst_datewise==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalIgst_datewise;?><?php }?>
					</b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalNet_datewise)?></b></td>
					</tr>
					<?php } ?>
					<tr style="background-color:#E5E4E2;;">
					<td colspan="8" align="right"><b>&nbsp;</b></td>
					<td class="rightAligntextClass"><b><?php echo $total_qty; ?></b></td>
					<td></td><td></td>
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
