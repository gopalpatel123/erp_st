<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'User Rights');
?>
<style>
.disabledbutton {
    pointer-events: none;
    opacity: 0.7;
}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">User Rights</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($userRight,['onsubmit'=>'return checkValidation()']) ?>
				<div class="row">
						<div class="col-md-12">
							<div class="form-group">
							<label class="col-md-3"></label>
							<label class="col-md-2" style="text-align:right">Select User</label>
							<div class="col-md-3">
							<?php echo $this->Form->control('user_id',['empty'=>'- Select User -','class'=>'form-control input-sm getUser select2me','label'=>false, 'options' => $users,'required'=>'required']);
								?>
								</div>
						</div>
					</div>
					
					<div class="userData loading">
					
					</div>
					<br><br>
				<div class="row showButtons" style="display:none">
						<div class="col-md-12">
							<div class="form-group">
							<div class="col-md-6"></div>
							<div class="col-md-2">
							<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit', 'style'=>'align:center']) ?>
							</div>
							<div class="col-md-3"></div>
							</div>
							</div>
				</div>
				<?= $this->Form->end() ?>
			</div>
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
	$(document).ready(function(){
		$('.getUser').die().live('change',function(){ 
		var userid=$(this).val();
		$('.loading').html('loading...');
			var url='".$this->Url->build(["controller" => "UserRights", "action" => "ajaxUserRights"])."';
			url=url+'/'+userid
			$.ajax({
				url: url,
				type: 'GET'
				//dataType: 'text'
			}).done(function(response) {
			//alert(response);
			    $('.loading').val();
				//var fetch=$.parseJSON(response);
				$('.userData').html(response);
				$('.showButtons').show();
				
			});	
			});
			
		});
		$(document).ready(function(){
		$('.checkAll').live('click', function(){
			$('input:checkbox').not(this).prop('checked', this.checked);
			});
			});
			
		
	function checkValidations1() 
	{  
		var amount_before_tax  = parseFloat($('.amount_before_tax').val());
		if(!amount_before_tax || amount_before_tax==0){
			alert('Error: Zero amount invoice can not be generated.');
			return false;
		}
		
		if(!amount_before_tax || amount_before_tax < 0){
			alert('Error: Minus amount invoice can not be generated.');
			return false;
		}
		
		var StockDB=[]; var StockInput = {};
		$('#main_table tbody#main_tbody tr.main_tr').each(function()
		{
			var stock=$(this).find('td:nth-child(1) input.totStock').val();
			var item_id=$(this).find('td:nth-child(1) select.attrGet option:selected').val();
			var quantity=parseFloat($(this).find('td:nth-child(2) input.quantity').val());
			var existingQty=parseFloat(StockInput[item_id]);
			if(!existingQty){ existingQty=0; }
			StockInput[item_id] = quantity+existingQty;
			StockDB[item_id] = stock;
		});
		
		var c=1;
		$('#main_table tbody#main_tbody tr.main_tr').each(function()
		{
			var item_id=$(this).find('td:nth-child(1) select.attrGet option:selected').val();
			if(StockInput[item_id]>StockDB[item_id]){
				c=0;
			}
		});
		if(c==0){
			alert('Error: Stock is going in minus.');
			return false;
		}
		if(confirm('Are you sure you want to submit!'))
		{
			$('.submit').attr('disabled','disabled');
			$('.submit').text('Submiting...');
			return true;
		}
		else
		{
			return false;
		}
	}";
	
	
	
	$js.="
	$(document).ready(function() {
	$('.quantity,.discount,.dis_amount').keypress(function(event) {
			if ( event.which == 45 || event.which == 189 ) {
			event.preventDefault();
		}
		}); });
		
		function checkValidation()
	{
	        $('.submit').attr('disabled','disabled');
	        $('.submit').text('Submiting...');
    }
		
		";
echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>