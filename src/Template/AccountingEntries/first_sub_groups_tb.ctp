<?php if($status=="Parent"){?>
<table class='table' style='border:1px; ' border="1px" >
<thead>
						<tr>
							<th scope="col" width="25%"></th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";></th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";></th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";></th>
						</tr>
						<tr>
							<th scope="col">Ledgers</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
						</tr>
					</thead>
	<?php foreach($ClosingBalanceForPrint as $key=>$ClosingBalance)
							{ //pr(@$OpeningBalanceForPrint[$key]['balance']);
								    $closing_credit=0;
									$closing_debit=0;
							?>
									<tr>
										<td><a href="#" role='button' child='yes' status='close' parent='no' class="group_name" group_id='<?php  echo $key; ?>' style='color:black;'>
														<?php echo $ClosingBalance['name']; ?>
															 </a>
													</td>
										<?php if(@$OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($OpeningBalanceForPrint[$key]['balance']));
										?>
										</td>
										<td scope="col" align="right"></td>
										<?php } else{ ?>
										<td scope="col" align="right"></td>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs(@$OpeningBalanceForPrint[$key]['balance']));
										?>
										</td>
										<?php }?>
										<td scope="col" align="right">
										<?php echo $this->Money->moneyFormatIndia(abs(@$TransactionsDr[$key]['balance'])); ?>
										</td>
										<td scope="col" align="right">
										<?php echo $this->Money->moneyFormatIndia(abs(@$TransactionsCr[$key]['balance'])); ?>
										</td>
										<?php if(@$ClosingBalance['balance'] > 0){ ?>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($ClosingBalance['balance']));
										?>
										</td>
										<td scope="col" align="right"></td>
										<?php } else{ ?>
										<td scope="col" align="right"></td>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($ClosingBalance['balance']));
										?>
										</td>
										<?php }?>
									</tr>
						<?php } ?>
</table>

<?php }else{?>
<table class='table' style='border:1px; ' border="1px" >
<thead>
						<tr>
							<th scope="col" width="25%"></th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";></th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";></th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";></th>
						</tr>
						<tr>
							<th scope="col">Ledgers</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
						</tr>
					</thead>
	<?php foreach($ClosingBalanceForPrint as $key=>$ClosingBalance)
							{ //pr(@$OpeningBalanceForPrint[$key]['balance']);
								    $closing_credit=0;
									$closing_debit=0;
							?>
									<tr>
										<td><?php echo $ledgerData[$key]; ?>
													</td>
										<?php if(@$OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($OpeningBalanceForPrint[$key]['balance']));
										?>
										</td>
										<td scope="col" align="right"></td>
										<?php } else{ ?>
										<td scope="col" align="right"></td>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs(@$OpeningBalanceForPrint[$key]['balance']));
										?>
										</td>
										<?php }?>
										<td scope="col" align="right">
										<?php echo $this->Money->moneyFormatIndia(abs(@$TransactionsDr[$key]['balance'])); ?>
										</td>
										<td scope="col" align="right">
										<?php echo $this->Money->moneyFormatIndia(abs(@$TransactionsCr[$key]['balance'])); ?>
										</td>
										<?php if(@$ClosingBalance['balance'] > 0){ ?>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($ClosingBalance['balance']));
										?>
										</td>
										<td scope="col" align="right"></td>
										<?php } else{ ?>
										<td scope="col" align="right"></td>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($ClosingBalance['balance']));
										?>
										</td>
										<?php }?>
									</tr>
						<?php } ?>
</table>
<?php }?>
