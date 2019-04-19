<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Edit Supplier');
?>
<div class="row">
	<div class="col-md-8">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Edit Supplier</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($supplier,['id'=>'form_sample_2']) ?>
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Supplier Name <span class="required">*</span></label>
									<?php echo $this->Form->control('name',['class'=>'form-control input-sm','placeholder'=>'Supplier Name','label'=>false,'autofocus']); ?>
								</div>
								<div class="form-group">
									<label>PAN</label>
									<?php echo $this->Form->control('pan',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'PAN']); ?>
								</div>
								<div class="form-group">
									<label>Under  Accounting Group  <span class="required">*</span></label>
									<?php echo $this->Form->control('accounting_group_id',['class'=>'form-control input-sm select2me','label'=>false, 'options' => $accountingGroups,'value'=>@$supplier->ledger->accounting_group_id]); ?>
								</div>
								<div class="form-group">
									<label>GSTIN </label>
									<?php echo $this->Form->control('gstin',['class'=>'form-control input-sm gst','placeholder'=>'Eg:22ASDFR0967W6Z5','label'=>false]); ?>
								</div>
								<div class="form-group">
									<label>Mobile </label>
									<?php echo $this->Form->control('mobile',['class'=>'form-control input-sm','placeholder'=>'Mobile no','label'=>false,'autofocus','maxlength'=>10]); ?>
								</div>
								<div class="col-md-8" style="padding-left: 0px;padding-right: 0px;">
								<div class="form-group" >
									<label>Opening balance value</label>
									<?php 
										$value="";
										if(!empty($account_entry->debit))
										{
											$value =@$account_entry->debit;
										}
										else
										{
											$value = @$account_entry->credit;
										}
										echo $this->Form->control('opening_balance_value',['id'=>'opening_balance_value','class'=>'rightAligntextClass form-control input-sm balance','label'=>false,'value'=>@$value,'placeholder'=>'Opening Balance']);
									?>
								</div>
							</div>
							<div class="col-md-4" style="padding-left: 0px;padding-right:0;">
								<label style="visibility:hidden;">s</label>
								<?php $option =[['value'=>'Dr','text'=>'Dr'],['value'=>'Cr','text'=>'Cr']];
									$check="";
									if(!empty($account_entry->debit))
									{
										$check ='Dr';
									}
									else
									{
										$check ='Cr';
									}
									echo $this->Form->control('debit_credit',['id'=>'cr_dr','class'=>'form-control input-sm cr_dr','label'=>false, 'options' => $option,'value'=>'creditor','value'=>$check]);
								?>
							</div>
								<div class="form-group">
									<label>Bill to Bill Accounting </label>
									<?php 
									$option =[['value'=>'yes','text'=>'yes'],['value'=>'no','text'=>'no']];
									echo $this->Form->control('bill_to_bill_accounting',['class'=>'form-control input-sm bill_to_bill_accounting','label'=>false, 'options' => $option,'required'=>'required','value'=>$supplier->ledger->bill_to_bill_accounting]); ?>
								</div>
								<div class="form-group default_credit_days_div" >
								<label>Default Credit Days</label>
									<?php echo $this->Form->control('default_credit_days',['class'=>'form-control input-sm default_credit_days','placeholder'=>'Default Credit Days','label'=>false,'value'=>$supplier->ledger->default_credit_days]); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>State <span class="required">*</span></label>
									<?php echo $this->Form->control('state_id',['class'=>'form-control input-sm select2me','label'=>false,'empty'=>'-State-', 'options' => $states,'required'=>'required']); ?>
								</div>
								<div class="form-group">
									<label>City <span class="required">*</span></label>
									<?php echo $this->Form->control('city_id',['class'=>'form-control input-sm select2me','label'=>false,'empty'=>'-City-', 'options' => $cities,'required'=>'required']); ?>
								</div>
								<div class="form-group">
									<label>TAN</label>
									<?php echo $this->Form->control('tan',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'TAN']); ?>
								</div>
								<div class="form-group">
									<label>Email</label>
									<?php echo $this->Form->control('email',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'example@domain.com']); ?>
								</div>
								<div class="form-group">
									<label>Address</label>
									<?php echo $this->Form->control('address',['class'=>'form-control input-sm','label'=>false]); ?>
								</div>
							</div>
						</div>
						
					<div class="row">
							<div class="window" style="margin:auto;display:hidden;">
								<table width="90%" class="refTbl"><tbody>
							<?php
                          //  unset($option_ref);								 
							$option_ref[]= ['value'=>'New Ref','text'=>'New Ref'];
							$option_ref[]= ['value'=>'Advance','text'=>'Advance'];
							$option_ref[]= ['value'=>'On Account','text'=>'On Account'];
								if(!empty($supplier->reference_details)){
								?>
									
									<?php
										$j=0;$total_amount_dr=0;$total_amount_cr=0;$colspan=0; 
										
										foreach($supplier->reference_details as $reference_detail)
										{	?>
										<tr>
											<td width="20%">
												<?php 
												echo $this->Form->input('reference_details.'.$j.'.type', ['options'=>$option_ref,'label' => false,'class' => 'form-control input-sm refType','required'=>'required','value'=>$reference_detail->type]); ?>
											</td>
											
											<td width="">
												<?php if($reference_detail->type=='New Ref' || $reference_detail->type=='Advance'){ ?>
												<?php echo $this->Form->input('reference_details.'.$j.'.ref_name', ['type'=>'text','label' => false,'class' => 'form-control input-sm ref_name','placeholder'=>'Reference Name','required'=>'required', 'value'=>$reference_detail->ref_name]); ?>
												<?php } ?>
											</td>
											
											<td width="20%" style="padding-right:0px;">
												<?php
												$value="";
												$cr_dr="";
												
												if(!empty($reference_detail->debit))
												{
													$value=$reference_detail->debit;
													$total_amount_dr=$total_amount_dr+$reference_detail->debit;
													$cr_dr="Dr";
													$name="debit";
												}
												else
												{
													$value=$reference_detail->credit;
													$total_amount_cr=$total_amount_cr+$reference_detail->credit;
													$cr_dr="Cr";
													$name="credit";
												}

												echo $this->Form->input('reference_details.'.$j.'.'.$name, ['label' => false,'class' => 'form-control input-sm calculation rightAligntextClass','placeholder'=>'Amount','required'=>'required','value'=>$value, 'type'=>'text']); ?>
											</td>
											<td width="10%" style="padding-left:0px;">
												<?php 
												echo $this->Form->input('reference_details.'.$j.'.type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  calculation refDrCr','value'=>$cr_dr]); ?>
											</td>
											
											<td width="15%" style="padding-left:0px;"valign="top">
											<?php if($reference_detail->type=='New Ref' || $reference_detail->type=='Advance'){ 
												echo $this->Form->input('reference_details.'.$j.'.due_days', ['label' => false,'class' => 'form-control input-sm numberOnly rightAligntextClass dueDays','placeholder'=>'Due Days','value'=>$reference_detail->due_days, 'type'=>'text']); ?><?php } ?>
											</td> 
											<td  width="5%" align="right">
												<a class="delete-tr-ref calculation" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
											</td>
										</tr>
										<?php $j++;} 
										if($total_amount_dr>$total_amount_cr)
										{
											$total = $total_amount_dr-$total_amount_cr;
											$type="Dr";
										}
										if($total_amount_dr<$total_amount_cr)
										{
											$total = $total_amount_cr-$total_amount_dr;
											$type="Cr";
										}
										?>
										<?php } ?>
									</tbody>
									
									<tfoot>
										<tr class="remove_ref_foot">
											<td colspan="2"><input type="hidden" id="htotal" value="<?php echo @$total;?>">
											<a role="button" class="addRefRow">Add Row</a>
											</td>
											<td valign="top">
											<input type="text" name="total" class="form-control input-sm rightAligntextClass total calculation " id="total" value="<?php echo @$total;?>" readonly></td><td valign="top"><input type="text" id="total_type" name="total_type" class="form-control input-sm total_type calculation " value="<?php echo @$type;?>" readonly></td>
										
										</tr>
									</tfoot>
									</table>
						</div>
				   </div>
				</div>
				<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit']) ?>
				<?= $this->Form->end() ?>
			</div>
		</div>
	</div>
</div>


<table id="sampleForRef" style="display:none;" width="100%">
	<tbody>
		<tr>
			<td width="20%" valign="top"> 
				
				<?php 
				echo $this->Form->input('type', ['options'=>$option_ref,'label' => false,'class' => 'form-control input-sm refType']); ?>
			</td>
			<td width="" valign="top">
				<?php echo $this->Form->input('ref_name', ['type'=>'text','label' => false,'class' => 'form-control input-sm ref_name','placeholder'=>'Reference Name']); ?>
			</td>
			
			<td width="20%" style="padding-right:0px;" valign="top">
				<?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm calculation rightAligntextClass','placeholder'=>'Amount']); ?>
			</td>
			<td width="10%" style="padding-left:0px;" valign="top">
				<?php 
				echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  calculation refDrCr','value'=>'Dr']); ?>
			</td>
			<td width="15%" style="padding-left:0px;" valign="top">
				<?php 
				echo $this->Form->input('due_days', ['label' => false,'class' => 'form-control input-sm numberOnly rightAligntextClass dueDays','placeholder'=>'Due Days','value'=>0]);  ?>
			</td>
			<td width="5%" align="right" valign="top">
				<a class="delete-tr-ref calculation" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>
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
	<!-- BEGIN VALIDATEION -->
	<?php echo $this->Html->script('/assets/global/plugins/jquery-validation/js/jquery.validate.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<!-- END VALIDATEION -->
	<!-- BEGIN COMPONENTS DROPDOWNS -->
	<?php echo $this->Html->script('/assets/global/scripts/metronic.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/layout.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/quick-sidebar.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/layout/scripts/demo.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<?php echo $this->Html->script('/assets/admin/pages/scripts/components-dropdowns.js', ['block' => 'PAGE_LEVEL_SCRIPTS_JS']); ?>
	<!-- END COMPONENTS DROPDOWNS -->
<!-- END PAGE LEVEL SCRIPTS -->

<?php
	$kk='<input type="text" class="form-control input-sm ref_name " placeholder="Reference Name">';
	$dd='<input type="text" class="form-control input-sm rightAligntextClass dueDays " placeholder="Due Days" value=0>';
	$total_input='<input type="text" class="form-control input-sm rightAligntextClass total calculation noBorder" readonly>';
	$total_type='<input type="text" class="form-control input-sm total_type calculation noBorder" readonly>';
	
	$js="
	$(document).ready(function() {	
		var form1 = $('#form_sample_2');
		var error1 = $('.alert-danger', form1);
		var success1 = $('.alert-success', form1);

			form1.validate({
                errorElement: 'span',
                errorClass: 'help-block help-block-error',
                focusInvalid: false,
                ignore: '', 
				rules: {
					
				},
				messages: {
					
				},

				invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    Metronic.scrollTo(error1, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
							success1.show();
							error1.hide();
							form1[0].submit();
							$('.submit').attr('disabled','disabled');
							$('.submit').text('Submiting...');
							return true;

                }
			});
			
			var bill_accounting=$('.bill_to_bill_accounting option:selected', this).val();
			var main_amt=$('.balance').val();
			if(bill_accounting=='yes' && main_amt>0){
			$('.default_credit_days_div').show();
			$('.window').show();
			}
			else if(bill_accounting=='no'){
				$('.default_credit_days').val(0);
				$('.default_credit_days_div').hide();
				$('.window').hide();
			}
			else{
				$('.window').hide();
			}
			
		$('.delete-tr-ref').die().live('click',function() 
			{	
				$(this).closest('tr').remove();
				renameRefRows();
				calculation();
			});
		
		$('.bill_to_bill_accounting').die().live('change',function(){
			var bill_accounting=$('option:selected', this).val();
			if(bill_accounting=='no'){ 
				$('.default_credit_days').val(0);
				$('.default_credit_days_div').hide();
				$('div.window table tbody').find('tr').remove();
				$('div.window table.refTbl tfoot tr td:nth-child(2) input.total').rules('remove', 'equalTo');
				$('.window').hide();
			}
			else{
				$('.default_credit_days_div').show();
				var mainAmt=$('.balance').val();
				if(mainAmt>0){
				$('.window').show();
				AddRefRow();
				}else{
				$('div.window table tbody').find('tr').remove();
				$('div.window table.refTbl tfoot tr td:nth-child(2) input.total').rules('remove', 'equalTo');
				$('.window').hide();
				}
				
			}
		});
		$('.balance').live('blur',function()
		{
			var main_amt=$(this).val();
			var bill_accounting=$('.bill_to_bill_accounting option:selected').val();
			
			if(main_amt>0 && bill_accounting=='yes'){
				$('.window').show();
				AddRefRow();
				}else{
				$('div.window table tbody').find('tr').remove();
				$('div.window table.refTbl tfoot tr td:nth-child(2) input.total').rules('remove', 'equalTo');
				$('.window').hide();
				}
		});
		$('.addRefRow').die().live('click',function(){
				AddRefRow();
			});
			
		function AddRefRow(){
			
			var refTr=$('#sampleForRef tbody tr').clone();
			$('div.window table tbody').append(refTr);
			renameRefRows();
			calculation();
		}
		
		function renameRefRows(){
			var i=0;
			var bill_accounting=$('option:selected', this).val();
			var cr_dr =$('.cr_dr option:selected').val();
			var mainAmt=$('.balance').val();
			if(cr_dr=='Dr'){
					var eqlClassDr=$('.balance').attr('id');
					
				}else{
					var eqlClassCr=$('.balance').attr('id');
				}
				
				$('div.window table tbody tr').each(function(){
					$(this).find('td:nth-child(1) select.refType').attr({name:'reference_details['+i+'][type]',id:'reference_details-'+i+'-type'});
					var is_input=$(this).find('td:nth-child(2) input.ref_name').length;
					if(is_input){
						$(this).find('td:nth-child(2) input.ref_name').attr({name:'reference_details['+i+'][ref_name]',id:'reference_details-'+i+'-ref_name'}).rules('add', 'required');
						$(this).find('td:nth-child(5) input.dueDays').attr({name:'reference_details['+i+'][due_days]',id:'reference_details-'+i+'-due_days'});
					}
					var Dr_Cr=$(this).find('td:nth-child(4) select option:selected').val();
					if(Dr_Cr=='Dr'){
						$(this).find('td:nth-child(3) input').attr({name:'reference_details['+i+'][debit]',id:'reference_details-'+i+'-debit'}).rules('add', 'required');
					}else{
						$(this).find('td:nth-child(3) input').attr({name:'reference_details['+i+'][credit]',id:'reference_details-'+i+'-credit'}).rules('add', 'required');
					}
					i++;
				});
				var total_type=$('div.window table.refTbl tfoot tr td:nth-child(3) input.total_type').val();
				if(total_type=='Dr'){
					eqlClass=eqlClassDr;
				}else{
					eqlClass=eqlClassCr;
				}
				
				$('div.window table.refTbl tfoot tr td:nth-child(2) input.total')
						.rules('add', {
							equalTo: '#'+eqlClass,
							messages: {
								equalTo: 'Enter bill wise details upto '+mainAmt+' '+cr_dr
							}
						});
				
		}
		$('.refType').die().live('change',function(){
				var type=$(this).val();
				var currentRefRow=$(this).closest('tr');
				
				 if(type=='On Account'){
					currentRefRow.find('td:nth-child(2)').html('');
					currentRefRow.find('td:nth-child(5)').html('');
				}else{
					currentRefRow.find('td:nth-child(2)').html('".$kk."');
					currentRefRow.find('td:nth-child(5)').html('".$dd."');
				}
				renameRefRows();
			});
		
		$('.calculation').die().live('keyup, change',function()
			{ 
				calculation();
				
			});	
		function calculation(){
			var total_debit=0;var total_credit=0; var remaining=0;
				$('div.window table tbody tr').each(function(){
					var Dr_Cr=$(this).find('td:nth-child(4) select option:selected').val();
					var amt= parseFloat($(this).find('td:nth-child(3) input').val());
					if(!amt){ amt=0; }
					if(Dr_Cr=='Dr'){
						total_debit=round(total_debit+amt, 2);
					}
					else if(Dr_Cr=='Cr'){
						total_credit=round(total_credit+amt, 2);
					}
					
					remaining=round(total_debit-total_credit, 2);
					
					if(remaining>0){
						$(this).closest('table').find(' tfoot td:nth-child(2) input.total').val(remaining);
						$(this).closest('table').find(' tfoot td:nth-child(3) input.total_type').val('Dr');
					}
					else if(remaining<0){
						remaining=Math.abs(remaining)
						$(this).closest('table').find(' tfoot td:nth-child(2) input.total').val(remaining);
						$(this).closest('table').find(' tfoot td:nth-child(3) input.total_type').val('Cr');
					}
					else{
						$(this).closest('table').find(' tfoot td:nth-child(2) input.total').val('0');
						$(this).closest('table').find(' tfoot td:nth-child(3) input.total_type').val('');	
					}
				});
				renameRefRows();
		}	
		ComponentsPickers.init();
	});	
	
	$(document).on('blur', '.gst', function(e)
    { 
		var mdl=$(this).val();
		var numbers = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
		if(mdl.match(numbers))
		{
			
		}
		else
		{
			$(this).val('');
			return false;
		}
    });
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>
