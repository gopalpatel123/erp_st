<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Shades');
?>
<div class="row">
	<div class="col-md-6">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Shades List</span>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-condensed table-hover table-bordered">
					<thead>
						<tr>
							<th scope="col"><?= $this->Paginator->sort('Sr') ?></th>
							<th scope="col"><?= $this->Paginator->sort('name') ?></th>
							<th scope="col" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0;
								foreach ($shades as $shade): 
								$i++;?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?= h($shade->name) ?></td>
							<td class="actions"><?php if (in_array("63", $userPages)){?>
								<?= $this->Html->link(__('Edit'), ['action' => 'edit', $shade->id]) ?>
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
