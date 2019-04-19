<style>
  .strongClass{
	font: 12px arial, sans-serif
  }
  .rightAligntextClasses
  {
   text-align:right !important;
  }
  .rightFloatClasses
  {
   float:right !important;
  }
  .container {
    //width: 30em;
    overflow-x: auto;
    //white-space: nowrap;
}
</style>

<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Credit Note Bill');
?>

		<div class="portlet light table-responsive">
		<div class="portlet-title rightFloatClasses">
				<div class="caption">
                   <button onclick="myFunction()">Print</button>
				</div>
			</div>
<div style="width:1000px;" class="table-responsive">
<table style=" font-family:arial, sans-serif; font-size:10px; padding-left:2px;padding-right:2px;" width='100%' border='0'>
   <tr><td class="strongClass" height="40px" style="font-size:14px; text-align:center" colspan="4">  
		</td></tr></table>
		
		<?php foreach($invoiceBills->toArray() as $data){
		foreach($data->credit_note_rows as $credit_note_row){?>
			<?php }}?>
			<table style="text-align:right; padding-left:2px;padding-right:2px;" width='100%' border='0'>
		<tr><td colspan="4"><b><?=@$data->company->name?></b></td></tr>
		<tr><td colspan="4"><?=@$data->company->address?></td></tr>
		<tr><td colspan="4"><?=@$data->company->state->name?></td></tr>
		<tr><td><br/></td></tr>
		</table>
		<?php foreach($invoiceBills->toArray() as $data){
		foreach($data->credit_note_rows as $credit_note_row){?>
			<?php }}?>
			<table style="text-align:right; padding-left:2px;padding-right:2px;" width='100%' border='0'>
		<tr><td colspan="4"><strong>Tel: &nbsp;</strong>1111</td></tr>
		<tr><td colspan="4"><strong>GST No. &nbsp;</strong>5555</td></tr>
		<tr><td colspan="4"><strong>Invoice No. &nbsp;</strong><?=$data->voucher_no?></td></tr>
		<tr><td colspan="4"><strong>Cust No. &nbsp;</strong>hgfh454</td></tr>
		<tr><td colspan="4"><strong>Cust Name: &nbsp;</strong><?=$data->partyDetails->name?></td></tr>
        <tr><td colspan="4"><br/></td></tr>	       
		   </table>
		<table style="padding-left:2px;padding-right:2px;" width='100%' border='0'>
   <tr><td class="strongClass" style="font-size:14px; text-align:center" colspan="4">  <strong>CREDIT NOT INVOICE</strong>
		</td></tr></table>
		<br/><br/>
		<table style="" width='100%' border='1'>
		<tr class="rightAligntextClasses">
		<th style="text-align:left">Sr.</th>
		<th style="text-align:left">Item Code</th>
		<th style="text-align:left">HSN Code</th>
		<th>Qty</th>
		<th>UOM</th>
		<th>Rate</th>
		<th>Taxable Value</th>
		<th>%GST Rate</th>
		<th>Net</th>
		</tr>
		<?php
		foreach($invoiceBills->toArray() as $data){
		$cgst=0;
		$sgst=0;
		$igst=0;
		$totalAmount=0;
		$i=0;
		$net=$data->amount_before_tax+$data->total_cgst
		+$data->total_sgst+$data->total_igst;
		$roundnet=round($net);
		if($net>$roundnet)
		{
		$exct=number_format($roundnet-$net, 2);
		}
		else{
		$exct=number_format($roundnet-$net,2);
		}
		
		foreach($data->credit_note_rows as $credit_note_row){
		$i++;
		if(@$data->company->state_id==$data->partyDetails->state_id){
		$gstValue=$credit_note_row->gst_value;
		$gst=$gstValue/2;
		$cgst+=$gst;
		$sgst+=$gst;
		$totalAmount+=$credit_note_row->quantity*$credit_note_row->rate;
		}
		else{
		$gstValue=$credit_note_row->gst_value;
		$gst=$gstValue;
		$igst+=$gst;
		}
		?>
		<tr class="rightAligntextClasses">
		<td style="text-align:left"><?= $i ?></td>
		<td style="text-align:left"><?=$credit_note_row->item->item_code ?><?php echo ' -';?> <?=$credit_note_row->item->name ?></td>
		<td style="text-align:left"><?=$credit_note_row->item->hsn_code ?></td>
		<td><?=$credit_note_row->quantity ?></td>
		<td><?=@$credit_note_row->item->size->name ?></td>
		<td><?=$credit_note_row->rate ?></td>
		<td><?=$credit_note_row->taxable_value ?></td>
		<td><?=$credit_note_row->gst_figure->name ?></td>
		<td><?=$credit_note_row->net_amount ?></td>
		</tr>
		<?php }}
    
		?>
			</table>
			
		<table width="100%" border="0">
			<tr class="rightAligntextClasses">
		<th colspan="8" class="rightAligntextClasses">Amount Before Tax</th>
		<td><?=$data->amount_before_tax ?></td>
		</tr>
		<?php if($data->company->state_id==$data->partyDetails->state_id){?>
		<tr class="rightAligntextClasses">
		<th colspan="8" class="rightAligntextClasses">Add CGST</th>
		<td><?=$data->total_cgst ?></td>
		</tr>
		<tr class="rightAligntextClasses">
		<th colspan="8" class="rightAligntextClasses">Add SGST</th>
		<td><?=$data->total_sgst ?></td>
		</tr>
		<?php }else {?>
		<tr class="rightAligntextClasses">
		<th colspan="8" class="rightAligntextClasses">Add IGST</th>
		<td><?=$data->total_igst ?></td>
		</tr>
		<?php }?>
		<tr class="rightAligntextClasses">
		<th colspan="8" class="rightAligntextClasses">Round off</th>
		<td><?= $exct ?></td>
		</tr>
		<tr class="rightAligntextClasses">
		<th colspan="8" class="rightAligntextClasses">Amount After Tax</th>
		<td><?=$roundnet ?></td>
		</tr>
		</table>
		</div></div>
		<script>
function myFunction() {
    window.print();
}
</script>