
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
	font-family: Calibri !important;
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
$this->set('title', 'Challan Bill');
?>
<div style="width:300px;font-family: Calibri !important;" class="maindiv">
<?php echo $this->Html->link('Print',array(),['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: left','onclick'=>'javascript:NewPrint(2);']);
 echo $this->Html->link('Close',['controller'=>'Challans','action'=>'add'],['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: right']);
?>
<table  width="100%" border="0"  >
<tbody>
<?php foreach($invoiceBills->toArray() as $data){
		foreach($data->challan_rows as $sales_invoice_row){?>
			<?php }}?>
			<tr>
	<td colspan="4" align="center">
	<?php if(!empty(@$data->company->logo)){ ?>
	<?php echo $this->Html->image('/img/'.$data->company->logo, ['height' => '50px', 'width' => '50px']); ?>
	<?php } ?>
 	</tr>
	<tr>
		<td colspan="4"
		style="text-align:center;font-size:20px;"><b><span><?=@$data->company->name?></span></b></td>
    </tr>
	<tr>
	<td colspan="4"
 		style="text-align:center;font-size:12px !important;"><span><?=@$data->company->address?>, <?=@$data->company->state->name?></span></td>
	</tr>
	<tr><td colspan="4"
 		style="text-align:center;font-size:12px !important;"><span>Ph : <?=@$data->company->phone_no ?> |  Mobile : <?=@$data->company->mobile ?><br> GSTIN NO:
		<?=@$data->company->gstin ?></span></td>
	</tr>
	<tr>
		<td colspan="4"
		style="text-align:center;font-size:16px; padding-bottom:3px;  padding-top:10px;"><b><span><u>GST INVOICE</u></span></b></td>
	</tr>
	
	<?php if($data->invoice_receipt_type=="credit"){ ?>
		<tr>
			<td colspan="4"
		style="text-align:center;font-size:14px; padding-bottom:10px;  padding-top:-15px;"><b><span>CREDIT</span></b></td>
		</tr>
	<?php } ?>
	
	<tr>
		<td colspan="4" style="font-size:14px;">
		<b>Invoice Date:</b> <?= h(@$data->transaction_date)?>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;"><b>Customer Name: 
		<?php if(!empty($partyCustomerid)){?>
		<?= h(str_pad(@$data->partyDetails->customer_id, 4, '0', STR_PAD_LEFT))?>
		<?php }?>
		<?=ucwords($data->partyDetails->name)?></b></td>
	</tr>
	<?php if(!empty($partyCustomerid)){?>
	<tr>
		<td colspan="4" style="font-size:14px;">Mobile No: 
		<?=$data->partyDetails->mobile?></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">GSTIN No: 
		<?=$data->partyDetails->gstin?></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">City: 
		<?=@$data->partyDetails->city->name?></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">State: 
		<?=$data->partyDetails->state->name?></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="4" style="font-size:14px;">Invoice No.: 
		<?php
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
								$words = explode(" ", $coreVariable['company_name']);
								$acronym = "";
								foreach ($words as $w) {
								$acronym .= $w[0];
								}
								?>
								<?= $acronym.'/'.$financialyear.'/'. h(str_pad($data->voucher_no, 3, '0', STR_PAD_LEFT))?>
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
		<td align="right" style="font-style:italic;font-size:12px;">Taxable Value</td>
		<td align="center" style="font-style:italic;font-size:12px;">%SGST</td>
		<td align="center" style="font-style:italic;font-size:12px;"> %CGST</td>
		<td></td>
	</tr>
	<?php } else { ?> 
	<tr>
		<td align="right" style="font-style:italic;font-size:12px;">Taxable Value</td>
		<td style="font-style:italic;font-size:12px;">%IGST</td> 
		<td style="font-style:italic;font-size:12px;"></td><?php } ?>
	</tr>
	
	<?php
		$unit_ids=[]; $k=0;
		foreach($invoiceBills->toArray() as $data){
		$cgst=0;
		$sgst=0;
		$igst=0;
		$totalAmount=0;
		$totDis=0;
		$totalQty=0;
		$unitQty=[];
		$unitName=[];
		$totalQty=0;
		foreach($data->challan_rows as $sales_invoice_row){ ?>
		<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
		<?php
			$totalQty+=$sales_invoice_row->quantity;
			if(@$data->company->state_id==$data->partyDetails->state_id){
				$gst_type=$sales_invoice_row->gst_figure->tax_percentage;
				$gst_perc=$gst_type/2;
				$gstValue=$sales_invoice_row->gst_value;
				$gst=$gstValue/2;
				$cgst+=$gst;
				$sgst+=$gst;
				$totalAmount+=$sales_invoice_row->quantity*$sales_invoice_row->rate;
			}
			else{
				$gst_type=$sales_invoice_row->gst_figure->name;
				$gstValue=$sales_invoice_row->gst_value;
				$gst=$gstValue;
				$igst+=$gst;
				
				$totalAmount+=$sales_invoice_row->quantity*$sales_invoice_row->rate;
			}
			if($sales_invoice_row->discount_percentage>0)
			{
				$DisVal=$sales_invoice_row->quantity*$sales_invoice_row->rate;
				$totDis+=$DisVal*$sales_invoice_row->discount_percentage/100;
			}
			
			if($sales_invoice_row->item->unit->special_treatment==0){
			@$unitQty[$sales_invoice_row->item->unit->id]+=$sales_invoice_row->quantity;
			@$unitName[$sales_invoice_row->item->unit->id]=$sales_invoice_row->item->unit->name;
			
			}else{
				$k++;
			@$unitQty[$sales_invoice_row->item->unit->id]=$k;
			@$unitName[$sales_invoice_row->item->unit->id]=$sales_invoice_row->item->unit->name;
			}
			
			
			
		?>
		<tr>
			<td style="font-size:12px;"><?=$sales_invoice_row->item->item_code.' ' ?><?=  $sales_invoice_row->item->name ?></td>
			<td style="font-size:14px;"><?php
			if(!empty($sales_invoice_row->item->size->name))
			{
				echo @$sales_invoice_row->item->size->name;
			}
			else{
				echo '-';
			}
			?></td>
			<td style="text-align:left;font-size:12px;"><?=$sales_invoice_row->quantity ?><?php echo ' ';?><?=$sales_invoice_row->item->unit->name ?></td>
			<td style="text-align:right;"><?=$sales_invoice_row->rate ?></td>
		</tr>
		<tr>
			<td style="font-style:italic;font-size:12px;"><?=$sales_invoice_row->item->hsn_code ?></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$sales_invoice_row->discount_percentage ?></td>
			<td></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$sales_invoice_row->net_amount ?></td>
		</tr>
		
		<?php if($data->company->state_id==$data->partyDetails->state_id){?>
		<tr>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$sales_invoice_row->taxable_value ?></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$gst_perc.' %' ?></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$gst_perc.' %'?></td><td></td>
		</tr>
		
		<?php }else {?>
		<tr>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$sales_invoice_row->taxable_value ?></td>
			<td style="font-style:italic;font-size:12px;text-align:right"><?=$gst_type ?></td>
			<td></td><td></td>
		</tr>
		<?php }?>
		<?php }}   ?>
		<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
		<?php foreach($unitName as $key=>$unit){ ?>
		<tr>
			<td style="font-size:14px;">Total quantity in <?php echo $unit; ?></td>
			<td></td>
			<td></td>
			<td style="text-align:right;font-size:14px;"><?php echo number_format($unitQty[$key],2);  ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Total MRP</td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo number_format($totalAmount,2);  ?></td>
		</tr>
		<?php if(!empty($data->discount_amount)) {?>
		<tr>
			<td>Discount </td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo $data->discount_amount;  ?></td>
		</tr>
		
		<?php } if(!empty($data->round_off)) {?>
		<tr>
		<td>Round off </td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo $data->round_off;  ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Net Total</td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><b><?php echo number_format($data->amount_after_tax, 2);  ?></b></td>
		</tr>
		<tr>
		<td colspan="4" style="font-size:14px;"><b>Amount In words: </b> <?php echo $this->NumberWords->convert_number_to_words($data->amount_after_tax); ?><?php echo ' only'; ?> </td>
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
	<?php foreach($sale_invoice_rows as $sale_invoice_row){
	if($taxable_type!= 'IGST') { ?>
	<tr>
		<td  style="text-align:right;"><?php echo number_format($sale_invoice_row->total_taxable_amount,2) ?></td>
		<td style="text-align:right;"> <?= h($sale_invoice_row->gst_figure->tax_percentage/2) .'%' ?></td>
		<td style="text-align:right;"><?php echo number_format($sale_invoice_row->total_gst_amount/2,2) ?></td>
		<td style="text-align:right;"><?= h($sale_invoice_row->gst_figure->tax_percentage/2) .'%' ?></td>
		<td style="text-align:right;"><?php echo number_format($sale_invoice_row->total_gst_amount/2,2) ?></td>
	</tr>
	<?php } else { ?>
	<tr>
		<td style="text-align:right;"><?php echo number_format($sale_invoice_row->total_taxable_amount,2) ?></td>
		<td style="text-align:right;"><?= h($sale_invoice_row->gst_figure->tax_percentage).'%' ?></td>
		<td style="text-align:right;"><?php echo number_format($sale_invoice_row->total_gst_amount,2) ?></td>
	</tr>
	<?php } } ?>
	
</tbody>
</table>

<table border="0"  style="font-size:14px; margin-top:15px;margin-left:40px; border-collapse: collapse;">
	<tr>
		<td><b>||   29.04.2019 को मतदान  अवश्य  करें    ||</b></td>
	</tr>
	
</table>
</div>

