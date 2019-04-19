<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Edit Item');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Edit Second Temp Grn Records</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($secondTampGrnRecord) ?>
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Item Name <span class="required">*</span></label>
									<?php echo $this->Form->control('item_name',['class'=>'form-control input-sm','placeholder'=>'Item Name','label'=>false,'required'=>'required']); ?>
								</div>
							</div>
								
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>HSN Code<span class="required">*</span> </label>
									<?php echo $this->Form->control('hsn_code',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'HSN Code','required'=>'required']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Unit <span class="required">*</span></label>
									<?php echo $this->Form->control('unit_id',['class'=>'form-control input-sm select2me','label'=>false,'empty'=>'-Unit-', 'options' => $units,'required'=>'required']); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Shade </label>
									<?php echo $this->Form->control('shade_id',['class'=>'form-control input-sm select2me','label'=>false,'empty'=>'-Shade-', 'options' => $shades]); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Size </label>
									<?php echo $this->Form->control('size_id',['class'=>'form-control input-sm select2me','label'=>false,'empty'=>'-Size-', 'options' => $sizes]); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Description </label>
									<?php echo $this->Form->control('description',['class'=>'form-control input-sm','label'=>false,'rows'=>'2']); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Sales Rate </label>
									<?php echo $this->Form->control('sales_rate',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'Sales Rate','required'=>'required']); ?>
								</div>
							</div>
						</div>	
					
						<span class="caption-subject bold " style="float:center;">Gst Rate</span><hr style="margin: 6px 0;">
						<div class="row" >
							<div class="col-md-3">
								<div class="form-group">
									<div class="radio-list">
										<div class="radio-inline" style="padding-left: 0px;">
											<?php 
												echo $this->Form->radio(
												'gst_rate_fixed_or_fluid',
												[
													['value' => 'fix', 'text' => 'Fix','class' => 'radio-task kind_of_gst'],
													['value' => 'fluid', 'text' => 'Fluid','class' => 'radio-task kind_of_gst']
												]
												); ?>
										</div>
									</div>
								</div>
							</div>
						</div>	
							<div class="row" >
								<div class="col-md-4">
									<div class="form-group">
										<label>Gst Rate Less than or Equal to Amt </label>
										<?php echo $this->Form->control('first_gst_figure_id',['class'=>'form-control input-sm','label'=>false,'empty'=>'-GST Figure-', 'options' => $gstFigures,'required'=>'required']);
										
										if(@$item->kind_of_gst=="fix")
										{
											$style="style='display:none;'";
											$validation="'required'=>'required'";
										}
										?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group hide_gst" <?php echo @$style;?>>
										<label >Amount</br></br> </label>
										<?php 
										echo $this->Form->control('amount_in_ref_of_gst_rate',['class'=>'form-control input-sm removeAddRequired','label'=>false,'placeholder'=>'Amount','required'=>'required']); ?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group hide_gst" <?php echo @$style;?>>
										<label>Gst Greter than to Amount</br></br> </label>
										<?php echo $this->Form->control('second_gst_figure_id',['class'=>'form-control input-sm removeAddRequired','label'=>false,'empty'=>'-GST Figure-', 'options' => $gstFigures,'required'=>'required']); ?>
									</div>
								</div>
							</div>
							<span class="caption-subject bold " style="float:center;">Barcode</span><hr style="margin: 6px 0;">
							<div class="row" >
								<div class="col-md-6">
									<span>Item Code: <?php echo $secondTampGrnRecord->item_code; ?></span>
								</div>
								<div class="col-md-6">
									<?= $this->Html->Image('barcode/'.$secondTampGrnRecord->item_id.'.png') ?>
								</div>
							</div>	
				</br>
				</br>
			</div>
			<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success']) ?>
			<?= $this->Form->end() ?>
			
		</div>
	</div>
</div>
<!-- BEGIN PAGE LEVEL STYLES -->
	<!-- BEGIN COMPONENTS PICKERS -->
	<?php echo $this->Html->css('/assets/global/plugins/clockface/css/clockface.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<!-- END COMPONENTS PICKERS -->

	<!-- BEGIN COMPONENTS DROPDOWNS -->
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-select/bootstrap-select.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/select2/select2.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/jquery-multi-select/css/multi-select.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<!-- END COMPONENTS DROPDOWNS -->
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
	<!-- BEGIN COMPONENTS PICKERS -->
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/clockface/js/clockface.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-daterangepicker/moment.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<!-- END COMPONENTS PICKERS -->
	
	<!-- BEGIN COMPONENTS DROPDOWNS -->
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-select/bootstrap-select.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/select2/select2.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<?php echo $this->Html->script('/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<!-- END COMPONENTS DROPDOWNS -->
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<!-- BEGIN COMPONENTS PICKERS -->
	<?php echo $this->Html->script('/assets/admin/pages/scripts/components-pickers.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<!-- END COMPONENTS PICKERS -->

	<!-- BEGIN COMPONENTS DROPDOWNS -->
	<?php echo $this->Html->script('/assets/global/scripts/metronic.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/layout.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/quick-sidebar.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/demo.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/pages/scripts/components-dropdowns.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<!-- END COMPONENTS DROPDOWNS -->
<!-- END PAGE LEVEL SCRIPTS -->
<?php
	$js="
	$(document).ready(function() {
		
		var gst_type1 = $('input[name=gst_rate_fixed_or_fluid]:checked'). val();
		kind_of_gst(gst_type1);
		 
	
		$('.calculation').die().live('keyup',function(){
		  amt_calc();
		});
		$('.reverseCalculation').die().live('keyup',function(){
		   reverce_amt_calc();
		});
		function amt_calc()
		{
		var qty = $('.qty').val();
		var rate = $('.rate').val();
		var amt = qty*rate
		if(amt)
		{
		$('.amt').val(amt.toFixed(2)); 
		}
		}

		function reverce_amt_calc()
		{
		  var qty = $('.qty').val();
		  var amt = $('.amt').val();
		  if(qty){
		  var rate = amt/qty;
		  if(rate)
		  {
		  $('.rate').val(rate.toFixed(2));  }}
		}

		$('.kind_of_gst').die().live('change',function(){
			var gst_type = $(this).val();
			kind_of_gst(gst_type);
		});
		function kind_of_gst(gst_type){
			if(gst_type=='fix')
			{
				$('.hide_gst').hide();
				$('.removeAddRequired').removeAttr('required');
			}
			else
			{
				$('.hide_gst').show();
				$('.removeAddRequired').attr('required','required');
			}
		}
		ComponentsPickers.init();
	});
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>