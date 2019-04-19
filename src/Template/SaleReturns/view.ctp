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
	width:90%;
    size: auto;   /* auto is the initial value */
    margin: 0px 0px 0px 0px;  /* this affects the margin in the printer settings */
}
.maindiv {
border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width: 75%;font-size: 12px;
}
</style>


<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Sales Return View');
?>
<div  class="maindiv" style="border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width: 75%;font-size: 12px;">	
	<table width="100%" class="divHeader">
		<tbody><tr>
				<td width="30%"><?php if(!empty(@$saleReturn->company->logo)){ ?>
				<?php echo $this->Html->image('/img/'.$saleReturn->company->logo, ['height' => '70px', 'width' => '70px']); ?>
				<?php } ?></td>
				<td align="center" width="40%" style="font-size: 12px;"><div align="center" style="font-size: 18px;font-weight: bold;color: #0685a8;">SALES RETURN VIEW</div></td>
				<td align="right" width="40%" style="font-size: 12px;">
				<span style="font-size: 14px;font-weight: bold;"><?=@$saleReturn->company->name?></span><br/>
				<span><?=@$saleReturn->company->address?>, <?=@$saleReturn->company->state->name?></span></br>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>  <?=@$saleReturn->company->phone_no ?> |  Mobile : <?=@$saleReturn->company->mobile ?><br> GSTIN NO:
				<?=@$saleReturn->company->gstin ?></span></td>
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
                        <td>Sales Invoice No </td>
                        <td width="20" align="center">:</td>
                        <td><?php
								    $date = date('Y-m-d', strtotime($saleReturn->sales_invoice->transaction_date));
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
								<?= $acronym.'/'.$financialyear.'/'. h(str_pad($saleReturn->sales_invoice->voucher_no, 3, '0', STR_PAD_LEFT))?></td>
						</tr>
					<tr>
						<td>Voucher No</td>
						<td width="20" align="center">:</td>
						<td><?php echo '#'.str_pad($saleReturn->voucher_no, 4, '0', STR_PAD_LEFT);?>
					</tr>
				</tbody></table>
			</td>
			<td width="50%" valign="top" align="right">
				<table>
					<tbody><tr>
						<td>Transaction Date</td>
						<td width="20" align="center">:</td>
						<td><?php echo date("d-m-Y",strtotime($saleReturn->transaction_date)); ?></td>
					</tr>
					<tr>
						<td>Party</td>
						<td width="20" align="center">:</td>
						<td><?php echo $saleReturn->party_ledger->name; ?></td>
					</tr>
				</tbody></table>
			</td>
		</tr>
		</tbody></table>
		<br>
		<table width="100%" class="table  table-bordered" style="border:none;" border="0">
			<thead>
				<tr></tr>
				<tr align="center">
					<td><b>Sr</b></td>
					<td><b>Item</b></td>
					<td><b>Qty</b></td>
					<td><b>Rate</b></td>
					<td><b>Discount(%)</b></td>
					<td><b>Discount(Amt)</b></td>
					<td><b>Taxable Value</b></td>
					<td><b>GST</b></td>
					<td><b>Taxable Amount</b></td>
				</tr>
			</thead>
			<tbody id='main_tbody' class="tab">
			<?php $i=0;	 $total=0;		$total_discount=0;				
				foreach($saleReturn->sale_return_rows as $sale_return_row)
				{ 
					$discount_amt=round($sale_return_row->rate*$sale_return_row->discount_percentage/100,2);
					$total_discount+=$discount_amt;
				?>
					
				<tr class="main_tr" class="tab">
					<td width="5%" class="rightAligntextClass"><?php echo $i+1; ?></td>
					<td width="25%">
						<?php echo $sale_return_row->item->name; ?>
					</td>
					<td width="5%" class="rightAligntextClass">
						<?php echo $sale_return_row->return_quantity; ?>
					</td>
					<td width="15%" class="rightAligntextClass">
						<?php echo $sale_return_row->rate; ?>
					</td>
					<td width="5%" class="rightAligntextClass">
						<?php echo $sale_return_row->discount_percentage; ?>
					</td>
					<td width="10%" class="rightAligntextClass">
						<?php echo $discount_amt; ?>
					</td>
					<td width="15%" class="rightAligntextClass">
						<?php echo $sale_return_row->taxable_value; ?>
					</td>
					<td width="5%" class="rightAligntextClass">
						<?php echo $sale_return_row->gst_figure->name; ?>
					</td>
					<td width="20%" class="rightAligntextClass">
						<?php echo $sale_return_row->net_amount; 
						$total+=$sale_return_row->taxable_value;
						?>	
					</td>
				</tr>
			<?php $i++; } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="8" class="rightAligntextClass"><b>Amt Before Tax</b></td>
					<td width="25%" class="rightAligntextClass"><?php echo $saleReturn->amount_before_tax; ?></td>
				</tr>
				<tr>
					<td colspan="8" class="rightAligntextClass"><b>Discount Amount</b></td>
					<td width="25%" class="rightAligntextClass"><?php echo $total_discount; ?></td>
				</tr>
				<?php if(!empty($saleReturn->total_cgst)) {?>
				<tr>
					<td colspan="8" class="rightAligntextClass"><b>Total CGST</b></td>
					<td width="25%" class="rightAligntextClass"><?php echo $saleReturn->total_cgst; ?></td>
				</tr>
				<?php	}	?>
				<?php if(!empty($saleReturn->total_sgst)) {?>
				<tr>
					<td colspan="8" class="rightAligntextClass"><b>Total SGST</b></td>
					<td width="25%" class="rightAligntextClass"><?php echo $saleReturn->total_sgst; ?></td>
				</tr>
				<?php	}	?>
				<?php if(!empty($saleReturn->total_igst)){?>
				<tr>
					<td colspan="8" class="rightAligntextClass"><b>Total IGST</b></td>
					<td width="25%" class="rightAligntextClass"><?php echo $saleReturn->total_igst; ?></td>
				</tr>
				<?php	}	?>
				<?php if(!empty($saleReturn->round_off)){?>
				<tr>
					<td colspan="8" class="rightAligntextClass"><b>Round OFF</b></td>
					<td width="25%" class="rightAligntextClass"><?php echo $saleReturn->round_off; ?></td>
				</tr>
				<?php	}	?>
				<tr>
					<td colspan="8" class="rightAligntextClass"><b>Amt After Tax</b></td>
					<td width="25%" class="rightAligntextClass"><?php echo $saleReturn->amount_after_tax; ?></td>
				</tr>
			</tfoot>
		</table>
						
 </div>


