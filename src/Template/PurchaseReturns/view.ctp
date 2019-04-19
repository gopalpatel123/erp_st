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
$this->set('title', 'Purchase Return View');

?>
<div  class="maindiv" style="border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width:100%;font-size: 12px;">	
	<table width="100%" class="divHeader">
		<tbody><tr>
				<td width="30%"><?php if(!empty(@$purchaseReturn->company->logo)){ ?>
				<?php echo $this->Html->image('/img/'.$purchaseReturn->company->logo, ['height' => '70px', 'width' => '70px']); ?>
				<?php } ?></td>
				<td align="center" width="40%" style="font-size: 12px;"><div align="center" style="font-size: 18px;font-weight: bold;color: #0685a8;">PURCHASE RETURN VIEW</div></td>
				<td align="right" width="40%" style="font-size: 12px;">
				<span style="font-size: 14px;font-weight: bold;"><?=@$purchaseReturn->company->name?></span><br/>
				<span><?=@$purchaseReturn->company->address?>, <?=@$purchaseReturn->company->state->name?></span></br>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>  <?=@$purchaseReturn->company->phone_no ?> |  Mobile : <?=@$purchaseReturn->company->mobile ?><br> GSTIN NO:
				<?=@$purchaseReturn->company->gstin ?></span></td>
			</tr>
			<tr>
				<td colspan="3">
				<div style="border:solid 2px #0685a8;margin-bottom:5px;margin-top: 5px;"></div>
				</td>
			</tr>
		</tbody>
	</table>
		<table width="100%">
		<tbody>
			<tr>
				<td width="50%" valign="top" align="left">
				<table>
					 <tbody><tr>
                        <td>Purchase Invoice No</td>
                        <td width="20" align="center">:</td>
                        <td><?php echo '#'.str_pad($purchaseReturn->purchase_invoice->voucher_no, 4, '0', STR_PAD_LEFT);?></td>
						</tr>
					<tr>
						<td>Voucher No</td>
						<td width="20" align="center">:</td>
						<td><?php echo '#'.str_pad($purchaseReturn->voucher_no, 4, '0', STR_PAD_LEFT);?></td>
					</tr>
				</tbody></table>
			</td>
			<td width="50%" valign="top" align="right">
				<table>
					<tbody><tr>
						<td>Transaction Date</td>
						<td width="20" align="center">:</td>
						<td><?php echo date("d-m-Y",strtotime($purchaseReturn->transaction_date)); ?></td>
					</tr>
					<tr>
						<td>Supplier</td>
						<td width="20" align="center">:</td>
						<td><?php echo $purchaseReturn->purchase_invoice->supplier_ledger->name; ?></td>
					</tr>
					<tr>
						<td>GST No</td>
						<td width="20" align="center">:</td>
						<td><?php echo $purchaseReturn->purchase_invoice->supplier_ledger->supplier->gstin; ?></td>
					</tr>
				</tbody></table>
			</td>
			</tr>
			
		
		</tbody></table>
		<br>
		<table width="100%" class="table  table-bordered" style="border:none;" border="0">
			<thead>
				<tr></tr>
				<tr align="center" >
					<td rowspan="2"><b>Sr</b></td>
					<td rowspan="2"><b>Item</b></td>
					<td rowspan="2"><b>Qty</b></td>
					<td rowspan="2"><b>Rate</b></td>
					<td colspan="2"><b>Discount</b></td>
					<td colspan="2"><b>Pnf</b></td>
					<td rowspan="2"><b>Taxable Value</b></td>
					<td colspan="2"><b><?php if($supplier_state_id == $state_id){ echo 'GST'; } else{ echo 'IGST';} ?></b></td>
					<td rowspan="2">Round Off</b></th>
					<td rowspan="2"><b>Net Amount</b></td>
				</tr>
				<tr>
					<th><div align="center">%</div></th>
					<th><div align="center">Rs</div></th>
					<th><div align="center">%</div></th>
					<th><div align="center">Rs</div></th>
					<th><div align="center">%</div></th>
					<th><div align="center">Rs</div></th>
					
				</tr>
			</thead>
			
			<tbody id='main_tbody' class="tab">
				<?php if(!empty($purchaseReturn->purchase_return_rows))
				$i=1;
				$total_amt=0; 	$total_discount=0;		$total_pnf=0;	$total_round_off=0;
				$total_taxable_value=0; $total_gst=0;	$total_net_amount=0;$total_qty=0;
				 foreach($purchaseReturn->purchase_return_rows as $purchase_return_row)
				 { 
				?>
				<tr class="main_tr" class="tab" >
					<td width="3%" class="rightAligntextClass"><?php echo $i; ?></td>
					<td width="15%" align="left">
							<?php echo $purchase_return_row->item->name; ?>
					</td>
					<td width="5%" class="rightAligntextClass">
							<?php echo $purchase_return_row->quantity;
							$total_qty+=$purchase_return_row->quantity;
							?>
					</td>	
					<td width="5%" class="rightAligntextClass">
							<?php echo $purchase_return_row->rate;
							?>
					</td>	
					<td width="5%" class="rightAligntextClass">
							<?php if(!empty($purchase_return_row->discount_percentage)) { echo $purchase_return_row->discount_percentage; } else { echo ' '; }
							?>
					</td>	
					<td width="5%" class="rightAligntextClass">
					<?php  if(!empty($purchase_return_row->discount_amount)) { echo $purchase_return_row->discount_amount; } else { echo ' '; }
					$total_discount+=$purchase_return_row->discount_amount;?>
					</td>	
					<td width="5%" class="rightAligntextClass">
							<?php  if(!empty($purchase_return_row->pnf_percentage)) { echo $purchase_return_row->pnf_percentage; } else { echo ' '; }
							?>
					</td>	
					<td width="5%" class="rightAligntextClass">
							<?php  if(!empty($purchase_return_row->pnf_amount)) { echo $purchase_return_row->pnf_amount; } else { echo ' '; }
							$total_pnf+=$purchase_return_row->pnf_amount;
							?>
					</td>	
					
					<td width="5%" class="rightAligntextClass">
							<?php echo $purchase_return_row->taxable_value;
							$total_taxable_value+=$purchase_return_row->taxable_value;
							?>
					</td>

					<td width="5%" class="rightAligntextClass">
							<?php echo $purchase_return_row->item->FirstGstFigures->tax_percentage;
							?>
					</td>	
					<td width="5%" class="rightAligntextClass">
							<?php echo $purchase_return_row->gst_value;
							$total_gst+= $purchase_return_row->gst_value;
							?>
					</td>
					<td width="5%" class="rightAligntextClass">
							<?php  if($purchase_return_row->round_off>0) { echo $purchase_return_row->round_off;}
							$total_round_off+=$purchase_return_row->round_off;
							?>
					</td>	
					<td width="5%" class="rightAligntextClass" style="border-left-width:2px;">
							<?php echo $purchase_return_row->net_amount;
									$total_amt+=$purchase_return_row->net_amount;
							?>
					</td>
					
				</tr>
				<?php $i++; } ?> 
	
			</tbody>
			<tfoot>
				
				<tr>
					<td colspan="2" class="rightAligntextClass"><b>Total</b></td>
					<td colspan="1" class="rightAligntextClass"><b><?php  if($total_qty>0) {  echo $total_qty; } ?></b></td>
					<td colspan="1" class="rightAligntextClass"><b></b></td>
					<td colspan="2" class="rightAligntextClass"><b><?php  if($total_discount>0) {  echo $total_discount; } ?></b></td>
					<td colspan="2" class="rightAligntextClass"><b><?php if($total_pnf>0) { echo $total_pnf; }?></b></td>
					<td colspan="1" class="rightAligntextClass"><b><?php echo $total_taxable_value;  ?></b></td>
					<td colspan="2" class="rightAligntextClass"><b><?php  if($total_gst>0) {  echo $total_gst; } ?></b></td>
					<td colspan="1" class="rightAligntextClass"><b><?php  if($total_round_off>0) { echo $total_round_off; }?></b></td>
					<td colspan="1" class="rightAligntextClass"><b><?php echo $total_amt; ?></b></td>
						
				</tr>
			
			</tfoot>
		</table>
						
 </div>


