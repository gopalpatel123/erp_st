<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Create Item');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Create Item</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($item,['onsubmit'=>'return checkValidation()','allow_to_submit'=>'0','id'=>'CreateItem']) ?>
				<div class="row">
				
					<div class="col-md-6">
					    <div class="form-group">
							<label>Item Name <span class="required">*</span></label>
							<?php echo $this->Form->control('name',['class'=>'form-control input-sm','placeholder'=>'Item Name','label'=>false,'autofocus']); ?>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Under Stock Group </label>
									<?php echo $this->Form->control('stock_group_id',['class'=>'form-control input-sm select2me','label'=>false,'empty'=>'-Primary-', 'options' => $stockGroups]); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>HSN Code</label>
									<?php echo $this->Form->control('hsn_code',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'HSN Code']); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Unit <span class="required">*</span></label>
									<?php echo $this->Form->control('unit_id',['class'=>'form-control input-sm select2me','label'=>false, 'options' => $units,'required'=>'required']); ?>
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
									<label>Shade </label>
									<?php echo $this->Form->control('shade_id',['class'=>'form-control input-sm select2me','label'=>false,'empty'=>'-Shade-', 'options' => $shades]); ?>
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
						<span class="caption-subject bold " style="float:center;">Opening Balance</span><hr style="margin: 6px 0;">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Quantity </label>
									<?php echo $this->Form->control('quantity',['class'=>' rightAligntextClass form-control input-sm qty calculation reverseCalculation','label'=>false,'placeholder'=>'Quantity']); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Rate </label>
									<?php echo $this->Form->control('rate',['class'=>'rightAligntextClass form-control input-sm rate calculation','label'=>false,'placeholder'=>'Rate']); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Value </label>
									<?php echo $this->Form->control('amount',['class'=>'rightAligntextClass form-control input-sm amt reverseCalculation','label'=>false,'placeholder'=>'Value']); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Sales Rate </label>
									<?php echo $this->Form->control('sales_rate',['class'=>'rightAligntextClass form-control input-sm','label'=>false,'placeholder'=>'Sales Rate','required'=>'required']); ?>
								</div>
							</div>
						</div>
						<span class="caption-subject bold " style="float:center;">Gst Rate</span><hr style="margin: 6px 0;">
						<div class="row" >
							<div class="col-md-3">
								<div class="form-group">
									<div class="radio-list">
										<div class="radio-inline" style="padding-left: 0px;">
											<?php echo $this->Form->radio(
											'kind_of_gst',
											[
												['value' => 'fix', 'text' => 'Fix','class' => 'radio-task kind_of_gst'],
												['value' => 'fluid', 'text' => 'Fluid','class' => 'radio-task kind_of_gst','checked' => 'checked']
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
									<label style="font-size: 9px;">Gst Rate Less than or Equal to Amt </label>
									<?php echo $this->Form->control('first_gst_figure_id',['class'=>'form-control input-sm','label'=>false,'empty'=>'-GST Figure-', 'options' => $gstFigures,'required'=>'required']); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group hide_gst">
									<label style="font-size: 10px;">Amount </label>
									<?php echo $this->Form->control('gst_amount',['class'=>'rightAligntextClass form-control input-sm removeAddRequired','label'=>false,'placeholder'=>'Amount','required'=>'required']); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group hide_gst">
									<label style="font-size: 10px;">Gst Greter than to Amount </label>
									<?php echo $this->Form->control('second_gst_figure_id',['class'=>'form-control input-sm removeAddRequired','label'=>false,'empty'=>'-GST Figure-', 'options' => $gstFigures,'required'=>'required']); ?>
								</div>
							</div>
						</div>
						<span class="caption-subject bold " style="float:center;">Barcode Generation</span><hr style="margin: 6px 0;">
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group">
									<div class="radio-list">
										<div class="radio-inline" style="padding-left: 0px;">
											<?php echo $this->Form->radio(
											'barcode_decision',
											[
												['value' => '1', 'text' => 'Let system  generate barcode','class' => 'barcode_decision','checked' => 'checked'],
												['value' => '2', 'text' => 'Already have barcode','class' => 'barcode_decision']
											]
											); ?>
										</div>
                                    </div>
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-4">
								<div class="form-group item_code_div" style="display:none;">
									<label>Item Code </label>
									<?php echo $this->Form->control('provided_item_code',['class'=>'form-control input-sm','label'=>false, 'placeholder'=>'Item Code', 'type'=>'text']); ?>
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
			if(gst_type=='fix')
			{
				$('.hide_gst').hide();
				$('input[name=gst_amount]').removeAttr('required');
				$('select[name=second_gst_figure_id]').removeAttr('required');
			}
			else
			{
				$('.hide_gst').show();
				$('input[name=gst_amount]').attr('required','required');
				$('select[name=second_gst_figure_id]').attr('required','required');
			}
		});
		
		$('.barcode_decision').die().live('click',function(){
			var barcode_decision = $(this).val();
			if(barcode_decision=='1')
			{
			  $('.item_code_div').hide();
			  $('input[name=provided_item_code]').removeAttr('required');
			}
			else
			{
			  $('.item_code_div').show();
			  $('input[name=provided_item_code]').attr('required','required');
			}
		});

		
		
		check_itemCode_uniqueness();
		$('input[name=provided_item_code]').die().live('blur',function(){
			check_itemCode_uniqueness();
		});
		function check_itemCode_uniqueness(){
			var barcode_decision = $('input[name=barcode_decision]').val();
			if(barcode_decision=='1'){
				$('#CreateItem').attr('allow_to_submit','1');
			}else{
				var provided_item_code = $('input[name=provided_item_code]').val();
				if(!provided_item_code){
					$('#CreateItem').attr('allow_to_submit','1');
				}else{
					$('#CreateItem').attr('allow_to_submit','0');
					var url='".$this->Url->build(['controller'=>'Items','action'=>'checkUnique'])."';
					url=url+'/'+provided_item_code;
					
					$.ajax({
						url: url,
						type: 'GET',
					}).done(function(response) {
						alert(response);
						response = $.parseJSON(response);
						if(response.is_unique=='yes')
						{
							$('input[name=provided_item_code]').closest('.form-group').append('<span class=error_unique>Matched.</span>');
						}else{
							$('input[name=provided_item_code]').closest('.form-group').append('<span class=error_unique>Not Matched.</span>');
						}
					});
				}
			}
			
		}
		
ComponentsPickers.init();
    });
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>