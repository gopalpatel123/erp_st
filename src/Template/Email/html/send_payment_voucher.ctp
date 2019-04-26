<table width="100%" style="font-family:Palatino Linotype;" >
		<tr>
			<td align="left" style="font-size: 28px;font-weight: bold;color: #0685a8;"><?php echo $company ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td width="50%" valign="top" align="left">
				<?php echo $member_name; ?>
			</td>
		</tr>
		
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Sub : Payment advice <?php echo "<br/>"; ?></td>
			
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Dear Sir,<?php echo "<br/>"; ?> </td> 
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>We have initiated payment to your account for your following invoices :-</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>
					<table border="1" width="70%">
						<tr>
								<th>S. No.</th>
								<th>Invoice No.</th>
								<th>Date</th>
								<th>Dr</th>
								<th>Cr</th>
								
						</tr>
						<?php $total_dr=0;  $total_cr=0;  $i=1; foreach($payment->reference_details as $reference_detail){  ?>
							<tr>
									<td align="center"><?php echo $i++; ?></td>
									<td width="25%" align="center"><?php echo $reference_detail->reference_no; ?></td>
									<td align="center"><?php echo date("d-m-Y",strtotime($transaction_date)); ?></td>
								<?php if($reference_detail->debit > 0){ $total_dr+=$reference_detail->debit; ?>
									<td align="center"><?= h($this->Number->format($reference_detail->debit,['places'=>2])) ?></td>
									<td align="center"></td>
								<?php } else { $total_cr+=$reference_detail->credit; ?>
									<td align="center"></td>
								<td align="center"><?= h($this->Number->format($reference_detail->credit,['places'=>2])) ?></td>
								<?php }  ?>
							</tr>
						<?php } ?>
							<tr >
									<td colspan="5" border="none" align="center">Total amount : Rs. <?php echo abs($total_dr-$total_cr); ?></td>
							</tr>
					</table>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>We request you to kindly acknowledge on receipt of the same.. <?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		
		<tr>
			<td>Regards, <?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Accounts Executive <?php echo "<br/>"; ?>
			</td>
		</tr>
		
</table>