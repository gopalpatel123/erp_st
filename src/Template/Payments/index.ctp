<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Payment Voucher List');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Payment Voucher</span>
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
					<?php $page_no=$this->Paginator->current('Payments');
					 $page_no=($page_no-1)*20; ?>
					<table class="table table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th scope="col"><?= __('Sr') ?></th>
								<th scope="col"><?= $this->Paginator->sort('voucher_no') ?></th>
								<th scope="col"><?= $this->Paginator->sort('Party') ?></th>
								<th scope="col"><?= $this->Paginator->sort('transaction_date') ?></th>
								<th scope="col"><?= $this->Paginator->sort('Amount') ?></th>
								<th scope="col" class="actions"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($payments as $payment): 
							if($payment->status == 'cancel') { ?>
							 <tr style="background-color:#FE5E5E ;">
							<?php } else { ?>
							<tr> <?php } ?>
									<td><?= h(++$page_no) ?></td>
									<td><?= h(str_pad($payment->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
									<td><?= h($payment->payment_rows[0]->ledger->name) ?></td>
									<td><?= h(date("d-m-Y",strtotime($payment->transaction_date))) ?></td>
									<td class=""><?= h($payment->payment_rows[0]->debit) ?></td>
									<td class="actions">
										<?= $this->Html->link(__('View'), ['action' => 'view', $payment->id]) ?>
										<?php if ($payment->status!='cancel'){?>
										<?php if (in_array("45", $userPages)){?>
										<?= $this->Html->link(__('Edit'), ['action' => 'edit', $payment->id]) ?>
										<?php }?>
										
									<?= $this->Form->postLink(__('Cancel'), ['action' => 'cancel', $payment->id], ['confirm' => __('Are you sure you want to cancel # {0}?',h(str_pad($payment->voucher_no, 3, '0', STR_PAD_LEFT)))]) ?>&nbsp;&nbsp;
									<?php }?>
										
										<!-- <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $payment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payment->id)]) ?> -->
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