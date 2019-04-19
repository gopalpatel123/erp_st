<table class='table' style='border:1px; ' border="1px" >
	<?php if($status=="Parent"){
	  foreach($groupForPrint as $key=>$groupForPrintRow){  
		$t=$groupForPrintRow['balance']; 
		 ?>
		<?php  if($t!=0){ ?>
		<tr>
			<td>
				<a href="#" role='button' child='yes' status='close' parent='no' class="group_name" group_id='<?php  echo $key; ?>' style='color:black;'>
				<?php echo $groupForPrintRow['name']; ?>
					 </a>  
				
			</td>
			<td align="right">
				<?php  if(!empty($groupForPrintRow['balance'])){ 
						if($groupForPrintRow['balance'] > 0){
							echo round(abs($groupForPrintRow['balance']),2); echo " Dr.";
						}else{
							echo round(abs($groupForPrintRow['balance']),2); echo " Cr.";
						}
					
					//$LeftTotal+=abs($groupForPrintRow['balance']);
				} ?>
			</td>
		</tr>
		
	<?php } ?>
	<?php } ?>
	<?php }else{ 
		foreach($groupForPrint as $key=>$groupForPrintRow){  
		$t=$groupForPrintRow['balance']; 
		 ?>
		<?php  if($t!=0){ ?>
		<tr>
			<td>
				
				<?php echo $ledgerData[$key]; ?>
				 
				
			</td>
			<td align="right">
				<?php  if(!empty($groupForPrintRow['balance'])){ 
						if($groupForPrintRow['balance'] > 0){
							echo round(abs($groupForPrintRow['balance']),2); echo " Dr.";
						}else{
							echo round(abs($groupForPrintRow['balance']),2); echo " Cr.";
						}
					
					//$LeftTotal+=abs($groupForPrintRow['balance']);
				} ?>
			</td>
		</tr>
		
	<?php } ?>
	<?php } ?>
	<?php } ?>
</table>
