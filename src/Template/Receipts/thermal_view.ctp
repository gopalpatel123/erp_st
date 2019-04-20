
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
$this->set('title', 'Sales Invoice Bill');
?>
<div style="width:300px;font-family: Calibri !important;" class="maindiv">
<?php echo $this->Html->link('Print',array(),['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: left','onclick'=>'javascript:NewPrint(2);']);
 echo $this->Html->link('Close',['controller'=>'Receipts','action'=>'index'],['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: right']);
?>
<table  width="100%" border="0"  >
<tbody>

			<tr>
	<td colspan="4" align="center">
	<?php if(!empty(@$receipts->company->logo)){ ?>
	<?php echo $this->Html->image('/img/'.$receipts->company->logo, ['height' => '50px', 'width' => '50px']); ?>
	<?php } ?></td>
 	</tr>
	<tr>
		<td colspan="4"
		style="text-align:center;font-size:20px;"><b><span><?=@$receipts->company->name?></span></b></td>
    </tr>
	<tr>
	<td colspan="4"
 		style="text-align:center;font-size:12px !important;"><span><?=@$receipts->company->address?>, <?=@$receipts->company->state->name?></span></td>
	</tr>
	<tr><td colspan="4"
 		style="text-align:center;font-size:12px !important;"><span>Ph : <?=@$receipts->company->phone_no ?> |  Mobile : <?=@$receipts->company->mobile ?><br> GSTIN NO:
		<?=@$receipts->company->gstin ?></span></td>
	</tr>
	<tr>
		<td colspan="4"
		style="text-align:center;font-size:16px; padding-bottom:3px;  padding-top:10px;"><b><span><u>Receipt</u></span></b></td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">
		<b>Voucher No:</b> <?= h(str_pad($receipts->voucher_no, 4, '0', STR_PAD_LEFT)) ?>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">
		<b>Invoice Date:</b> <?= h(date('d-m-Y',strtotime(@$receipts->transaction_date)))?>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:14px;">
		<b>Narration:</b> <?= @$receipts->narration?>
		</td>
	</tr>
	<tr>
		<td colspan="4"
		style=" padding-bottom:10px;  padding-top:10px;"></td>
	</tr>
	<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
	<tr>
		<td><b>Ledger A/C</b></td>
		<td align="center"><b>Dr</b></td>
		<td align="center"><b>Cr</b></td>
	</tr>
	<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
	<?php  
			foreach($receipts->receipt_rows as $receiptRows){
					@$total_debit+=$receiptRows->debit;
					@$total_credit+=$receiptRows->credit; ?>
					<tr>
					<td style="text-align:left"><?=$receiptRows->ledger->name?>
						<div class="window" style="margin:auto;"><table width="50%">
							<?php foreach($receiptRows->reference_details as $refdata)
							{?><tr>
							<td style="text-align:left"><?=$refdata->type?></td>
							<td style="text-align:left"><?=$refdata->ref_name?></td>
							<?php if($refdata->debit){ ?>
							<td class="rightAligntextClass"><?=$refdata->debit?> Dr</td><?php } else {?>
							<td class="rightAligntextClass"><?=$refdata->credit?> Cr</td><?php } ?></tr>
							<?php } ?></table>
						</div>
					</td>
					<td  align="center"><?=$receiptRows->debit?></td>
					<td  align="center"><?=$receiptRows->credit?></td>
					</tr>
			<?php }  ?>
	<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
	<tr>
		<td><b>Total</b></td>
		<td  align="center"><?=$total_debit?></td>
		<td  align="center"><?=$total_credit?></td>
	</tr>
	<tr><td colspan="4" style="border-top:1px dashed;"></td></tr>
			
</tbody></table>
<table width="100%" border="" style="font-size:12px; border-collapse: collapse; margin-top:15px; border-style:dashed">
<thead>
	
</thead>
<tbody>
	
</tbody>
</table>

</div>

