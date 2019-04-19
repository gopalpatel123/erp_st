<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Edit Shade');
?>
<div class="row">
	<div class="col-md-6">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Edit Shade</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($shade) ?>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Shade Name <span class="required">*</span></label>
							<?php echo $this->Form->control('name',['class'=>'form-control input-sm','placeholder'=>'Shade Name',
							'label'=>false,'autofocus','required']); ?>
						</div>
						<div class="row"><span class="loading"></span>
							<div class="col-md-6">
								<div class="form-group">
									<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit']) ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?= $this->Form->end() ?>
			</div>
		</div>
	</div>
</div>