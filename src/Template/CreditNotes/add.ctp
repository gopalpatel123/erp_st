<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Credit Note Voucher');
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
					<span class="caption-subject font-green-sharp bold ">Credit Note Voucher</span>
				</div>
				<div class="actions">
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($creditNote,['id'=>'form_sample_2']) ?>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Voucher No :</label>&nbsp;&nbsp;
							<?= h(str_pad($voucher_no, 4, '0', STR_PAD_LEFT)) ?>
						<input type="hidden" name="voucher_no" value="<?php echo $voucher_no;?>" >
						<input type="hidden" name="company_id" value="<?php echo $company_id;?>" >
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Transaction Date <span class="required">*</span></label>
							<?php echo $this->Form->control('transaction_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y'),'required'=>'required']); ?>
						</div>
					</div>
				</div>
				<div class="row">
						<div class="table-responsive">
							<table id="MainTable" class="table table-condensed table-striped" width="100%">
								<thead>
									<tr>
										<td></td>
										<th>Particulars</th>
										<th>Debit</th>
										<th>Credit</th>
										<th width="10%"></th>
									</tr>
								</thead>
								<tbody id='MainTbody' class="tab">
									<tr class="MainTr">
										<td width="10%" valign="top">
											<?php 
											echo $this->Form->input('cr_dr', ['options'=>['Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm cr_dr', 'readonly'=>'readonly','required'=>'required','value'=>'Cr']); ?>
										</td>
										<td width="65%" valign="top">
											<?php echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$ledgerFirstOptions,'label' => false,'class' => 'form-control input-sm ledger select2me','required'=>'required']); ?>
											<div class="window" style="margin:auto;"></div>
										</td>
										<td width="10%" valign="top">
											<?php echo $this->Form->input('debit', ['label' => false,'class' => 'form-control input-sm  debitBox rightAligntextClass numberOnly calculate_total','placeholder'=>'Debit','style'=>'display:none;']); ?>
										</td>
										<td width="10%" valign="top">
											<?php echo $this->Form->input('credit', ['label' => false,'class' => 'form-control input-sm creditBox rightAligntextClass numberOnly calculate_total','placeholder'=>'Credit']); ?>	
										</td>
										<td align="center"  width="10%" valign="top">
											<!--<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a> -->
										</td>
									</tr>
									<tr class="MainTr">
										<td width="10%" valign="top">
											<?php 
											echo $this->Form->input('cr_dr', ['options'=>['Dr'=>'Dr'],'label' => false,'class' => 'form-control input-sm cr_dr', 'readonly'=>'readonly','required'=>'required','value'=>'Dr']); ?>
										</td>
										<td width="65%" valign="top">
											<?php echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$ledgerOptions,'label' => false,'class' => 'form-control input-sm ledger select2me','required'=>'required']); ?>
											<div class="window" style="margin:auto;"></div>
										</td>
										<td width="10%" valign="top">
											<?php echo $this->Form->input('debit', ['label' => false,'class' => 'form-control input-sm  debitBox rightAligntextClass numberOnly calculate_total','placeholder'=>'Debit']); ?>
										</td>
										<td width="10%" valign="top">
											<?php echo $this->Form->input('credit', ['label' => false,'class' => 'form-control input-sm creditBox rightAligntextClass numberOnly calculate_total','placeholder'=>'Credit','style'=>'display:none;']); ?>	
										</td>
										<td align="center"  width="10%" valign="top">
											<!--<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a> -->
										</td>
									</tr>
								</tbody>
								<tfoot>
									<tr style="border-top:double;">
										<td colspan="2" valign="top" >	
											<button type="button" class="AddMainRow btn btn-default input-sm"><i class="fa fa-plus"></i> Add row</button>
											<input type="hidden" id="totalBankCash">
										</td>
										<td valign="top"><input type="text" class="form-control input-sm rightAligntextClass noBorder " name="totalMainDr" id="totalMainDr" readonly></td>
										<td valign="top"><input type="text" class="form-control input-sm rightAligntextClass noBorder" name="totalMainCr" id="totalMainCr" readonly></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label>Narration </label>
								<?php echo $this->Form->control('narration',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'Narration','rows'=>'4']); ?>
							</div>
						</div>
					</div>
				<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit'])  ?>
				<?= $this->Form->end() ?>
			</div>
		</div>
	</div>
</div>
<?php
$option_ref[]= ['value'=>'New Ref','text'=>'New Ref'];
$option_ref[]= ['value'=>'Against','text'=>'Against'];
$option_ref[]= ['value'=>'Advance','text'=>'Advance'];
$option_ref[]= ['value'=>'On Account','text'=>'On Account'];
?>


<table id="sampleForRef" style="display:none;" width="100%">
	<tbody>
		<tr>
			<td width="20%" valign="top"> 
				<input type="hidden" class="ledgerIdContainer" />
				<input type="hidden" class="companyIdContainer" />
				<?php 
				echo $this->Form->input('type', ['options'=>$option_ref,'label' => false,'class' => 'form-control input-sm refType','required'=>'required']); ?>
			</td>
			<td width="" valign="top">
				<?php echo $this->Form->input('ref_name', ['type'=>'text','label' => false,'class' => 'form-control input-sm ref_name','placeholder'=>'Reference Name','required'=>'required']); ?>
			</td>
			<td width="20%" style="padding-right:0px;" valign="top">
				<?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm calculation numberOnly rightAligntextClass','placeholder'=>'Amount','required'=>'required']); ?>
			</td>
			<td width="10%" style="padding-left:0px;" valign="top">
				<?php 
				echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  calculation refDrCr','value'=>'Dr']); ?>
			</td>
			<td width="15%" style="padding-left:0px;" valign="top">
				<?php 
				echo $this->Form->input('due_days', ['label' => false,'class' => 'form-control input-sm numberOnly rightAligntextClass dueDays','placeholder'=>'Due Days','title'=>'Due Days']);  ?>
			</td>
			<td width="5%" align="right" valign="top">
				<a class="delete-tr-ref" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>


<?php
$option_mode[]= ['value'=>'Cheque','text'=>'Cheque'];
$option_mode[]= ['value'=>'NEFT/RTGS','text'=>'NEFT/RTGS'];
?>
<table id="sampleForBank" style="display:none;" width="100%">
	<tbody>
		<tr>
			<td width="30%" valign="top">
				<?php 
				echo $this->Form->input('mode_of_payment', ['options'=>$option_mode,'label' => false,'class' => 'form-control input-sm paymentType','required'=>'required']); ?>
			</td>
			<td width="30%" valign="top">
				<?php echo $this->Form->input('cheque_no', ['label' =>false,'class' => 'form-control input-sm cheque_no positiveValue','placeholder'=>'Cheque No']); ?> 
			</td>
			
			<td width="30%" valign="top">
				<?php echo $this->Form->input('cheque_date', ['label' =>false,'class' => 'form-control input-sm date-picker cheque_date ','data-date-format'=>'dd-mm-yyyy','placeholder'=>'Cheque Date']); ?>
			</td>
			
			
		</tr>
	</tbody>
</table>

<table id="sampleMainTable" style="display:none;" width="100%">
	<tbody class="sampleMainTbody">
		<tr class="MainTr">
			<td width="10%" valign="top">
				<?php 
				echo $this->Form->input('cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm cr_dr','required'=>'required','value'=>'Dr']); ?>
			</td>
			<td width="65%" valign="top">
				<?php echo $this->Form->input('ledger_id', ['empty'=>'--Select--','options'=>@$ledgerOptions,'label' => false,'class' => 'form-control input-sm ledger','required'=>'required']); ?>
				<div class="window" style="margin:auto;"></div>
			</td>
			<td width="10%" valign="top">
				<?php echo $this->Form->input('debit', ['label' => false,'class' => 'form-control input-sm  debitBox numberOnly rightAligntextClass calculate_total','placeholder'=>'Debit']); ?>
			</td>
			<td width="10%" valign="top">
				<?php echo $this->Form->input('credit', ['label' => false,'class' => 'form-control input-sm creditBox numberOnly rightAligntextClass calculate_total','placeholder'=>'Credit','style'=>'display:none;']); ?>	
			</td>
			<td align="center"  width="10%" valign="top">
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
	$dd='<input type="text" class="form-control input-sm rightAligntextClass dueDays " placeholder="Due Days">';
	$total_input='<input type="text" class="form-control input-sm rightAligntextClass total calculation noBorder" readonly >';
	$total_type='<input type="text" class="form-control input-sm total_type calculation noBorder" readonly >';
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
					var totalMainDr  = parseFloat($('#totalMainDr').val());
					var totalBankCash = parseFloat($('#totalBankCash').val());
					if(!totalMainDr || totalMainDr==0){
						alert('Error: zero amount creditNote can not be generated.');
						return false;
					}
					/* else if(totalBankCash<=0){
						alert('Error: No Bank or Cash Debited.');
						return false;
					} */
					else{
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
                }

			});
			
			$('.delete-tr').die().live('click',function() 
			{	
				$(this).closest('tr.MainTr').remove();
				renameMainRows();
			});
			
			$('.delete-tr-ref').die().live('click',function() 
			{	var SelectedTr=$(this).closest('tr.MainTr');
				$(this).closest('tr').remove();
				renameMainRows();
				renameRefRows(SelectedTr);
				calculation(SelectedTr);
			});
			
			$('.paymentType').die().live('change',function(){
				var type=$(this).val();	
				var currentRefRow=$(this).closest('tr');
				var SelectedTr=$(this).closest('tr.MainTr');
				if(type=='NEFT/RTGS'){
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
			
			$('.refType').die().live('change',function(){
				var type=$(this).val();
				var currentRefRow=$(this).closest('tr');
				var ledger_id=$(this).closest('tr.MainTr').find('select.ledger option:selected').val();
				var due_days=$(this).closest('tr.MainTr').find('select.ledger option:selected').attr('default_days');
				if(type=='Against'){
					$(this).closest('tr').find('td:nth-child(2)').html('Loading Ref List...');
					var url='".$this->Url->build(['controller'=>'ReferenceDetails','action'=>'listRef'])."';
					url=url+'/'+ledger_id;
					$.ajax({
						url: url,
					}).done(function(response) { 
						currentRefRow.find('td:nth-child(2)').html(response);
						currentRefRow.find('td:nth-child(5)').html('');
					});
				}else if(type=='On Account'){
					currentRefRow.find('td:nth-child(2)').html('');
					currentRefRow.find('td:nth-child(5)').html('');
				}else{
					currentRefRow.find('td:nth-child(2)').html('".$kk."');
					currentRefRow.find('td:nth-child(5)').html('".$dd."');
					currentRefRow.find('td:nth-child(5) input.dueDays').val(due_days);
				}
				var SelectedTr=$(this).closest('tr.MainTr');
				renameRefRows(SelectedTr);
			});
			
			$('.cr_dr').die().live('change',function(){
				var cr_dr=$(this).val();
				
				if(cr_dr=='Cr'){
					$(this).closest('tr').find('.debitBox').val('');
					$(this).closest('tr').find('.debitBox').hide();
					$(this).closest('tr').find('.creditBox').show();
				}else{
					$(this).closest('tr').find('.creditBox').val('');
					$(this).closest('tr').find('.debitBox').show();
					$(this).closest('tr').find('.creditBox').hide();
				}
				renameMainRows();
				
				var SelectedTr=$(this).closest('tr.MainTr');
				renameRefRows(SelectedTr);
			});
			
			$('.ledger').die().live('change',function(){
				var openWindow=$(this).find('option:selected').attr('open_window');
				
				if(openWindow=='party'){
					var SelectedTr=$(this).closest('tr.MainTr');
					var windowContainer=$(this).closest('td').find('div.window');
					windowContainer.html('');
					windowContainer.html('<table width=90% class=refTbl><tbody></tbody><tfoot><tr style=border-top:double#a5a1a1><td colspan=2><a role=button class=addRefRow>Add Row</a></td><td>$total_input</td><td valign=top>$total_type</td></tr></tfoot></table>');
					AddRefRow(SelectedTr);
				}
				else if(openWindow=='bank'){
					var SelectedTr=$(this).closest('tr.MainTr')
					var windowContainer=$(this).closest('td').find('div.window');
					windowContainer.html('');
					windowContainer.html('<table width=90% ><tbody></tbody><tfoot><td colspan=4></td></tfoot></table>');
					AddBankRow(SelectedTr);
				}
				else{
					var windowContainer=$(this).closest('td').find('div.window');
					windowContainer.html('');
				}
				renameMainRows();
			});
			
			$('.AddMainRow').die().live('click',function(){ 
				addMainRow();
			});
			
			//addMainRow();
			function addMainRow(){
				var tr=$('#sampleMainTable tbody.sampleMainTbody tr.MainTr').clone();
				$('#MainTable tbody#MainTbody').append(tr);
				renameMainRows();
			}
			
			function renameMainRows(){
				var i=0; var main_debit=0; var main_credit=0; var count_bank_cash=0;
				$('#MainTable tbody#MainTbody tr.MainTr').each(function(){
					$(this).attr('row_no',i);
					var cr_dr=$(this).find('td:nth-child(1) select.cr_dr option:selected').val()
					var is_cash_bank=$(this).find('td:nth-child(2) option:selected').attr('bank_and_cash');
					$(this).find('td:nth-child(1) select.cr_dr').attr({name:'credit_note_rows['+i+'][cr_dr]',id:'credit_note_rows-'+i+'-cr_dr'});
					$(this).find('td:nth-child(2) select.ledger').attr({name:'credit_note_rows['+i+'][ledger_id]',id:'credit_note_rows-'+i+'-ledger_id'}).select2();
					$(this).find('td:nth-child(3) input.debitBox').attr({name:'credit_note_rows['+i+'][debit]',id:'credit_note_rows-'+i+'-debit'});
					$(this).find('td:nth-child(4) input.creditBox').attr({name:'credit_note_rows['+i+'][credit]',id:'credit_note_rows-'+i+'-credit'});
					
					if(cr_dr=='Dr'){
						$(this).find('td:nth-child(3) input.debitBox').rules('add', 'required');
						$(this).find('td:nth-child(4) input.creditBox').rules('remove', 'required');
						$(this).find('td:nth-child(4) span.help-block-error').remove();
						var debit_amt=parseFloat($(this).find('td:nth-child(3) input.debitBox').val());
						if(!debit_amt){
							debit_amt=0;
						}
						main_debit=round(main_debit+debit_amt, 2);
						if(is_cash_bank=='yes'){
						 count_bank_cash++;
						}
						
					}else{
						$(this).find('td:nth-child(3) input.debitBox').rules('remove', 'required');
						$(this).find('td:nth-child(3) span.help-block-error').remove();
						$(this).find('td:nth-child(4) input.creditBox').rules('add', 'required');
						var credit_amt=parseFloat($(this).find('td:nth-child(4) input.creditBox').val());
						if(!credit_amt){
							credit_amt=0;
						}
						main_credit=round(main_credit+credit_amt, 2);
						
					}
					i++;
				});
				$('#MainTable tfoot tr td:nth-child(2) input#totalMainDr').val(main_debit);
				$('#MainTable tfoot tr td:nth-child(3) input#totalMainCr').val(main_credit);
				$('#MainTable tfoot tr td:nth-child(1) input#totalBankCash').val(count_bank_cash);
			}
			
			$('.addBankRow').die().live('click',function(){
				var SelectedTr=$(this).closest('tr.MainTr');
				AddBankRow(SelectedTr);
			});
			
			function AddBankRow(SelectedTr){
				var bankTr=$('#sampleForBank tbody tr').clone();
				//console.log(bankTr);
				SelectedTr.find('td:nth-child(2) div.window table tbody').append(bankTr);
				renameBankRows(SelectedTr);
			}
			
			function renameBankRows(SelectedTr){
				var row_no=SelectedTr.attr('row_no');
				SelectedTr.find('td:nth-child(2) div.window table tbody tr').each(function(){
					
					var type = SelectedTr.find('td:nth-child(1) select.paymentType option:selected').val(); 
					
					$(this).find('td:nth-child(1) select.paymentType').attr({name:'credit_note_rows['+row_no+'][mode_of_payment]',id:'credit_note_rows-'+row_no+'-mode_of_payment'});
					$(this).find('td:nth-child(2) input.cheque_no').attr({name:'credit_note_rows['+row_no+'][cheque_no]',id:'credit_note_rows-'+row_no+'-cheque_no'});
					$(this).find('td:nth-child(3) input.cheque_date').attr({name:'credit_note_rows['+row_no+'][cheque_date]',id:'credit_note_rows-'+row_no+'-cheque_date'}).datepicker();
				if(type=='Cheque')
					{ 
						$(this).find('td:nth-child(2) input.cheque_no').rules('add','required');
						$(this).find('td:nth-child(3) input.cheque_date').rules('add','required');
					}
					else if(type=='NEFT/RTGS')
					{
						$(this).find('td:nth-child(2) input').rules('remove','required');
						$(this).find('td:nth-child(3) input').rules('remove','required');
					}
				
				});
			}
			
			$('.addRefRow').die().live('click',function(){
				var SelectedTr=$(this).closest('tr.MainTr');
				AddRefRow(SelectedTr);
			});
			
			function AddRefRow(SelectedTr){
				var refTr=$('#sampleForRef tbody tr').clone();
				var due_days=SelectedTr.find('td:nth-child(2) select.ledger option:selected').attr('default_days');
				//console.log(refTr);
				refTr.find('td:nth-child(5) input.dueDays').val(due_days);
				SelectedTr.find('td:nth-child(2) div.window table tbody').append(refTr);
				renameRefRows(SelectedTr);
			}
			
			function renameRefRows(SelectedTr){
				var i=0;
				var ledger_id=SelectedTr.find('td:nth-child(2) select.ledger').val();
				var cr_dr=SelectedTr.find('td:nth-child(1) select.cr_dr option:selected').val();
				if(cr_dr=='Dr'){
					var eqlClassDr=SelectedTr.find('td:nth-child(3) input.debitBox').attr('id');
					var mainAmt=SelectedTr.find('td:nth-child(3) input.debitBox').val();
				}else{
					var eqlClassCr=SelectedTr.find('td:nth-child(4) input.creditBox').attr('id');
					var mainAmt=SelectedTr.find('td:nth-child(4) input.creditBox').val();
				}
				
				SelectedTr.find('input.ledgerIdContainer').val(ledger_id);
				SelectedTr.find('input.companyIdContainer').val(".$company_id.");
				var row_no=SelectedTr.attr('row_no');
				if(SelectedTr.find('td:nth-child(2) div.window table tbody tr').length>0){
				SelectedTr.find('td:nth-child(2) div.window table tbody tr').each(function(){
					$(this).find('td:nth-child(1) input.companyIdContainer').attr({name:'credit_note_rows['+row_no+'][reference_details]['+i+'][company_id]',id:'credit_note_rows-'+row_no+'-reference_details-'+i+'-company_id'});
					$(this).find('td:nth-child(1) input.ledgerIdContainer').attr({name:'credit_note_rows['+row_no+'][reference_details]['+i+'][ledger_id]',id:'credit_note_rows-'+row_no+'-reference_details-'+i+'-ledger_id'});
					$(this).find('td:nth-child(1) select.refType').attr({name:'credit_note_rows['+row_no+'][reference_details]['+i+'][type]',id:'credit_note_rows-'+row_no+'-reference_details-'+i+'-type'});
					var is_select=$(this).find('td:nth-child(2) select.refList').length;
					var is_input=$(this).find('td:nth-child(2) input.ref_name').length;
					if(is_select){
						$(this).find('td:nth-child(2) select.refList').attr({name:'credit_note_rows['+row_no+'][reference_details]['+i+'][ref_name]',id:'credit_note_rows-'+row_no+'-reference_details-'+i+'-ref_name'}).rules('add', 'required');
					}else if(is_input){
						$(this).find('td:nth-child(2) input.ref_name').attr({name:'credit_note_rows['+row_no+'][reference_details]['+i+'][ref_name]',id:'credit_note_rows-'+row_no+'-reference_details-'+i+'-ref_name'}).rules('add', 'required');
						$(this).find('td:nth-child(5) input.dueDays').attr({name:'credit_note_rows['+row_no+'][reference_details]['+i+'][due_days]',id:'credit_note_rows-'+row_no+'-reference_details-'+i+'-due_days'});
					}
					var Dr_Cr=$(this).find('td:nth-child(4) select option:selected').val();
					if(Dr_Cr=='Dr'){
						$(this).find('td:nth-child(3) input').attr({name:'credit_note_rows['+row_no+'][reference_details]['+i+'][debit]',id:'credit_note_rows-'+row_no+'-reference_details-'+i+'-debit'}).rules('add', 'required');;
					}else{
						$(this).find('td:nth-child(3) input').attr({name:'credit_note_rows['+row_no+'][reference_details]['+i+'][credit]',id:'credit_note_rows-'+row_no+'-reference_details-'+i+'-credit'}).rules('add', 'required');;
					}
					i++;
				});
				var total_type=SelectedTr.find('td:nth-child(2) div.window table.refTbl tfoot tr td:nth-child(3) input.total_type').val();
					if(total_type=='Dr'){
					 eqlClass=eqlClassDr;
					}else{
					 eqlClass=eqlClassCr;
					}
				
				SelectedTr.find('td:nth-child(2) div.window table.refTbl tfoot tr td:nth-child(2) input.total')
						.attr({name:'credit_note_rows['+row_no+'][total]',id:'credit_note_rows-'+row_no+'-total'})
						.rules('add', {
							equalTo: '#'+eqlClass,
							messages: {
								equalTo: 'Enter bill wise details upto '+mainAmt+' '+cr_dr
							}
						});
				}
			}
			
			$('.calculate_total').die().live('keyup',function()
			{ 
				 renameMainRows();
			});
			$('.calculation').die().live('keyup',function()
			{ 
				var SelectedTr=$(this).closest('tr.MainTr');
				calculation(SelectedTr);
				
			});
			$('.calculation').die().live('change',function()
			{ 
				var SelectedTr=$(this).closest('tr.MainTr');
				calculation(SelectedTr);
				
			});
			function calculation(SelectedTr)
			{
				var total_debit=0;var total_credit=0; var remaining=0; var i=0;
				SelectedTr.find('td:nth-child(2) div.window table tbody tr').each(function(){
				var Dr_Cr=$(this).find('td:nth-child(4) select option:selected').val();
				//console.log(Dr_Cr);
				var amt= parseFloat($(this).find('td:nth-child(3) input').val());
				if(!amt){amt=0; }
					if(Dr_Cr=='Dr'){
						total_debit=round(total_debit+amt, 2);
						
					}
					else if(Dr_Cr=='Cr'){
						total_credit=round(total_credit+amt, 2);
						//console.log(total_credit);
					}
					
					remaining=round(total_debit-total_credit, 2);
					
					if(remaining>0){
						//console.log(remaining);
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
				renameRefRows(SelectedTr);
					
				i++;
			}
			
			
			ComponentsPickers.init();	
		});
	";
?>
<?php echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom'));  ?>

