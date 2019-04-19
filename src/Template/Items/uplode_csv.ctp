<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', '');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Uplode Item</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($uplode_csv,['enctype'=>'multipart/form-data']) ?>
				<div class="row">
				    <div class="col-md-6">
					    <div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>uplode csv </label>
									<?php echo $this->Form->input('csv', ['type'=>'file','class' => 'form-control', 'label' => false, 'placeholder' => 'csv upload',]); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success']) ?>
				<?= $this->Form->end() ?>
			</div>
		</div>
	</div>
</div>
