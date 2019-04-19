<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Import');
?>
<?php if($countSecondTampGrnRecords>0){ ?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Instructions Steps </span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($grn) ?>
				<div class="row">
					<div class="col-md-6 col-sm-6">
						<div class="portlet-body">
							<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><div class="scroller" style="height: 200px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
							<b></b>
								<ol>
									<li>
										<span>
											Process Data Records . 
											<?php echo $this->Html->link(' Check from here', '/SecondTampGrnRecords',['escape' => false]); ?>
										</span>
									</li></br>
									<li>
										<?php echo $notvalid_to_importRecords; ?><span> Records are invalid to import. 
											<?php echo $this->Html->link(' Check from here to Fixed', '/SecondTampGrnRecords/index/invalid',['escape' => false]); ?>
										</span>
									</li><br/>
									<li>
										<?php if($notvalid_to_importRecords>0) { ?>
										<span>First fix the invalid records then import command will be appear.<span><br/><?php } else { ?>Your Record is ready to Final Import. <b><?php echo $this->Html->link('Click here to Import', '/SecondTampGrnRecords/finalImport',['escape' => false,'style'=>'color:green']); ?></b> <?php } ?>
										</li></br><li><span>Delete existing data and start again Step 2. <?php echo $this->Html->link('Delete & Start', '/SecondTampGrnRecords/deleteSecondTempRecords',['escape' => false]); ?> <span>
									</li>
										
								</ol>
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

<?php goto Bottom; } ?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Uplode CSV File</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($grn,['enctype'=>'multipart/form-data','onsubmit'=>'return checkValidation()']) ?>
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
				<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit']) ?>
				<?= $this->Form->end() ?>
			</div>
		</div>
	</div>
</div>
<?php Bottom: ?>
<?php
	$js="
	function checkValidation()
	{
	        $('.submit').attr('disabled','disabled');
	        $('.submit').text('Submiting...');
    }
	
";
echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>