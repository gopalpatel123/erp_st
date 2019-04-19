<!DOCTYPE html>
<html>
<head>
	<title>Page Title</title>
	<style type="text/css" media="print">
	@page {
		size: auto;   /* auto is the initial value */
		margin: 0px 0px 0px 0px;  /* this affects the margin in the printer settings */
	}
	.print{
	page-break-after:always;
	}
	</style>
</head>
<body style="margin: 150px 0px 0px 20px;padding: 0;">

	
		<?php 
		$r=0; $inc=0;
		foreach($item_barcodes as $arData){ ?>
			<table style="width:100%; height:100%" class="print" >
			<tr>
			<td width="100%" height="100%" style="font-size:75px; text-align:center;" align="center" valign="middle">
				<table width="100%" style="font-size:108px;line-height: 121px;">
					<tr>
						<td colspan="2"><?php echo $coreVariable['company_name']; ?></td>
					</tr>
					<tr>
						<td colspan="2">Item : <?= $arData->name ?></td>
					</tr>
					
					<tr>
					    <td>Item Quantity : <?=$no_of_quantity ?></td>
					</tr>
					<tr>
					    <td>No Of Cartoon : <?=$no_of_cartoon ?></td>
					</tr>
					<tr>
					    <td>MRP : <?=$arData->sales_rate ?></td>
					</tr>
					
					<tr>
						<td>HSN Code : <?= $arData->hsn_code.' ' ?></td>
					</tr>
					
					
					<tr>
					<td>
					    
					        <?= $this->Html->Image('barcode/'.$arData->id.'.png',['style'=>'margin-left:36px;width:760px;height:170px;']) ?><br/>
							
					</td>	
					<tr>
						<td><?= $arData->item_code ?></td>
					</tr>
					
					
					</tr>
				</table>
				</td>
				</tr>
			</table>
			<?php
		
		} ?>
	

</body>
</html>


