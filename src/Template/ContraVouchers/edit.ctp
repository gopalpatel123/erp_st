<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Contra Voucher');

$option_mode[]= ['value'=>'Cheque','text'=>'Cheque'];
$option_mode[]= ['value'=>'NEFT/RTGS','text'=>'NEFT/RTGS'];
$option_mode[]= ['value'=>'Cash','text'=>'Cash'];
?>
<style>
.noBorder{
	border:none;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
     vertical-align: top !important; 
}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Edit Contra Voucher</span>
				</div>
				<div class="actions">
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($contraVoucher,['id'=>'form_sample_2']) ?>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Voucher No :</label>&nbsp;&nbsp;
							<?= h(str_pad($contraVoucher->voucher_no, 4, '0', STR_PAD_LEFT)) ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Transaction Date <span class="required">*</span></label>
							<?php echo $this->Form->control('transaction_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'required'=>'required','value'=>date('d-m-Y',strtotime($contraVoucher->transaction_date))]); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Reference No </label>
							<?php echo $this->Form->control('reference_no',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'refrence no','type'=>'text']); ?>
						</div>
					</div>
				</div>
				<div class="row">
						<div class="table-responsive">
							<table id="MainTable" class="table table-condensed table-striped" width="100%">
								<thead>
									<tr>
										<th></th>
										<th>Particulars</th>
										<th>Debit</th>
										<th>Credit</th>
										<th width="10%"></th>
									</tr>
								</thead>
								<tbody id='MainTbody' class="tab">
								<?php
								if(!empty($contraVoucher->contra_voucher_rows))
								{
									$i=0; 
									foreach($contraVoucher->contra_voucher_rows as $contra_voucher_row){	
								?>
									<tr class="MainTr" row_no="<?php echo $i;?>">
										<td width="10%">
											<?php 
											echo $this->Form->input('contra_voucher_rows.'.$i.'.id',['value'=>$contra_voucher_row->id,'class'=>'hidden']);
											if($i==0)
											{
												echo $this->Form->input('contra_voucher_rows.'.$i.'.cr_dr', ['options'=>['Dr'=>'Dr'],'label' => false,'class' => 'form-control input-sm cr_dr','required'=>'required','readonly'=>'readonly']); 
											}
											else if($i==1)
											{
												echo $this->Form->input('contra_voucher_rows.'.$i.'.cr_dr', ['options'=>['Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm cr_dr','required'=>'required','value'=>'Cr','readonly'=>'readonly']);
											}
											else{
											echo $this->Form->input('contra_voucher_rows.'.$i.'.cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm cr_dr','required'=>'required','value'=>$contra_voucher_row->cr_dr]); 
											}
											
											?>
											
										</td>
										<td width="65%">
										<?php
										if($i==0)
										{ 
										?>
											<?php echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$ledgerDroption,'label' => false,'class' => 'form-control input-sm ledger','required'=>'required','value'=>$contra_voucher_row->ledger_id]); 
										}
										else if($i==1)
										{
											echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$ledgerOptions,'label' => false,'class' => 'form-control input-sm ledger','required'=>'required','value'=>$contra_voucher_row->ledger_id]);
										}
										else{
											echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$AllLedgers,'label' => false,'class' => 'form-control input-sm ledger','required'=>'required','value'=>$contra_voucher_row->ledger_id]);
										}
										?>
											<div class="window" style="margin:auto;">
											<?php
											if(!empty($contra_voucher_row->mode_of_payment)){
												if($contra_voucher_row->mode_of_payment=='NEFT/RTGS' || $contra_voucher_row->mode_of_payment=='Cash' )
												{  
													$style="display:none;";
												}
												else 
												{
													$style="";
												} 
												if(!empty($contra_voucher_row->cheque_date))
												{
													$date = date("d-m-Y",strtotime($contra_voucher_row->cheque_date));
												}
											?>
											<table width='90%'>
												<tbody>
													<tr>
														<td width="30%" style="vertical-align: top !important;">
															<?php 
															echo $this->Form->input('contra_voucher_rows.'.$i.'.mode_of_payment', ['options'=>$option_mode,'label' => false,'class' => 'form-control input-sm paymentType','required'=>'required','value'=>$contra_voucher_row->mode_of_payment]); ?>
														</td>
														<td width="30%" style="<?php echo @$style;?>" style="vertical-align: top !important;">
															<?php echo $this->Form->input('contra_voucher_rows.'.$i.'.cheque_no', ['label' =>false,'class' => 'form-control input-sm cheque_no','placeholder'=>'Cheque No','value'=>$contra_voucher_row->cheque_no]); ?> 
														</td>
														
														<td width="30%" style="<?php echo @$style;?>" style="vertical-align: top !important;">
															<?php echo $this->Form->input('contra_voucher_rows.'.$i.'.cheque_date', ['label' =>false,'class' => 'form-control input-sm date-picker cheque_date ','data-date-format'=>'dd-mm-yyyy','placeholder'=>'Cheque Date','value'=>@$date,'type'=>'text']); ?>
														</td>
													</tr>
												</tbody>
												<tfoot>
												<td colspan='4'></td>
												</tfoot>
											</table>
											<?php } ?>
											</div>
										</td>
										<td width="10%">
										<?php if(empty($contra_voucher_row->debit))
											  {
												  $style1="display:none;";
											  }else
											  {
												   $style1="display:block;";
											  }
											?>
											<?php echo $this->Form->input('debit', ['label' => false,'class' => 'form-control input-sm  debitBox rightAligntextClass numberOnly totalCalculation','placeholder'=>'Debit','value'=>$contra_voucher_row->debit,'style'=>@$style1]); ?>
										
										</td>
										<td width="10%">
										<?php 
										      if(empty($contra_voucher_row->credit))
											  {
												  $style2="display:none;";
											  }else
											  {
												   $style2="display:block;";
											  }
										?>
											<?php echo $this->Form->input('credit', ['label' => false,'class' => 'form-control input-sm  creditBox rightAligntextClass numberOnly totalCalculation','placeholder'=>'Credit','value'=>$contra_voucher_row->credit,'style'=>@$style2]); ?>
										</td>
										<td align="center"  width="10%">
										<?php 
											if($i>1)
											{
										?>
											<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
										<?php } ?>
										</td>
									</tr>
								<?php $i++; } } ?>	
								</tbody>
								<tfoot>
									<tr style="border-top:double;">
										<td colspan="2">	
											<button type="button" class="AddMainRow btn btn-default input-sm"><i class="fa fa-plus"></i> Add row</button>
										</td>
										<td><input type="text" class="form-control input-sm rightAligntextClass total_debit" placeholder="Total Debit" id="totalMainDr" name="totalMainDr" value="<?php echo $contraVoucher->totalMainDr;?>" readonly></td>
										<td><input type="text" class="form-control input-sm rightAligntextClass total_credit" placeholder="Total Credit" id="totalMainCr" name="totalMainCr" value="<?php echo $contraVoucher->totalMainCr;?>" readonly></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label>Narration </label>
								<?php echo $this->Form->control('narration',['class'=>'form-control input-sm ','label'=>false,'placeholder'=>'Narration','rows'=>'4']); ?>
							</div>
						</div>
					</div>
				<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit'])  ?>
				<?= $this->Form->end() ?>
			</div>
		</div>
	</div>
</div>


<table id="sampleForBank" style="display:none;" width="100%">
	<tbody>
		<tr>
			<td width="30%" style="vertical-align: top !important;">
				<?php 
				echo $this->Form->input('mode_of_payment', ['options'=>$option_mode,'label' => false,'class' => 'form-control input-sm paymentType','required'=>'required']); ?>
			</td>
			<td width="30%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('cheque_no', ['label' =>false,'class' => 'form-control input-sm cheque_no','placeholder'=>'Cheque No']); ?> 
			</td>
			
			<td width="30%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('cheque_date', ['label' =>false,'class' => 'form-control input-sm date-picker cheque_date ','data-date-format'=>'dd-mm-yyyy','placeholder'=>'Cheque Date']); ?>
			</td>
			
			
		</tr>
	</tbody>
</table>

<table id="sampleMainTable" style="display:none;" width="100%">
	<tbody class="sampleMainTbody">
		<tr class="MainTr">
			<td width="10%" style="vertical-align: top !important;">
				<?php 
				echo $this->Form->input('cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm cr_dr','required'=>'required','value'=>'Dr']); ?>
			</td>
			<td width="65%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$ledgers,'label' => false,'class' => 'form-control input-sm ledger','required'=>'required']); ?>
				<div class="window" style="margin:auto;"></div>
			</td>
			<td width="10%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('debit', ['label' => false,'class' => 'form-control input-sm  debitBox rightAligntextClass numberOnly totalCalculation','placeholder'=>'Debit']); ?>
			</td>
			<td width="10%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('credit', ['label' => false,'class' => 'form-control input-sm creditBox rightAligntextClass numberOnly totalCalculation','placeholder'=>'Credit','style'=>'display:none;']); ?>	
			</td>
			<td align="center"  width="10%" style="vertical-align: top !important;">
				<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
			</td>
		</tr>
		
	</tbody>
	<tfoot >
		<tr>
			<td colspan="2" >	
				<button type="button" class="add_row btn btn-default input-sm"><i class="fa fa-plus"></i> Add row</button>
			</td>
		</tr>
	</tfoot>
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
	<!-- BEGIN VALIDATEION -->
	<?php echo $this->Html->script('/assets/global/plugins/jquery-validation/js/jquery.validate.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<!-- END VALIDATEION -->

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
	$kk='<input type="text" class="form-control input-sm ref_name " placeholder="Reference Name">';
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
					totalMainCr: {
						equalTo: '#totalMainDr'
					},
				},
				messages: {
					totalMainCr: {
						equalTo: 'Total debit and credit not matched !'
					},
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
					if(confirm('Are you sure you want to submit!'))
					{
						success1.show();
						error1.hide();
						form1[0].submit();
						$('.submit').attr('disabled','disabled');
						$('.submit').text('Submiting...');
						return true;
					}
                }
			});
			
			$('.totalCalculation').die().live('keyup',function(){
				 calc();
			});
			
			function calc()
			{ 
				var totalCredit=0;
				var totalDebit=0;
				$('#MainTable tbody#MainTbody tr.MainTr').each(function(){ 
					var debit  = parseFloat($(this).find('td:nth-child(3) input.totalCalculation').val()); 
					var credit = parseFloat($(this).find('td:nth-child(4) input.totalCalculation').val()); 
					if(debit)
					{
						totalDebit  = totalDebit+debit;
					}
					if(credit)
					{
						totalCredit = totalCredit+credit;
					}
					
				}); 
				if(!totalDebit){ totalDebit=0; }
				$('.total_debit').val(round(totalDebit,2));
				
				if(!totalCredit){totalCredit=0; }
				$('.total_credit').val(round(totalCredit,2));
			}
			
			$('.paymentType').die().live('change',function(){
				var type=$(this).val();	
				var currentRefRow=$(this).closest('tr');
				var SelectedTr=$(this).closest('tr.MainTr');
				if(type=='NEFT/RTGS' || type=='Cash'){
					currentRefRow.find('span.help-block-error').remove();
					currentRefRow.find('td:nth-child(2) input').val('');
					currentRefRow.find('td:nth-child(3) input').val('');
					currentRefRow.find('td:nth-child(2)').hide();
					currentRefRow.find('td:nth-child(3)').hide();
					renameBankRows(SelectedTr);
				}
				else{
					currentRefRow.find('td:nth-child(2)').show();
					currentRefRow.find('td:nth-child(3)').show();
					renameBankRows(SelectedTr);
				}
			});
			
			$('.refDrCr').die().live('change',function(){
				var SelectedTr=$(this).closest('tr.MainTr');
				renameRefRows(SelectedTr);
			});
			
			$('.delete-tr').die().live('click',function() 
			{
				$(this).closest('tr').remove();
				renameMainRows();
				calc();
				
			});
			
			
			
			$('.cr_dr').die().live('change',function(){
				
				var cr_dr=$(this).val();
				if(cr_dr=='Cr')
				{
					$(this).closest('tr').find('.debitBox').val('');
					calc();
					$(this).closest('tr').find('.debitBox').hide();
					//$(this).closest('tr').find('.creditBox').attr('required', true);
					$(this).closest('tr').find('.creditBox').show();
				}
				else{
					
					//$(this).closest('tr').find('.debitBox').attr('required', false);
					$(this).closest('tr').find('.debitBox').show();
					$(this).closest('tr').find('.creditBox').val('');
					calc();
					$(this).closest('tr').find('.creditBox').hide();
				}
			});
			
			$('.ledger').die().live('change',function(){
				
				var openWindow=$(this).find('option:selected').attr('open_window'); 
				if(openWindow=='bank'){
					var SelectedTr=$(this).closest('tr.MainTr')
					var windowContainer=$(this).closest('td').find('div.window');
					windowContainer.html('');
					windowContainer.html('<table width=90%><tbody></tbody><tfoot><td colspan=4></td></tfoot></table>');
					AddBankRow(SelectedTr);
				}
				else{
					var SelectedTr=$(this).closest('tr.MainTr')
					var windowContainer=$(this).closest('td').find('div.window');
					windowContainer.html('');
				}
			});
			
			$(document).ready(ledgerShow);
			function ledgerShow()
			{
			    $('#MainTable tbody#MainTbody tr.MainTr').each(function(){
				var openWindow=$(this).find('td:nth-child(2) select.ledger option:selected').attr('open_window');
				if(openWindow=='no'){
				    var bankValue=0;
					var SelectedTr=$(this).closest('tr.MainTr');
					SelectedTr.find('.BankValueDefine').val(bankValue);
					var windowContainer=SelectedTr.find('td:nth-child(2) select.ledger option:selected').closest('td').find('div.window');
					windowContainer.html('');
				}
			  });
			}
			
			$('.AddMainRow').die().live('click',function(){ 
				addMainRow();
			});
			
			//addMainRow();
			function addMainRow(){
				var tr=$('#sampleMainTable tbody.sampleMainTbody tr.MainTr').clone();
				$('#MainTable tbody#MainTbody').append(tr);
				renameMainRows();
			}
			
			
			renameMainRows();
			function renameMainRows()
			{
				var i=0;
				$('#MainTable tbody#MainTbody tr.MainTr').each(function(){
					$(this).attr('row_no',i);
					$(this).find('td:nth-child(1) input.hidden').attr({name:'contra_voucher_rows['+i+'][id]',id:'contra_voucher_rows-'+i+'-id'});
					$(this).find('td:nth-child(1) select.cr_dr').attr({name:'contra_voucher_rows['+i+'][cr_dr]',id:'contra_voucher_rows-'+i+'-cr_dr'});
					$(this).find('td:nth-child(2) select.ledger').attr({name:'contra_voucher_rows['+i+'][ledger_id]',id:'contra_voucher_rows-'+i+'-ledger_id'}).select2();
					$(this).find('td:nth-child(3) input.debitBox').attr({name:'contra_voucher_rows['+i+'][debit]',id:'contra_voucher_rows-'+i+'-debit'});
					$(this).find('td:nth-child(4) input.creditBox').attr({name:'contra_voucher_rows['+i+'][credit]',id:'contra_voucher_rows-'+i+'-credit'});
					i++;
					var type=$(this).find('td:nth-child(2) option:selected').attr('open_window'); 
					
					var SelectedTr=$(this).closest('tr.MainTr');
					if(type=='party'){
						renameRefRows(SelectedTr);
					}
					if(type=='bank'){
						renameBankRows(SelectedTr);
					}
					
				});
			}
			
			$('.addBankRow').die().live('click',function(){
				var SelectedTr=$(this).closest('tr.MainTr');
				AddBankRow(SelectedTr);
			});
			
			function AddBankRow(SelectedTr){
				var bankTr=$('#sampleForBank tbody tr').clone();
				console.log(bankTr);
				SelectedTr.find('td:nth-child(2) div.window table tbody').append(bankTr);
				renameBankRows(SelectedTr);
			}
			
			function renameBankRows(SelectedTr){
				var row_no=SelectedTr.attr('row_no');
				SelectedTr.find('td:nth-child(2) div.window table tbody tr').each(function(){
					var type = $(this).find('td:nth-child(1) select.paymentType option:selected').val();
					$(this).find('td:nth-child(1) select.paymentType').attr({name:'contra_voucher_rows['+row_no+'][mode_of_payment]',id:'contra_voucher_rows-'+row_no+'-mode_of_payment'});
					$(this).find('td:nth-child(2) input.cheque_no').attr({name:'contra_voucher_rows['+row_no+'][cheque_no]',id:'contra_voucher_rows-'+row_no+'-cheque_no'});
					$(this).find('td:nth-child(3) input.cheque_date').attr({name:'contra_voucher_rows['+row_no+'][cheque_date]',id:'contra_voucher_rows-'+row_no+'-cheque_date'}).datepicker();
					if(type=='Cheque')
					{ 
						$(this).find('td:nth-child(2) input.cheque_no').rules('add','required');
						$(this).find('td:nth-child(3) input.cheque_date').rules('add','required');
					}
					else
					{
						$(this).find('td:nth-child(2) input.cheque_no').rules('remove','required');
						$(this).find('td:nth-child(3) input.cheque_date').rules('remove','required');
					}
				});
				
			}
			
			
			
			
			ComponentsPickers.init();
		});
	";
?>
<?php echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom'));  ?>
