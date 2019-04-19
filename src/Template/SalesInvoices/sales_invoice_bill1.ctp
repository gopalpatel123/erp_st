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
	font-family:Lato !important;
}
</style>

<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0px 0px 0px 0px;  /* this affects the margin in the printer settings */
}
</style>

<div style="width:300px;" class="maindiv">
<?php echo $this->Html->link('Print',array(),['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: left','onclick'=>'javascript:window.print()();']);
 echo $this->Html->link('Close',['controller'=>'SalesInvoices','action'=>'add'],['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: right']);
?>
<table  width="100%" border="0" style="margin-top:15px;" >
<tbody>
<?php foreach($invoiceBills->toArray() as $data){
		foreach($data->sales_invoice_rows as $sales_invoice_row){?>
			<?php }}?>
			<tr>
	<td colspan="4" align="left">
	<?php echo $this->Html->image('/img/dangilogo.png', ['height' => '50px', 'width' => '50px']); ?>
 	</tr>
	<tr>
		<td colspan="4"
		style="text-align:center;font-size:18px;"><b><span><?=@$data->company->name?></span></b></td>
    </tr>
	<tr>
	<td colspan="4"
 		style="text-align:center;font-size:12px !important;"><span><?=@$data->company->address?>, <?=@$data->company->state->name?></span></td>
	</tr>
	<tr><td colspan="4"
 		style="text-align:center;font-size:12px !important;"><span>Ph - <?=@$data->company->phone_no ?> Mobile - <?=@$data->company->mobile ?><br> GSTIN NO:
		<?=@$data->company->gstin ?></span></td>
	</tr>
	<tr>
		<td colspan="4"
		style="text-align:center;font-size:16px; padding-bottom:10px;  padding-top:10px;"><b><span><u>GST INVOICE</u></span></b></td>
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
		<?=$data->partyDetails->city->name?></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">State: 
		<?=$data->partyDetails->state->name?></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="4" style="font-size:14px;">Invoice No.: <?= h('#'.str_pad($data->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
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
		<td style="font-style:italic;font-size:14px;"><b>HSN Code</b></td>
		<td align="center" style="font-style:italic;font-size:14px;"><b>Dis %</b></td>
		<td><b></b></td>
		<td align="center" style="font-style:italic;font-size:14px;"><b>Net Amount</b></td>
	</tr>
	<?php if($taxable_type!= 'IGST') { ?>
	<tr>
		<td></td>
		<td align="center" style="font-style:italic;font-size:14px;">Taxable Value</td>
		<td align="center" style="font-style:italic;font-size:14px;"> %SGST </br> %CGST</td>
		<td></td>
	</tr>
	<?php } else { ?> 
	<tr>
		<td></td>
		<td style="font-style:italic;font-size:14px;">Taxable Value</td>
		<td style="font-style:italic;font-size:14px;">%IGST</td> <?php } ?>
	</tr>
	<?php
		
		foreach($invoiceBills->toArray() as $data){
		$cgst=0;
		$sgst=0;
		$igst=0;
		$totalAmount=0;
		
		foreach($data->sales_invoice_rows as $sales_invoice_row){ ?>
		
		<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
		<?php
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
		?>
		<tr>
			<td><?=$sales_invoice_row->item->item_code.' ' ?><?=  $sales_invoice_row->item->name ?></td>
			<td><?php
			if(!empty($sales_invoice_row->item->size->name))
			{
			echo @$sales_invoice_row->item->size->name;
			}
			else{
			echo '-';
			}
			?></td>
			<td style="text-align:right;"><?=$sales_invoice_row->quantity ?></td>
			<td style="text-align:right;"><?=$sales_invoice_row->rate ?></td>
		</tr>
		<tr>
			<td style="font-style:italic;font-size:14px;"><?=$sales_invoice_row->item->hsn_code ?></td>
			<td style="font-style:italic;font-size:14px;text-align:right"><?=$sales_invoice_row->discount_percentage ?></td>
			<td></td>
			<td style="font-style:italic;font-size:14px;text-align:right"><?=$sales_invoice_row->net_amount ?></td>
		</tr>
		
		<?php if($data->company->state_id==$data->partyDetails->state_id){?>
		<tr>
			<td></td>
			<td style="font-style:italic;font-size:14px;text-align:right"><?=$sales_invoice_row->taxable_value ?></td>
			<td style="font-style:italic;font-size:14px;text-align:right"><?=$gst_perc.' %' ?><br/><?=$gst_perc.' %'?></td>
			<td></td>
		</tr>
		
		<?php }else {?>
		<tr>
			<td></td>
			<td style="font-style:italic;font-size:14px;text-align:right"><?=$sales_invoice_row->taxable_value ?></td>
			<td style="font-style:italic;font-size:14px;text-align:right"><?=$gst_type ?></td>
			<td></td>
		</tr>
		<?php }?>
		<?php }} ?>
		<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
		
		<tr>
			<td>Total MRP</td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo number_format($totalAmount,2);  ?></td>
			</tr>
		<tr>
			<td>Discount </td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo number_format($totalAmount-$data->amount_after_tax, 2);  ?></td>
		</tr>
		<tr>
			<td>Net Total</td>
			<td></td>
			<td></td>
			<td style="text-align:right;"><?php echo number_format($data->amount_after_tax, 2);  ?></td>
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
