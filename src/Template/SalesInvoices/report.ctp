<?php
 $url_excel="/?".$url; 
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Sales Report');
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
	echo '<table border="1"><tr style="font-size:14px;"><td colspan="20" align="center" style="text-align:center;">'.$companies->name .'<br/>' .$companies->address .',<br/>'. $companies->state->name .'</span><br/>
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
					<span class="caption-subject font-green-sharp bold ">Sales Report</span>
				</div>
				<div class="actions">
					
					<?php echo $this->Html->link( '<i class="fa fa-repeat"></i> Invoice Wise Report', '/SalesInvoices/InvoiceReport/'.@$url_excel,['class' =>'btn btn-sm blue tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Invoice Wise Report']); ?>
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/SalesInvoices/Report/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
		<?php } ?>
			<div class="portlet-body table-responsive">
				<?php 
				if(!empty($SalesInvoices))
				{
				?>
				<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
					<thead>
						<tr>
							<th scope="col" colspan="28" style="text-align:left";>Sales Register According To  <?php if($from){ ?>Date From <?=$from ?><?php } ?><?php if($to){ ?>Date To <?=$to ?> <?php } ?><?php if($party_ids){ ?> Party <?php } ?><?php  if($invoice_no){ ?> Invoice No :<?=$invoice_no ?><?php } ?> </th>
						</tr>
						<tr>
							<th scope="col" style="text-align:center";>Customer Code</th>
							<th scope="col" style="text-align:center";>Customer Name</th>
							<th scope="col" style="text-align:center";>Invoice No</th>
							<th scope="col" style="text-align:center";>Invoice date</th>
							<th scope="col" style="text-align:center";>HSN Code</th>
							<th scope="col" style="text-align:center";>Item Code</th>
							<th scope="col" style="text-align:center";>Item Name</th>
							<th scope="col" style="text-align:center";>Stock Group</th>
							<th scope="col" style="text-align:center";>Stock Sub Group</th>
							<th scope="col" style="text-align:center";>Size</th>
							<th scope="col" style="text-align:center";>Quantity</th>
							<th scope="col" style="text-align:center";>Rate Per Unit</th>
							<th scope="col" style="text-align:center";>Discount %</th>
							<th scope="col" style="text-align:center";>Discount Amt.</th>
							<th scope="col" style="text-align:center";>Taxable Value</th>
							<th scope="col" style="text-align:center";>CGST 2.5%</th>
							<th scope="col" style="text-align:center";>CGST Amt.</th>
							<th scope="col" style="text-align:center";>SGST 2.5%</th>
							<th scope="col" style="text-align:center";>SGST Amt.</th>
							<th scope="col" style="text-align:center";>CGST 6%</th>
							<th scope="col" style="text-align:center";>CGST Amt.</th>
							<th scope="col" style="text-align:center";>SGST 6%</th>
							<th scope="col" style="text-align:center";>SGST Amt.</th>
							<th scope="col" style="text-align:center";>CGST 9%</th>
							<th scope="col" style="text-align:center";>CGST Amt.</th>
							<th scope="col" style="text-align:center";>SGST 9%</th>
							<th scope="col" style="text-align:center";>SGST Amt.</th>
							<th scope="col" style="text-align:center";>CGST 14%</th>
							<th scope="col" style="text-align:center";>CGST Amt.</th>
							<th scope="col" style="text-align:center";>SGST 14%</th>
							<th scope="col" style="text-align:center";>SGST Amt.</th>
							<th scope="col" style="text-align:center";>IGST%</th>
							<th scope="col" style="text-align:center";>IGST Amt.</th>
							<th scope="col" style="text-align:center";>Net Amount</th>
							<th scope="col" style="text-align:center";>Cash</th>
							<th scope="col" style="text-align:center";>Credit</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$total_qty=0;
					$totalDiscount=0;
					$totalCgst5=0;
					$totalSgst5=0;
					$totalCgst12=0;
					$totalCgst18=0;
					$totalSgst12=0;
					$totalCgst28=0;
					$totalSgst28=0;
					$totalSgst18=0;
					$totalIgst=0;
					$totalNet=0;
					$totalTaxablevalue=0;
					$totalCash1=0;
					$totalCredit1=0;
					foreach($SalesInvoices as $salesInvoices){
					$total_qty_datewise=0;
					$totalDiscount_datewise=0;
					$totalCgst_datewise5=0;
					$totalSgst_datewise5=0;
					$totalCgst_datewise12=0;
					$totalCgst_datewise18=0;
					$totalCgst_datewise28=0;
					$totalSgst_datewise12=0;
					$totalSgst_datewise18=0;
					$totalSgst_datewise28=0;
					$totalIgst_datewise=0;
					$totalNet_datewise=0;
					$totalCash=0;
					$totalCredit=0;
					$totalTaxablevalue_datewise=0;
					foreach($salesInvoices as $data){
					foreach($data->sales_invoice_rows as $salesInvoicedata) 
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

					    if($data->party_ledger->customer_id==0 || $data->party_ledger->customer_id=='')
						{
							$customerName='Cash';
							$customerCode='-';
						}
						else{
							$customerName=$data->party_ledger->name;
							$customerCode=$data->party_ledger->customer->customer_id;
						}
					
						if($salesInvoicedata->discount_percentage>0)
						{
						   $salesInvoicedata->discount_percentage;
						   $totrate=$salesInvoicedata->quantity*$salesInvoicedata->rate;
						   $dis=round($totrate*$salesInvoicedata->discount_percentage/100,2);
						}
						else{
						   $dis=0;
						}
						$totalDiscount+=$dis;
						$totalDiscount_datewise+=$dis;
						if($data->total_igst=='' || $data->total_igst==0)
						{
						    $salesInvoicedata->gst_value;
							$gst=round($salesInvoicedata->gst_value/2,2);
						    $cgtax=$salesInvoicedata->gst_figure->tax_percentage/2;
							$cgst=$gst;
							$sgst=$gst;
							$igst=0;
							$itax=0;
						}
						else
						{
							$cgst=0;
							$sgst=0;
							$igst=$salesInvoicedata->gst_value;
							$itax=$salesInvoicedata->gst_figure->tax_percentage;
							$cgtax=0;
						}
						//$totalCgst+=$cgst;
						//$totalSgst+=$sgst;
						$totalIgst+=$igst;
						$totalNet+=$salesInvoicedata->net_amount;
						$totalTaxablevalue+=$salesInvoicedata->taxable_value;
						$total_qty+=$salesInvoicedata->quantity;
						
						//$totalCgst_datewise+=$cgst;
						//$totalSgst_datewise+=$sgst;
						$totalIgst_datewise+=$igst;
						$totalNet_datewise+=$salesInvoicedata->net_amount;
						$totalTaxablevalue_datewise+=$salesInvoicedata->taxable_value;
						$total_qty_datewise+=$salesInvoicedata->quantity;
					?>
					<tr>
					<td><?=$customerCode?></td>
					<td><?=$customerName?></td>
					<td><?= $field.'/'.$financialyear.'/'. h(str_pad($data->voucher_no, 3, '0', STR_PAD_LEFT))?></td>
					<td><?=$data->transaction_date?></td>
					<td><?=$salesInvoicedata->item->hsn_code?></td>
					<td><?=$salesInvoicedata->item->item_code?></td>
					<td><?=$salesInvoicedata->item->name?></td>
					<td><?=@$salesInvoicedata->item->stock_group->parent_stock_group->name?></td>
					<td><?=@$salesInvoicedata->item->stock_group->name?></td>
					<td><?=@$salesInvoicedata->item->size->name ?></td>
					<td class="rightAligntextClass"><?=$salesInvoicedata->quantity?></td>
					<td class="rightAligntextClass"><?=$this->Money->moneyFormatIndia($salesInvoicedata->rate)?></td>
					<td class="rightAligntextClass">
					<?php if($salesInvoicedata->discount_percentage==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $salesInvoicedata->discount_percentage.'%';?><?php }?>
					</td>
					<td class="rightAligntextClass">
					<?php if($dis==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $dis;?><?php }?>
					</td>
					<td class="rightAligntextClass"><?=$this->Money->moneyFormatIndia($salesInvoicedata->taxable_value)?></td>
					
					<?php if($salesInvoicedata->gst_figure->tax_percentage==5){ ?>
						<td class="rightAligntextClass">
						<?php if($cgtax==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgtax.'%';?><?php }?></td> 
						<td class="rightAligntextClass">
						<?php if($cgst==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgst; $totalCgst5+=$cgst; $totalCgst_datewise5+=$cgst;?><?php }?>
						</td>
						<td class="rightAligntextClass">
						<?php if($cgtax==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgtax.'%';?><?php }?>
						</td>
						<td class="rightAligntextClass">
						<?php if($sgst==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $sgst; $totalSgst5+=$sgst; $totalSgst_datewise5+=$sgst;?><?php }?>
						</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
					<?php } else if($salesInvoicedata->gst_figure->tax_percentage==12){ ?>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td class="rightAligntextClass">
						<?php if($cgtax==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgtax.'%';?><?php }?></td>
						<td class="rightAligntextClass">
						<?php if($cgst==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgst; $totalCgst12+=$cgst; $totalCgst_datewise12+=$cgst;?><?php }?>
						</td>
						<td class="rightAligntextClass">
						<?php if($cgtax==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgtax.'%';?><?php }?>
						</td>
						<td class="rightAligntextClass">
						<?php if($sgst==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $sgst; $totalSgst12+=$sgst; $totalSgst_datewise12+=$sgst;?><?php }?>
						</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
					<?php } else if($salesInvoicedata->gst_figure->tax_percentage==18){ ?>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td class="rightAligntextClass">
						<?php if($cgtax==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgtax.'%';?><?php }?></td>
						<td class="rightAligntextClass">
						<?php if($cgst==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgst; $totalCgst18+=$cgst; $totalCgst_datewise18+=$cgst;?><?php }?>
						</td>
						<td class="rightAligntextClass">
						<?php if($cgtax==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgtax.'%';?><?php }?>
						</td>
						<td class="rightAligntextClass">
						<?php if($sgst==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $sgst; $totalSgst18+=$sgst; $totalSgst_datewise18+=$sgst;?><?php }?>
						</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
					<?php } else{ ?>
					<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td class="rightAligntextClass">
						<?php if($cgtax==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgtax.'%';?><?php }?></td>
						<td class="rightAligntextClass">
						<?php if($cgst==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgst; $totalCgst28+=$cgst; $totalCgst_datewise28+=$cgst;?><?php }?>
						</td>
						<td class="rightAligntextClass">
						<?php if($cgtax==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $cgtax.'%';?><?php }?>
						</td>
						<td class="rightAligntextClass">
						<?php if($sgst==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $sgst; $totalSgst28+=$sgst; $totalSgst_datewise28+=$sgst;?><?php }?>
						</td>
					<?php }  ?>
					
					
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
					<td class="rightAligntextClass"><?=$this->Money->moneyFormatIndia($salesInvoicedata->net_amount)?></td>
					<?php if($data->invoice_receipt_type=="cash"){ $totalCash+=$salesInvoicedata->net_amount; $totalCash1+=$salesInvoicedata->net_amount;?>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($salesInvoicedata->net_amount)?></b></td>
					<td class="rightAligntextClass"><b>-</b></td>
					<?php }else{ 
					$totalCash+=$data->receipt_amount;
					$totalCash1+=$data->receipt_amount;
					$totalCredit+=$salesInvoicedata->net_amount-$data->receipt_amount;
					$totalCredit1+=$salesInvoicedata->net_amount-$data->receipt_amount;
					?>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($data->receipt_amount)?></b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($salesInvoicedata->net_amount-$data->receipt_amount)?></b></td>
					<?php } ?>
					</tr>
					<?php }}?>
					
					
					<?php if ($total_qty_datewise > 0){?>
						<tr style="background-color:#BCC6CC"><td colspan="10" align="right"><b>Total</b></td>
						<td class="rightAligntextClass"><b>
						<?php echo $total_qty_datewise;?>
						</b></td><td></td><td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalDiscount_datewise==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $this->Money->moneyFormatIndia($totalDiscount_datewise);?><?php }?>
						</b></td>
						<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalTaxablevalue_datewise)?></b></td>
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalCgst_datewise5==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalCgst_datewise5;?><?php }?>
						</b></td>
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalSgst_datewise5==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalSgst_datewise5;?><?php }?>
						</b></td>
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalCgst_datewise12==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalCgst_datewise12;?><?php }?>
						</b></td>
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalSgst_datewise12==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalSgst_datewise12;?><?php }?>
						</b></td>
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalCgst_datewise18==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalCgst_datewise18;?><?php }?>
						</b></td>
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalSgst_datewise18==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalSgst_datewise18;?><?php }?>
						</b></td>
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalCgst_datewise28==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalCgst_datewise28;?><?php }?>
						</b></td>
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalSgst_datewise28==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalSgst_datewise28;?><?php }?>
						</b></td>
						
						<td></td>
						<td class="rightAligntextClass"><b>
						<?php if($totalIgst_datewise==0){?>
						<?php echo '';?> <?php }else{ ?>
						<?php echo $totalIgst;?><?php }?>
						</b></td>
						<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalNet_datewise)?></b></td>
						<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalCash)?></b></td>
						<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalCredit)?></b></td>
						
						</tr>
					<?php } ?>
					<?php } ?>
					
					<tr style="background-color:#E5E4E2;">
					<td colspan="10" align="right"><b>Total</b></td>
					<td class="rightAligntextClass"><b>
					<?php echo $total_qty;?>
					</b></td><td></td><td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalDiscount==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $this->Money->moneyFormatIndia($totalDiscount);?><?php }?>
					</b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalTaxablevalue)?></b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalCgst5==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalCgst5;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalSgst5==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalSgst5;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalCgst12==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalCgst12;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalSgst12==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalSgst12;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalCgst12==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalCgst18;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalSgst12==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalSgst18;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalCgst12==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalCgst28;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalSgst12==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalSgst28;?><?php }?>
					</b></td>
					<td></td>
					<td class="rightAligntextClass"><b>
					<?php if($totalIgst==0){?>
					<?php echo '';?> <?php }else{ ?>
					<?php echo $totalIgst;?><?php }?>
					</b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalNet)?></b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalCash1)?></b></td>
					<td class="rightAligntextClass"><b><?=$this->Money->moneyFormatIndia($totalCredit1)?></b></td>
					
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
