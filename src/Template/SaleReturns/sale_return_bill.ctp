
<script type="text/javascript">
<!--
function NewPrint(Copies){
	
  var Count = 0;
  while (Count < Copies){
    window.print(0);
    Count++;
  }
}
//-->
</script>
<style>

@media print{
	.maindiv{
		width:300px !important;
	}	
	.hidden-print{
		display:none;
	}
}
p{
margin-bottom: 0;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    padding: 5px !important;
	font-family: Calibri  !important;
}
</style>

<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0px 0px 0px 0px;  /* this affects the margin in the printer settings */
}
</style>
<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Sales Return Bill');
?>
<div style="width:300px;font-family: Calibri  !important;" class="maindiv">
<?php echo $this->Html->link('Print',array(),['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: left','onclick'=>'javascript:NewPrint(2);']);
 echo $this->Html->link('Close',['controller'=>'SaleReturns','action'=>'index'],['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: right']);
?>
<table  width="100%" border="0"  >
<tbody>


			<tr>
	<td colspan="4" align="center">
	<?php if(!empty(@$saleReturn->company->logo)){ ?>
	<?php echo $this->Html->image('/img/'.$saleReturn->company->logo, ['height' => '50px', 'width' => '50px']); ?>
	<?php } ?>
 	</tr>
	<tr>
		<td colspan="4"
		style="text-align:center;font-size:20px;"><b><span><?=@$saleReturn->company->name?></span></b></td>
    </tr>
	<tr>
	<td colspan="4"
 		style="text-align:center;font-size:12px !important;"><span><?=@$saleReturn->company->address?>, <?=@$saleReturn->company->state->name?></span></td>
	</tr>
	<tr><td colspan="4"
 		style="text-align:center;font-size:12px !important;"><span>Ph : <?=@$saleReturn->company->phone_no ?> |  Mobile : <?=@$saleReturn->company->mobile ?><br> GSTIN NO:
		<?=@$saleReturn->company->gstin ?></span></td>
	</tr>
	<tr>
		<td colspan="4"
		style="text-align:center;font-size:16px; padding-bottom:10px;  padding-top:10px;"><b><span><u>Sales Return Bill</u></span></b></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">
		<b>Invoice Date:</b> <?= h(@$saleReturn->transaction_date)?>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;"><b>Customer Name: 
		<?php if(!empty($partyCustomerid)){?>
		<?= h(str_pad(@$saleReturn->partyDetails->customer_id, 4, '0', STR_PAD_LEFT))?>
		<?php }?>
		<?=ucwords($saleReturn->partyDetails->name)?></b></td>
	</tr>
	<?php if(!empty($partyCustomerid)){?>
	<tr>
		<td colspan="4" style="font-size:14px;">Mobile No: 
		<?=$saleReturn->partyDetails->mobile?></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">GSTIN No: 
		<?=$saleReturn->partyDetails->gstin?></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">City: 
		<?=@$saleReturn->partyDetails->city->name?></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">State: 
		<?=$saleReturn->partyDetails->state->name?></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="4" style="font-size:14px;">Sales Invoice No.: 
		<?php
								    $date = date('Y-m-d', strtotime($saleReturn->transaction_date));
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
								$words = explode(" ", $coreVariable['company_name']);
								$acronym = "";
								foreach ($words as $w) {
								$acronym .= $w[0];
								}
								?>
								<?= $acronym.'/'.$financialyear.'/'. h(str_pad($saleReturn->sales_invoice->voucher_no, 3, '0', STR_PAD_LEFT))?>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">Voucher No.: 
		<?php echo '#'.str_pad($saleReturn->voucher_no, 4, '0', STR_PAD_LEFT);?></td>
	</tr>
	<tr>
		<td colspan="4"
		style=" padding-bottom:10px;  padding-top:10px;"></td>
	</tr>
	<tr>
		<td><b>Item Code</b></td>
		<td align="center"><b>Size</b></td>
		<td align="center"><b>Qty</b></td>
		<td align="center"><b>Rate</b></td>
	</tr>
	<tr>
		<td style="font-style:italic;font-size:12px;"><b>HSN Code</b></td>
		<td align="center" style="font-style:italic;font-size:12px;"><b>Dis %</b></td>
		<td><b></b></td>
		<td align="center" style="font-style:italic;font-size:12px;"><b>Net Amount</b></td>
	</tr>
	<?php if($taxable_type!= 'IGST') { ?>
	<tr>
		<td align="right" style="font-style:italic;font-size:12px;" style="font-style:italic;font-size:12px;">Taxable Value</td>
		<td align="center" style="font-style:italic;font-size:12px;"> %SGST </td>
		<td align="center" style="font-style:italic;font-size:12px;"> %CGST</td>
		<td></td>
	</tr>
	<?php } else { ?> 
	<tr>
		<td align="right" style="font-style:italic;font-size:12px;">Taxable Value</td>
		<td style="font-style:italic;font-size:12px;">%IGST</td> <td></td><?php } ?>
	</tr>
	<?php
		
		$k=0;
		$cgst=0;
		$sgst=0;
		$igst=0;
		$totalAmount=0;
		$total_discount=0;
		$totalQty=0;
		$unitQty=[];
		$unitName=[];
		foreach($saleReturn->sale_return_rows as $sale_return_row){ ?>
		
		<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
		<?php
			$totalQty+=$sale_return_row->return_quantity;
			$discount_amt=round(($sale_return_row->return_quantity*$sale_return_row->rate)*$sale_return_row->discount_percentage/100,2);
			$total_discount+=$discount_amt;
			if(@$saleReturn->company->state_id==$saleReturn->partyDetails->state_id){
				
			$gst_type=$sale_return_row->gst_figure->tax_percentage;
			$gst_perc=$gst_type/2;
			$gstValue=$sale_return_row->gst_value;
			$gst=$gstValue/2;
			$cgst+=$gst;
			$sgst+=$gst;
			$totalAmount+=$sale_return_row->return_quantity*$sale_return_row->rate;
			}
			else{
			$gst_type=$sale_return_row->gst_figure->name;
			$gstValue=$sale_return_row->gst_value;
			$gst=$gstValue;
			$igst+=$gst;
			$totalAmount+=$sale_return_row->return_quantity*$sale_return_row->rate;
			}
			if($sale_return_row->item->unit->special_treatment==0){
			@$unitQty[$sale_return_row->item->unit->id]+=$sale_return_row->return_quantity;
			@$unitName[$sale_return_row->item->unit->id]=$sale_return_row->item->unit->name;
			
			}else{
				$k++;
			@$unitQty[$sale_return_row->item->unit->id]=$k;
			@$unitName[$sale_return_row->item->unit->id]=$sale_return_row->item->unit->name;
			}
		
		?>
		<tr>
			<td style="font-size:12px;"><?=$sale_return_row->item->item_code.' ' ?><?=  $sale_return_row->item->name ?></td>
			<td style="font-size:14px;"><?php
			if(!empty($sale_return_row->item->size->name))
			{
			echo @$sale_return_row->item->size->name;
			}
			else{
			echo '-';
			}
			?></td>
			<td style="text-align:right;font-size:12px;"><?=$sale_return_row->return_quantity ?><?php echo ' ';?><?=$sale_return_row->item->unit->name ?></td>
			<td style="text-align:right;"><?=$sale_return_row->rate ?></td>
		</tr>
		<tr>
			<td style="font-style:italic;font-size:12px;"><?=$sale_return_row->item->hsn_code ?></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$sale_return_row->discount_percentage ?></td>
			<td></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$sale_return_row->net_amount ?></td>
		</tr>
		
		<?php if($saleReturn->company->state_id==$saleReturn->partyDetails->state_id){?>
		<tr>
			
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$sale_return_row->taxable_value ?></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$gst_perc.' %' ?></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$gst_perc.' %'?></td>
			<td></td><td></td>
		</tr>
		
		<?php }else {?>
		<tr>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$sale_return_row->taxable_value ?></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$gst_type ?></td>
			<td></td><td></td>
		</tr>
		<?php }?>
		<?php } ?>
		<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
		<?php foreach($unitName as $key=>$unit){ ?>
		<tr>
			<td style="font-size:14px;">Total quantity in <?php echo $unit; ?></td>
			<td></td>
			<td></td>
			<td style="font-size:14px;text-align:right;"><?php echo number_format($unitQty[$key],2);  ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Total MRP</td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo number_format($totalAmount,2);  ?></td>
		</tr>
		<?php if(!empty($total_discount)){ ?>
		<tr>
			<td>Discount </td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo $total_discount;  ?></td>
		</tr>
		<?php }  if(!empty($saleReturn->round_off)) {?>
		<tr>
		<td>Round off </td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo $saleReturn->round_off;  ?></td>
		</tr>
		<?php } ?> 
		<tr>
			<td>Net Total</td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><b><?php echo number_format($saleReturn->amount_after_tax, 2);  ?></b></td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:14px;"><b>Amount in words: </b> <?php echo $this->NumberWords->convert_number_to_words($saleReturn->amount_after_tax); ?><?php echo ' only'; ?>  </td>
		</tr>			
</tbody></table>
<table width="100%" border="" style="font-size:12px; border-collapse: collapse; margin-top:15px; border-style:dashed">
<thead>
	<?php if($taxable_type!= 'IGST') { ?>
	<tr>
		<td align="center">Taxable Value</td>
		<td align="center">CGST (%)</td>
		<td align="center">CGST Amount</td>
		<td align="center">SGST (%)</td>
		<td align="center">SGST Amount</td>
	</tr>
</thead>
<tbody>
	<?php } else { ?>
	<tr>
		<td align="center">Taxable Value</td>
		<td align="center">IGST(%)</td>
		<td align="center">IGST Amount</td>
	</tr>
	<?php } ?>
	<?php foreach($sale_return_rows as $sale_return_row){
	if($taxable_type!= 'IGST') { ?>
	<tr>
		<td  style="text-align:right;"><?php echo number_format($sale_return_row->total_taxable_amount,2) ?></td>
		<td style="text-align:right;"> <?= h($sale_return_row->gst_figure->tax_percentage/2) .'%' ?></td>
		<td style="text-align:right;"><?php echo number_format($sale_return_row->total_gst_amount/2,2) ?></td>
		<td style="text-align:right;"><?= h($sale_return_row->gst_figure->tax_percentage/2) .'%' ?></td>
		<td style="text-align:right;"><?php echo number_format($sale_return_row->total_gst_amount/2,2) ?></td>
	</tr>
	<?php } else { ?>
	<tr>
		<td style="text-align:right;"><?php echo number_format($sale_return_row->total_taxable_amount,2) ?></td>
		<td style="text-align:right;"><?= h($sale_return_row->gst_figure->tax_percentage).'%' ?></td>
		<td style="text-align:right;"><?php echo number_format($sale_return_row->total_gst_amount,2) ?></td>
	</tr>
	<?php } } ?>
	
</tbody>
</table>
<table border="0"  style="font-size:12px; margin-top:15px; border-collapse: collapse;">
	<tr>
		<td><b>Terms & Condition:</b></td>
	</tr>
	<tr>
		<td>
			<ol>
				<li>Cash Memo must be produced for any complaint of exchange.</li>
				<li>All alteration undertaken at customers risk</li>
				<li>Any complaints regarding garments will be forwarded to manufacturer whose decision on subject will be final.</li>
				<li>Any manufacturing defect will be entertained with in 30 days.
				And subject to final decision of company.</li>
				<li>All disputes are subject to Udaipur jurisdiction only. E&OE. </li>
			</ol>
		</td>
	</tr>
</table>
</div>
