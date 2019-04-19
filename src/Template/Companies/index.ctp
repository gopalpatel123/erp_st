<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Company');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Company</span>
				</div>
			</div>
			<div class="portlet-body">
				<?php $page_no=$this->Paginator->current('Customers'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th scope="col" class="actions"><?= __('Sr') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Name') ?></th>
							<th scope="col"><?= $this->Paginator->sort('State') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Financial Year Begins From') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Books Beginning From') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Phone') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Mobile') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Fax') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Email') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Gstin') ?></th>
							<th scope="col"><?= $this->Paginator->sort('Pan') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php  $i=1;foreach ($companies as $company): ?>
						<tr>
							<td><?= h($i) ?></td>
							<td><?= h($company->name) ?></td>
							<td><?= h($company->state->name) ?></td>
							<td><?= h(date("d-m-Y",strtotime($company->financial_year_begins_from))) ?></td>
							<td><?= h(date("d-m-Y",strtotime($company->books_beginning_from))) ?></td>
							<td><?= h($company->phone_no) ?></td>
							<td><?= h($company->mobile) ?></td>
							<td><?= h($company->fax_no) ?></td>
							<td><?= h($company->email) ?></td>
							<td><?= h($company->gstin) ?></td>
							<td><?= h($company->pan) ?></td>
						</tr>
						<?php $i++; endforeach; ?>
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


