
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
<?php
 echo $this->Html->link('Close',['controller'=>'SalesInvoices','action'=>'dayBookFilter'],['escape'=>false,'class'=>'hidden-print','style'=>' background-color:blue;  font-size:18px; padding:5px; color:white; cursor:hand;  float: right']);
?>
<table  width="100%" border="0"  >
<tbody>

			<tr>
	<td colspan="3" align="center">
	<?php if(!empty(@$companies->logo)){ ?>
	<?php echo $this->Html->image('/img/'.$companies->logo, ['height' => '50px', 'width' => '50px']); ?>
	<?php } ?></td>
 	</tr>
	<tr>
		<td colspan="3"
		style="text-align:center;font-size:20px;"><b><span><?=@$companies->name?></span></b></td>
    </tr>
	
	
	<tr>
		<td colspan="3"
		style=" padding-bottom:10px;  padding-top:10px;"></td>
	</tr>
	<tr><td colspan="3" style="border-top:1px dashed;"></td></tr>
	<tr>
		<td><b>S.no</b></td>
		<td><b>Brand</b></td>
		<td align="center"><b>Amount</b></td>
	</tr>
	<tr><td colspan="3" style="border-top:1px dashed;"></td></tr>
	<?php $todayAmount=0; $i=1; $total=0; foreach($brandWise as $key=>$data) {?>
		<tr>
			<td><?php echo $i++; ?></td>
			<td><?php echo @$AllStockGroup[$key]; ?></td>
			<td align="center"><?php echo $data; $total+=$data; ?></td>
		</tr>
	<?php }?>
	<tr><td colspan="3" style="border-top:1px dashed;"></td></tr>
	<tr>
	<td><b></b></td>
	<td ><b>Total</b></td>
	<td align="center"><b><?php echo $total; ?></b></td>
	</tr>
	<tr><td colspan="3" style="border-top:1px dashed;"></td></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>
	</tbody></table>
	<table width="100%" border="" style="font-size:12px; border-collapse: collapse; margin-top:15px; border-style:dashed">
	<tr>
		<td align="center"><b>Total Amount</b></td>
		<td align="center"><b>Cash</b></td>
		<td align="center"><b>Credit</b></td>
	</tr>
	<tr>
		<td align="center"><?php echo $total; ?></td>
		<td align="center"><?php echo @$saleType['cash']; @$todayAmount+=@$saleType['cash'];?>Dr.</td>
		<td align="center"><?php echo @$saleType['credit']; @$todayAmount-=@$saleType['credit']; ?>Cr.</td>
	</tr>
	</table>
	<tr></tr>
	<tr></tr>
	
	<table width="100%" border="" style="font-size:12px; border-collapse: collapse; margin-top:15px; border-style:dashed">
		<tr>
			<td align="center"><b>Expense</b></td>
			<?php if($expenseData->totalDebit > $expenseData->totalCredit) { ?>
			<td align="center"><b><?php echo $expenseData->totalDebit-$expenseData->totalCredit;
				@$todayAmount+=@$expenseData->totalDebit-$expenseData->totalCredit;
			?>Dr</b></td>
			<?php } else { ?>
			<td align="center"><b><?php echo $expenseData->totalCredit-$expenseData->totalDebit; 
				@$todayAmount-=@$expenseData->totalCredit-$expenseData->totalDebit;
			?> Cr</b></td>
			<?php }  ?>
		</tr>
	</table>
	
	<table width="100%" border="" style="font-size:12px; border-collapse: collapse; margin-top:15px; border-style:dashed">
		<tr>
			<td align="center"><b>Closing Balance</b></td>
			<?php if($todayAmount > 0) { ?>
			<td align="center"><b><?php echo $todayAmount;?>Dr</b></td>
			<?php } else { ?>
			<td align="center"><b><?php echo abs($todayAmount);?> Cr</b></td>
			<?php }  ?>
		</tr>
	</table>
	
	<!--<table width="100%" border="" style="font-size:12px; border-collapse: collapse; margin-top:15px; border-style:dashed">
	<tr>
		<td align="center"><b>Expense Ledger</b></td>
		<td align="center"><b>Credit</b></td>
		<td align="center"><b>Debit</b></td>
	</tr>
	<?php foreach($expenseData as $data){ ?>
	<tr>
		<td align="center"><?php echo $data->ledger->name; ?></td>
		<td align="center"><?php echo @$data->totalCredit; ?></td>
		<td align="center"><?php echo @$data->totalDebit; ?></td>
	</tr>
	<?php } ?>
	</table> -->
			



</div>

