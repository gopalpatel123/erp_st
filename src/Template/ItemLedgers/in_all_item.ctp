<?php

$this->set('title', 'Stock Report');
?>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="table-responsive">
				   <table class="table table-hover tabl_tc">
						<thead>
							<tr>
								<th> SNo</th>
								<th scope="col">Grn</th>
								<th scope="col"> Transaction Date </th>
								<th scope="col">Quantity</th>
								<th scope="col">rate</th>
							</tr>
						</thead>
						<tbody id="main_tbody" >
							<?php $i=1; foreach ($ItemLedgersData as $data): ?>
							<tr>
								<td><?php echo $i++; ?></td>
								
								<td><?= $this->Html->link(__(@$data->grn->voucher_no), ['controller'=>'Grns','action' => 'view', $data->grn->id]) ?></td>
								
								<td><?php echo $data->transaction_date; ?></td>
								<td><?php echo @$data->quantity ?></td>
								
								<td><?php echo $data->rate; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
