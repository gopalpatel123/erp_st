<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Stock Groups');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Stock Groups</span>
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
			<?php $page_no=$this->Paginator->current('StockGroups');
					 $page_no=($page_no-1)*100; 
									?>				
				<table class="table table-condensed table-hover table-bordered">
					<thead>
						<tr>
							<th scope="col"><?= $this->Paginator->sort('Sr') ?></th>
							<th scope="col"><?= $this->Paginator->sort('name') ?></th>
							<th scope="col"><?= $this->Paginator->sort('parent_id') ?></th>
							<th scope="col" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($stockGroups as $stockGroup): ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($stockGroup->name) ?></td>
							<td><?= $stockGroup->has('parent_stock_group') ? $this->Html->link($stockGroup->parent_stock_group->name, ['controller' => 'StockGroups', 'action' => 'view', $stockGroup->parent_stock_group->id]) : '' ?></td>
							<td class="actions">
							<?php if (in_array("57", $userPages)){?>
								<?= $this->Html->link(__('Edit'), ['action' => 'edit', $stockGroup->id]) ?>
								<?php }?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
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