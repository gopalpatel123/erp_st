<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Create Sales Invoice');
?>

<form method="GET" id="barcodeFrom"/>
	<div class="row">
	<div class="col-md-3"></div>
		<div class="col-md-3">
			<input type="text" name="grn_no" class="form-control input-sm" placeholder="GRN No" value="">
		</div>
		<div class="col-md-1" align="left">
			<button type="submit" class="go btn blue-madison input-sm">Go</button>
		</div> 
	</div>
</form>
<?php if(!empty($Grns) && $PurchaseInvoice=="Yes") {?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">GRN </span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<?php $page_no=$this->Paginator->current('salesInvoices'); $page_no=($page_no-1)*20; ?>
					<table class="table table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th scope="col"><?= __('Sr') ?></th>
								<th scope="col"><?= $this->Paginator->sort('voucher_no') ?></th>
								<th scope="col"><?= $this->Paginator->sort('transaction_date') ?></th>
								<th scope="col" class="actions"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?= h(++$page_no) ?></td>
								<td><?= h('#'.str_pad($Grns->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
								<td><?= h($Grns->transaction_date) ?></td>
								<td class="actions">
									<?php  if($Grns->status=="Pending"){ ?>
									<?= $this->Html->link(__('Create Purchase Invoice'), ['controller'=>'PurchaseInvoices','action' => 'add', $Grns->id]) ?>
									<?php }else{ ?>
										<?php  echo $Grns->status; ?>
									<?php } ?>
									
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			
			</div>
		</div>
	</div>
</div>
<?php } else if(empty($Grns) && $PurchaseInvoice=="Yes") {?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject bold ">Invoice Not Found</span>
				</div>
			</div>
		</div>
	</div>
</div>


<?php } ?>