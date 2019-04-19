<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Accounting Groups');
?>
<div class="row">
	<div class="col-md-8">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Accounting Groups</span>
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
				<?php  $page_no=$this->Paginator->current('Ledgersaccounting-groups'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th scope="col" class="actions"><?= __('Sr') ?></th>
							<th scope="col"><?= $this->Paginator->sort('nature of group') ?></th>
							<th scope="col"><?= $this->Paginator->sort('name') ?></th>
							<th scope="col"><?= $this->Paginator->sort('parent') ?></th>
							<th scope="col" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($accountingGroups as $accountingGroup): ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h(@$accountingGroup->nature_of_group->name)  ?></td>
							<td><?= h($accountingGroup->name) ?></td>
							<td><?= h(@$accountingGroup->parent_accounting_group->name)  ?></td>
							<td class="actions">
							<?php if (in_array("72", $userPages)){?>
							<?= $this->Html->link(__('Edit'), ['action' => 'edit', $accountingGroup->id]) ?>
							<?php }?></td>
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
