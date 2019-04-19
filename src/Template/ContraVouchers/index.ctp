<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Contra Voucher List');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Contra Voucher</span>
				</div>
				<div class="actions">
					<form method="GET" id="">
						<div class="row">
							<div class="col-md-9">
								<?php echo $this->Form->input('search',['class'=>'form-control input-sm pull-right','label'=>false, 'placeholder'=>'Search','autofocus'=>'autofocus','value'=> @$search]);
								?>
							</div>
							<div class="col-md-1">
								<button type="submit" class="go btn blue-madison input-sm">Go</button>
							</div> 
						</div>
					</form>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<?php $page_no=$this->Paginator->current('ContraVouchers');
					 $page_no=($page_no-1)*100; ?>
					<table class="table table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th scope="col"><?= __('Sr') ?></th>
								<th scope="col"><?= $this->Paginator->sort('voucher_no') ?></th>
								<th scope="col"><?= $this->Paginator->sort('party') ?></th>
								<th scope="col"><?= $this->Paginator->sort('transaction_date') ?></th>
								<th scope="col"><?= $this->Paginator->sort('reference_no') ?></th>
								<th scope="col"><?= $this->Paginator->sort('Amount') ?></th>
								<th scope="col" class="actions"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($contraVouchers as $contraVoucher):if($contraVoucher->status == 'cancel') { ?>
							 <tr style="background-color:#FE5E5E ;">
							<?php } else { ?>
							<tr> <?php } ?> 
									<td><?= h(++$page_no) ?></td>
									<td><?= h(str_pad($contraVoucher->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
									<td><?= h($contraVoucher->contra_voucher_rows[0]->ledger->name) ?></td>
									<td><?= h(date("d-m-Y",strtotime($contraVoucher->transaction_date))) ?></td>
									<td><?= h($contraVoucher->reference_no) ?></td>
									<td class=""><?= h($contraVoucher->contra_voucher_rows[0]->debit) ?></td>
									<td class="actions">
										<?= $this->Html->link(__('View'), ['action' => 'view', $contraVoucher->id]) ?>
										<?php if ($contraVoucher->status!='cancel'){?>
										<?php if (in_array("51", $userPages)){?>
										<?= $this->Html->link(__('Edit'), ['action' => 'edit', $contraVoucher->id]) ?>
										<?php }?>
										
										<?= $this->Form->postLink(__('Cancel'), ['action' => 'cancel', $contraVoucher->id], ['confirm' => __('Are you sure you want to cancel # {0}?',h(str_pad($contraVoucher->voucher_no, 3, '0', STR_PAD_LEFT)))]) ?>
										<?php }?>
										
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="paginator">
					<ul class="pagination">
						<?= $this->Paginator->first('<< ' . __('first')) ?>
						<?= $this->Paginator->prev('< ' . __('previous')) ?>
						<?= $this->Paginator->numbers() ?>
						<?= $this->Paginator->next(__('next') . ' >') ?>
						<?= $this->Paginator->last(__('last') . ' >>') ?>
					</ul>
					<p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
				</div>
				
			</div>
		</div>
	</div>
</div>