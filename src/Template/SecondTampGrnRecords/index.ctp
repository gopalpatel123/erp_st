<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Grn Records');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Second Temp GRN Records</span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
				<?php $page_no=$this->Paginator->current('SecondTampGrnRecords'); $page_no=($page_no-1)*20; ?>
				<table class="table table-condensed table-hover table-bordered">
					<thead>
						<tr>
							<th scope="col" class="actions"><?= __('Sr') ?></th>
							<th scope="col"><?= $this->Paginator->sort('item_code') ?></th>
							<th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
							<th scope="col"><?= $this->Paginator->sort('purchase_rate') ?></th>
							<th scope="col"><?= $this->Paginator->sort('sales_rate') ?></th>
							<th scope="col"><?= $this->Paginator->sort('is_addition_item_data_required') ?></th>
							<th scope="col"><?= $this->Paginator->sort('item_name') ?></th>
							<th scope="col"><?= $this->Paginator->sort('hsn_code') ?></th>
							<th scope="col"><?= $this->Paginator->sort('unit') ?></th>
							<th scope="col"><?= $this->Paginator->sort('gst_rate_fixed_or_fluid') ?></th>
							<th scope="col"><?= $this->Paginator->sort('first_gst_rate') ?></th>
							<th scope="col"><?= $this->Paginator->sort('amount_in_ref_of_gst_rate') ?></th>
							<th scope="col"><?= $this->Paginator->sort('second_gst_rate') ?></th>
							<th scope="col" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($secondTampGrnRecords as $secondTampGrnRecord): ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($secondTampGrnRecord->item_code) ?></td>
							<td class="rightAligntextClass"><?= $this->Number->format($secondTampGrnRecord->quantity) ?></td>
							<td class="rightAligntextClass"><?= $this->Number->format($secondTampGrnRecord->purchase_rate) ?></td>
							<td class="rightAligntextClass"><?= $this->Number->format($secondTampGrnRecord->sales_rate) ?></td>
							<td><?= h($secondTampGrnRecord->is_addition_item_data_required) ?></td>
							<td><?= h($secondTampGrnRecord->item_name) ?></td>
							<td><?= h($secondTampGrnRecord->hsn_code) ?></td>
							<td><?= h(@$secondTampGrnRecord->unit->name) ?></td>
							<td><?= h($secondTampGrnRecord->gst_rate_fixed_or_fluid) ?></td>
							<td class="rightAligntextClass"><?= h(@$secondTampGrnRecord->FirstGstFigures->tax_percentage) ?></td>
							<td class="rightAligntextClass"><?= h(@$secondTampGrnRecord->amount_in_ref_of_gst_rate) ?></td>
							<td class="rightAligntextClass"><?= h(@$secondTampGrnRecord->SecondGstFigures->tax_percentage) ?></td>
							<td class="actions">
								<?= $this->Html->link(__('Edit'), ['action' => 'edit', $secondTampGrnRecord->id]) ?>
								<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $secondTampGrnRecord->id], ['confirm' => __('Are you sure you want to delete # {0}?', $secondTampGrnRecord->id)]) ?>
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