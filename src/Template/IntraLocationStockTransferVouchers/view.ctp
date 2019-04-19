<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Inter Location stock Transfer Voucher View');
?>
<style>
table.fixed { table-layout:fixed; }
table.fixed td { overflow: hidden; }
</style>
<div class="row">
	<div class="col-md-10">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Inter Location stock Transfer Voucher View</span>
				</div>
			</div>
			<div class="portlet-body">
				<table width="100%" class="fixed">
				  <tr>
					<td ><b>Voucher No </b></td>
					<td width="1%">:</td>
					<td><?php echo str_pad($intraLocationStockTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT);?></td>
					<td ><b>Transaction Date</b></td>
					<td width="1%" >:</td>
					<td><?php echo date("d-m-Y",strtotime($intraLocationStockTransferVoucher->transaction_date)); ?></td>
				</tr>
				<tr>
					<td><b>Stock Transfer From</b></td>
					<td width="1%">:</td>
					<td><?php echo $intraLocationStockTransferVoucher->TransferFromLocations->name; ?></td>
					<td ><b>Stock Transfer To</b></td>
					<td width="1%">:</td>
					<td><?php echo $intraLocationStockTransferVoucher->TransferToLocations->name;?></td>
				  </tr>
                  
                </table><br>
       		    <table id="main_table" class="table table-condensed table-bordered" style="width:auto;" >
					<thead>
					<tr align="center">
						<td align="left"><b>S.no</b></td>
						<td align="left"><b>Item Name</b></td>
						<td align="left"><b>Quantity</b></td>
					</tr>
					</thead>
					<tbody id='main_tbody' class="tab">
					 <?php 
						$i=0;	
                        $totQty=0;						
						foreach($intraLocationStockTransferVoucher->intra_location_stock_transfer_voucher_rows as $intra_location_stock_transfer_voucher_row):
						
						$totQty+=$intra_location_stock_transfer_voucher_row->quantity;
						
					?>
						<tr class="main_tr" class="tab">
							<td width="10%">
								<?php echo $i+1; ?>
							</td>
							<td>
								<?php echo $intra_location_stock_transfer_voucher_row->item->name;
								?>
							</td>
							<td class="rightAligntextClass">
								<?php echo $intra_location_stock_transfer_voucher_row->quantity;?>	
							</td>
						</tr>
					<?php $i++; endforeach; ?>
					<tr><td colspan="2" align="right"><b>Total Quantity</b></td>
					<td align="right"><b><?php echo $totQty;?></b></td></tr>
					</tbody>
					
				</table>
				<table>
					<tr style="padding-top:5px;">
					<td width="8%" valign="top"><b>Narration </b></td>
					<td width="1%" valign="top">:</td>
					<td colspan="3" width="89%"><?php echo $intraLocationStockTransferVoucher->narration;?></td>
				    </tr>
				</table>
			</div>
		</div>
	</div>
</div>