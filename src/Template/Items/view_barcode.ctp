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
<body style="margin: 0px 0px 0px 20px;padding: 0;">

	
		<?php 
		$r=0; $inc=0;
		foreach($item_barcodes as $arData){
			if($inc==0){ echo '<table style="width:100%;" class="print">'; }
			if($r==0){ echo '<tr>'; }
			?>
			<td width="25%" height="106px" style="font-size:10px;" valign="middle">
				<table width="100%" style="font-size:10px;line-height: 7px;">
					<tr>
						<td colspan="2"><?php echo $coreVariable['company_name']; ?></td>
					</tr>
					<tr>
						<td colspan="2">Item : <?= $arData->name ?></td>
					</tr>
					<tr>
						<td>HSN Code : <?= $arData->hsn_code.' ' ?></td>
						<?php if($company_id==1){ ?>
						<td>WSP Rs : <?=$arData->sales_rate ?></td>
						<?php }else{ ?>
						<td>Rs : <?=$arData->sales_rate ?></td>
						<?php } ?>
						
						
					</tr>
					<tr>
						<?php if(!empty($arData->size->name)){?><td>Size : <?= @$arData->size->name.' ' ?></td><?php }?>
						<?php if(!empty($arData->shade->name)){?><td>Shade : <?= @$arData->shade->name.' ' ?></td><?php }?>
					</tr>
				</table>
				<div align="center" style="font-size:8px;"><?= $this->Html->Image('barcode/'.$arData->id.'.png',['width'=>'160px;','height'=>'13px','style'=>'width:160px;height:13px;']) ?><br/><?= $arData->item_code ?></div>
			</td>
			<?php
			
			if($r==4){ echo '</tr>'; }
			$r++;
			if($r==4){ $r=0; }
			$inc++;
			if($inc==40){ $inc=0; ?></table><?php }
			
			
		} ?>
	

</body>
</html>


