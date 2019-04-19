<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Import CSV');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Choose Steps</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($item) ?>
				<div class="row">
				
					<div class="col-md-6">
					    <a href="#" class="icon-btn">
						<i class="fa fa-group"></i>
						<div>
							 Users
						</div>
						<span class="badge badge-danger">
						2 </span>
						</a>
						
					</div>
					<div class="col-md-6">
						<a href="#" class="icon-btn">
						<i class="fa fa-barcode"></i>
						<div>
							 Products
						</div>
						<span class="badge badge-success">
						4 </span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
