<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Ledgers');
?>
<div class="row">
	<div class="col-md-8">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Ledgers</span>
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
				<?php $page_no=$this->Paginator->current('Ledgers'); $page_no=($page_no-1)*100; ?>
				<table class="table table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th scope="col" class="actions"><?= __('Sr') ?></th>
							<th scope="col"><?= $this->Paginator->sort('name') ?></th>
							<th scope="col"><?= $this->Paginator->sort('accounting_group') ?></th>
							<th scope="col" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($ledgers as $ledger): ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($ledger->name) ?></td>
							<td><?= h($ledger->accounting_group->name)  ?></td>
							<td class="actions">
								<?php if(!empty($ledger->customer_id))
								{ ?>
								<?php if (in_array("75", $userPages)){?>
									<?= $this->Html->link(__('Edit'), ['controller'=> 'customers' ,'action' => 'edit', $ledger->customer_id]) ?>
									<?php }?>
								<?php }
								else if(!empty($ledger->supplier_id)){ ?>
								
								<?php if (in_array("75", $userPages)){?>
								<?= $this->Html->link(__('Edit'), ['controller'=> 'suppliers' ,'action' => 'edit', $ledger->supplier_id]) ?>
								<?php }?>
								<?php } else { ?>
								<?= $this->Html->link(__('Edit'), ['action' => 'edit', $ledger->id]) ?><?php } ?>
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
