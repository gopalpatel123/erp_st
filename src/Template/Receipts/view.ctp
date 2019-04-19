<style>

@media print{
	.maindiv{
		width:100% !important;
		margin:auto;
	}	
	.hidden-print{
		display:none;
	}
}

</style>
<style type="text/css" media="print">
@page {
	width:100%;
    size: auto;   /* auto is the initial value */
    margin: 0px 0px 0px 0px;  /* this affects the margin in the printer settings */
}
.maindiv {
border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width: 100%;font-size: 12px;
}
</style>


<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Receipt Voucher');
?>
<div  class="maindiv" style="border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width:75%;font-size: 12px;">	
	<table width="100%" class="divHeader">
		<tbody><?php foreach($receipts as $data){?><tr>
				<td width="30%"><?php if(!empty(@$data->company->logo)){ ?>
				<?php echo $this->Html->image('/img/'.$data->company->logo, ['height' => '70px', 'width' => '70px']); ?>
				<?php } ?></td>
				<td align="center" width="40%" style="font-size: 12px;"><div align="center" style="font-size: 18px;font-weight: bold;color: #0685a8;">RECEIPT VOUCHER</div></td>
				<td align="right" width="40%" style="font-size: 12px;">
				<span style="font-size: 14px;font-weight: bold;"><?=@$data->company->name?></span><br/>
				<span><?=@$data->company->address?>, <?=@$data->company->state->name?></span></br>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>  <?=@$data->company->phone_no ?> |  Mobile : <?=@$data->company->mobile ?><br> GSTIN NO:
				<?=@$data->company->gstin ?></span></td>
			</tr><?php }?>
			<tr>
				<td colspan="3">
				<div style="border:solid 2px #0685a8;margin-bottom:5px;margin-top: 5px;"></div>
				</td>
			</tr>
		</tbody>
	</table>
		<table width="100%">	<thead>
		<?php foreach($receipts as $data){?>
		<tr>
			<td width="50%" valign="top" align="left">
				<table>
					<tr>
						<td>Voucher No</td>
						<td width="20" align="center">:</td>
						<td><?= h(str_pad($data->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
					</tr>
				</table>
			</td>
			<td width="50%" valign="top" align="right">
				<table>
					<tr>
						<td>Transaction Date</td>
						<td width="20" align="center">:</td>
						<td><?= h(date("d-m-Y",strtotime($data->transaction_date))) ?></td>
					</tr>
				</table>
			</td>
		</tr><?php }?>
	</table>
	<?php foreach($receipts as $data){?>
		Narration: <?php echo $data->narration;?><?php }?>
		<br/><br/>
		<table width="100%" class="table" style="font-size:12px">
			<tr style="background-color:#F0EFED;">
				<th colspan="3"><?= __('Ledger A/C') ?></th>
				<th><?= __('Dr') ?></th>
				<th><?= __('Cr') ?></th>
			</tr>
			<?php foreach($receipts->toArray() as $data){
					
					foreach($data->receipt_rows as $receiptRows)
					{
					@$total_debit+=$receiptRows->debit;
					@$total_credit+=$receiptRows->credit; ?>
					<tr>
					<td colspan="3" style="text-align:left"><b><?=$receiptRows->ledger->name?></b>
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
					<td ><?=$receiptRows->debit?></td>
					<td><?=$receiptRows->credit?></td>
					</tr>
			<?php } } ?>
			<tr>
			
			<td colspan="3" align="right"></td>
			
			<td> <?php echo $total_debit;?></td>
			<td> <?php echo $total_credit;?></td>
			</tr>
		</table>
	</div>
