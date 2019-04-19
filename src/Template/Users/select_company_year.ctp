<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */

$this->set('title', 'Login');
?>

	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<div class="row ">
		<div class="col-md-12">
			<div class="portlet-body">
					<h5>Select Financial Year</h5>
			<div class="table-scrollable">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Sr. No.</th>
						<th>Year</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					 <?php $i=0; foreach ($financialYears as $financialYear):  $i++; ?>
					<tr>
						<td><?= h($i) ?></td>
						<td><?= h(date("d-m-Y",strtotime($financialYear->fy_from)))?> - <?= h(date("d-m-Y",strtotime($financialYear->fy_to)))?></td>
						<td class="actions">
							<?= $this->Form->postLink(__('Select'),'/Users/selectCompanyYear/'.$financialYear->id) ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			</div>
			</div>
		</div>
		<!-- END FORM-->
	</div>
</div>
<!-- END LOGIN FORM -->