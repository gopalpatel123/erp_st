<?php //pr($salesInvoice); exit;
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
 
 
$this->set('title', 'Purchase Return');
$is_interstate=0;

if($supplier_state_id== $state_id){
		$is_interstate=0;
}else{
	$is_interstate=1;
}
?>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			
			<div class="portlet-body">
				<?= $this->Form->create($purchaseReturn,['id'=>'form_sample_2']) ?>
					<div class="row">
						<div class="col-md-6 caption-subject font-green-sharp bold " align="center" style="font-size:16px"><b>PURCHASE INVOICE</b></div>
						<div class="col-md-6 caption-subject font-green-sharp bold " align="center" style="font-size:16px"><b>PURCHASE RETURN</b></div>
					</div><br><br>
					<input type="hidden" name="state_id" class="state_id" value="<?php echo $state_id;?>">
					<input type="hidden" name="supplier_state_id" class="supplier_state_id" value="<?php echo $supplier_state_id;?>">
					<input type="hidden" name="is_interstate" id="is_interstate" value="<?php echo $is_interstate;?>">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label><b>Purchase Invoice Voucher No : </b><?= h('#'.str_pad($PurchaseInvoice->voucher_no, 4, '0', STR_PAD_LEFT)) ?></label>&nbsp;&nbsp;
								
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label><b>Transaction Date</b></label><br>
								<?php  
								echo $PurchaseInvoice->transaction_date;
								?>
							</div>
						</div>
						
						<div class="col-md-2">
								<label><b>Party</b></label><br/>
								<?php echo $PurchaseInvoice->supplier_ledger->name
								?>
						</div>
						
						<div class="col-md-2">
								<label><b>Purchase Account</b></label><br/>
								<?php echo $PurchaseInvoice->purchase_ledger->name
								?>
						</div>
						
						<div class="col-md-2">
								<?php if($NewVoucherNo==0){ ?>
									<label><b>Purchase Return Voucher No : </b><?= h('#'.str_pad($NewVoucherNo+1, 4, '0', STR_PAD_LEFT)) ?></label>
								<?php } else { ?>
									<label><b>Purchase Return Voucher No : </b><?= h('#'.str_pad($NewVoucherNo+1, 4, '0', STR_PAD_LEFT)) ?></label>
								<?php } ?>
						
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label>Transaction Date <span class="required">*</span></label>
									<?php echo $this->Form->input('transaction_date', ['autocomplete'=>'off','type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => date("d-m-Y"),'data-date-start-date'=>$coreVariable['fyValidFrom'],'data-date-end-date'=>$coreVariable['fyValidTo']]); ?>
								
								
							</div>
						</div>
					</div>
					
				   <div class="row">
				  <div class="table-responsive">
						<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;font-size:12px;" width="100%">
							<thead>
								<tr align="center" style="font-size:12px;">
									<th rowspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">Item<label></th>
									<th rowspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">Qty</label></th>
									<th rowspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">Rate</label></th>
									<th  colspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">Discount (%)</label></th>
									<th  colspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">PNF (%)</label></th>
									<th rowspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">Taxable Value</label></td>
									<?php if($supplier_state_id== $state_id){ ?>
											<th colspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px" id="gstDisplay">GST</label></th>
									<?php } else { ?>
												<th colspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px" id="gstDisplay">IGST</label></th>
									<?php } ?>
									
									<th rowspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">Round Off</label></th>
									<th rowspan="2" style="border-right-width:2px; border-right-color:#4db3a2;"><label>Total</label></th>
									<th  rowspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">is Return ?</label></th>
									<th rowspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">Return Quantity</label></th>
									<th rowspan="2" style="text-align:center;"><label  style="text-align:center; font-size:12px">Return Amount</label></th>
									
								</tr>
								<tr>
									<th><div align="center" style="text-align:center; font-size:12px">%</div></th>
									<th><div align="center" style="text-align:center; font-size:12px">Rs</div></th>
									<th><div align="center" style="text-align:center; font-size:12px">%</div></th>
									<th><div align="center" style="text-align:center; font-size:12px">Rs</div></th>
									<th><div align="center" style="text-align:center; font-size:12px">%</div></th>
									<th><div align="center" style="text-align:center; font-size:12px">Rs</div></th>
									
								</tr>
							</thead>
							<tbody id='main_tbody' class="tab">
							 <?php if(!empty($PurchaseInvoice->purchase_invoice_rows))
									 $i=0;		
									 foreach($PurchaseInvoice->purchase_invoice_rows as $purchase_invoice_row)
									 { //pr($PurchaseInvoice->supplier_ledger->name);
							?>
							<tr class="main_tr" class="tab" >
								<td width="15%" align="left">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  attrGet rightAligntextClass','value'=>$purchase_invoice_row->item_id]); 
										
										 echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  purchaseInvoiceRowId rightAligntextClass','value'=>$purchase_invoice_row->id]); 
										
										echo $purchase_invoice_row->item->name;
										?>
								</td>
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  quantity rightAligntextClass','value'=>$purchase_invoice_row->quantity]); 
										echo $purchase_invoice_row->quantity;
										?>
								</td>	
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  rate rightAligntextClass','value'=>$purchase_invoice_row->rate]); 
										echo $purchase_invoice_row->rate;
										?>
								</td>	
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  discount rightAligntextClass','value'=>$purchase_invoice_row->discount_percentage]); if(!empty($purchase_invoice_row->discount_percentage)) {
										echo $purchase_invoice_row->discount_percentage; }
										?>
								</td>	
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  discountAmount rightAligntextClass','value'=>$purchase_invoice_row->discount_amount,'total_dis'=>$purchase_invoice_row->discount_amount]); 
										if(!empty($purchase_invoice_row->discount_amount)) {
										echo $purchase_invoice_row->discount_amount;}
										?>
								</td>	
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  pnf rightAligntextClass','value'=>$purchase_invoice_row->pnf_percentage]); 
										if($purchase_invoice_row->pnf_percentage>0) {
										echo $purchase_invoice_row->pnf_percentage; }
										?>
								</td>	
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'text','label' => false,'class' => 'form-control input-sm  pnfAmount calculation rightAligntextClass','value'=>$purchase_invoice_row->pnf_amount,'total_pnf_amt'=>$purchase_invoice_row->pnf_amount]); if(!empty($purchase_invoice_row->pnf_amount)) {
										//echo $purchase_invoice_row->pnf_amount;
										}
										?>
								</td>	
								
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  taxableValue rightAligntextClass','value'=>$purchase_invoice_row->taxable_value]); 
										echo $purchase_invoice_row->taxable_value;
										?>
								</td>

								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm item_gst_figure_id numberOnly','placeholder'=>'','type'=>'hidden','value'=>$purchase_invoice_row->item_gst_figure_id]);
											
										echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm gst_figure_id numberOnly','style'=>'text-align:right','placeholder'=>'','type'=>'hidden','value'=>$purchase_invoice_row->item_gst_figure_id]);
										
										echo $purchase_invoice_row->gst_percentage;
										?>
								</td>	
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  gstValue rightAligntextClass','value'=>$purchase_invoice_row->gst_value]); 
										echo $purchase_invoice_row->gst_value;
										?>
								</td>
								<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  roundOff rightAligntextClass','value'=>$purchase_invoice_row->round_off]); 

										echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  actroundOff rightAligntextClass','value'=>$purchase_invoice_row->round_off]); if(!empty($purchaseInvoiceRows->round_off)) {
										echo $purchase_invoice_row->round_off; }
										?>
								</td>	
								<td width="5%" align="center" style="border-left-width:2px; border-right-color:#4db3a2;">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm  netAmount rightAligntextClass','value'=>$purchase_invoice_row->net_amount]); 
										echo $purchase_invoice_row->net_amount;
										?>
								</td>
								<td valign="top" width="5%" align="center" style="border-left-width:2px; border-left-color:#4db3a2; margin-top:-10px">
								<?php if($purchase_invoice_row->quantity-@$purchase_return_qty[@$purchase_invoice_row->id] > 0) {?>
									<label style="margin-top:-10px"><?php echo $this->Form->input('check', ['label' => false,'type'=>'checkbox','class'=>'rename_check','value' => @$purchase_invoice_row->item->id]); ?></label>
								<?php }  ?>
								</td>

								<td width="8%" align="center">
									<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm returnQty calculation rightAligntextClass numberOnly','placeholder'=>'Return Quantity',  'tabindex'=>'-1','type'=>'text','maxqt'=>$purchase_invoice_row->quantity-@$purchase_return_qty[@$purchase_invoice_row->id],'minqt'=>0.01]);
											
											//echo @$purchase_return_qty[@$purchase_invoice_row->id];
									?>	<span align="center" valign="top" style="font-size:11px; margin-top:none">Max Quantity:- <?php echo $purchase_invoice_row->quantity-@$purchase_return_qty[@$purchase_invoice_row->id];?></span>
								</td>
								<td width="8%" align="center">
								<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm  calculation returnAmt','required'=>'required', 'readonly'=>'readonly','placeholder'=>'Taxable Value', 'value'=>0, 'tabindex'=>'-1']); ?>
								</td>
								
							
							</tr>
							<?php $i++; } ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="12" style="border-right-width:2px; border-right-color:#4db3a2;"></td>
									<td  colspan="2"  align="right"><b>Total</b>
									</td>
									<td>
									<?php echo $this->Form->input('amount_before_tax', ['label' => false,'class' => 'form-control input-sm amount_before_tax rightAligntextClass', 'readonly'=>'readonly','placeholder'=>'', 'tabindex'=>'-1']); ?>	
									</td>
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

<!-- BEGIN PAGE LEVEL STYLES -->
	<!-- BEGIN COMPONENTS PICKERS -->
	<?php echo $this->Html->css('/assets/global/plugins/clockface/css/clockface.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<!-- END COMPONENTS PICKERS -->

	<?php echo $this->Html->script('/assets/global/plugins/jquery-validation/js/jquery.validate.min.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	<!-- BEGIN COMPONENTS DROPDOWNS -->
	<?php echo $this->Html->css('/assets/global/plugins/bootstrap-select/bootstrap-select.min.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/select2/select2.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<?php echo $this->Html->css('/assets/global/plugins/jquery-multi-select/css/multi-select.css', ['block' => 'PAGE_LEVEL_CSS']); ?>
	<!-- END COMPONENTS DROPDOWNS -->
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
	<!-- BEGIN COMPONENTS PICKERS -->
	<?php echo $this->Html->script('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	
	<?php echo $this->Html->script('/assets/global/plugins/clockface/js/clockface.js', ['block' => 'PAGE_LEVEL_PLUGINS_JS']); ?>
	
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
					var amount_before_tax  = parseFloat($('.amount_before_tax').val());
					if(!amount_before_tax || amount_before_tax==0){
						alert('Error: zero amount invoice can not be generated.');
						return false;
					}else if(amount_before_tax < 0){
						alert('Error: Minus amount invoice can not be generated.');
						return false;
					}
					
					if(confirm('Are you sure you want to submit!'))
							{
								form1[0].submit();
								$('.submit').attr('disabled','disabled');
								$('.submit').text('Submiting...');
								return true;
							}
							else
							{
								return false;
							}
					
					
					$('.submit').attr('disabled','disabled');
					$('.submit').text('Submiting...');
					return true;
					

                }
			});
	
	$('.rename_check').die().live('click',function() {
		rename_rows();
		//forward_total_amount();
    });
rename_rows();
function rename_rows()
	{
		var i=0;
		$('#main_table tbody#main_tbody tr.main_tr').each(function(){ 
			var val=$(this).find('input[type=checkbox]:checked').val();
		if(val){ 
			
			$(this).find('td:nth-child(1) input.attrGet').attr({name:'purchase_return_rows['+i+'][item_id]',id:'purchase_return_rows['+i+'][item_id]'});
			$(this).find('td:nth-child(1) input.purchaseInvoiceRowId').attr({name:'purchase_return_rows['+i+'][purchase_invoice_row_id]',id:'purchase_return_rows['+i+'][purchase_invoice_row_id]'});
			$(this).find('.rate').attr({name:'purchase_return_rows['+i+'][rate]',id:'purchase_return_rows['+i+'][rate]'});
			$(this).find('.discount').attr({name:'purchase_return_rows['+i+'][discount_percentage]',id:'purchase_return_rows['+i+'][discount_percentage]'});
			$(this).find('.discountAmount').attr({name:'purchase_return_rows['+i+'][discount_amount]',id:'purchase_return_rows['+i+'][discount_amount]'});
			$(this).find('.pnf').attr({name:'purchase_return_rows['+i+'][pnf_percentage]',id:'purchase_return_rows['+i+'][pnf_percentage]'});
			$(this).find('.pnfAmount').attr({name:'purchase_return_rows['+i+'][pnf_amount]',id:'purchase_return_rows['+i+'][pnf_amount]'});
			$(this).find('.taxableValue').attr({name:'purchase_return_rows['+i+'][taxable_value]',id:'purchase_return_rows['+i+'][taxable_value]'}).attr('readonly', true);
			
			$(this).find('.item_gst_figure_id').attr({name:'purchase_return_rows['+i+'][item_gst_figure_id]',id:'purchase_return_rows['+i+'][item_gst_figure_id]'}).attr('readonly', true);
			
			$(this).find('.gst_figure_id').attr({name:'purchase_return_rows['+i+'][gst_percentage]',id:'purchase_return_rows['+i+'][gst_percentage]'}).attr('readonly', true);
			$(this).find('.gstValue').attr({name:'purchase_return_rows['+i+'][gst_value]',id:'purchase_return_rows['+i+'][gst_value]'}).attr('readonly', true);
			$(this).find('.roundOff').attr({name:'purchase_return_rows['+i+'][round_off]',id:'purchase_return_rows['+i+'][round_off]'});
			
			
			var max_qty=$(this).find('.returnQty').attr('maxqt');
			//alert(max_qty);
			//$(this).find('.quantity').attr({name:'purchase_return_rows['+i+'][quantity]',id:'purchase_return_rows['+i+'][quantity]'});
			
			var min_qty=0.000000;
			$(this).find('.returnQty').attr({name:'purchase_return_rows['+i+'][quantity]',id:'purchase_return_rows['+i+'][quantity]'}).removeAttr('readonly');
			$(this).find('.returnAmt').attr({name:'purchase_return_rows['+i+'][net_amount]',id:'purchase_return_rows['+i+'][net_amount]'});
		i++;
			$(this).css('background-color','#fffcda');
		}else{ 
			$(this).find('td:nth-child(1) input.attrGet').attr({name:'q',id:'q'});
			$(this).find('.purchaseInvoiceRowId').attr({name:'q',id:'q'});
			$(this).find('.quantity').attr({name:'q',id:'q'});
			$(this).find('.rate').attr({name:'q',id:'q'});
			$(this).find('.discount').attr({name:'q',id:'q'});
			$(this).find('.discountAmount').attr({name:'q',id:'q'});
			$(this).find('.pnf').attr({name:'q',id:'q'});
			$(this).find('.pnfAmount').attr({name:'q',id:'q'});
			$(this).find('.taxableValue').attr({name:'q',id:'q'});
			$(this).find('.item_gst_figure_id').attr({name:'q',id:'q'});
			$(this).find('.gst_figure_id').attr({name:'q',id:'q'});
			$(this).find('.gstValue').attr({name:'q',id:'q'});
			$(this).find('.roundOff').attr({name:'q',id:'q'});
			$(this).find('.returnAmt').attr({name:'q',id:'q'});
			$(this).find('.returnQty').attr({name:'q',id:'q', readonly:'readonly'}).val('');
			$(this).css('background-color','#FFF');
		}
		});
		//forward_total_amount();
	}
	
	$('.returnQty').die().live('blur',function()
	{ 
		forward_total_amount();
	});
	forward_total_amount();
	
	$('.calculation').die().live('keyup',function()
	{
		forward_total_amount();
	});
	
	function forward_total_amount() 
		{   	
			var total_dis=0;
			var total_pnf=0;
			var total_taxable=0;
			var total_gst=0;
			var total_round=0;
			var total_amt=0;
			
			$('#main_table tbody#main_tbody tr.main_tr').each(function()
			{ 
				var chkquanity1=$(this).find('.returnQty').attr('maxqt');
				var chkquanity2=parseFloat($(this).closest('tr').find('.returnQty').val());
				var total_quantity =parseFloat($(this).closest('tr').find('.quantity ').val());
			
			if(chkquanity2 != 0){
				if(chkquanity2>chkquanity1)
				{
					alert('Please enter a value less than or equal to quantity '+chkquanity1);
					$(this).closest('tr').find('.returnQty').val(''); 
				}
				
				if(chkquanity2==0)
				{
					alert('Please enter a value greater than or equal to quantity');
					$(this).closest('tr').find('.returnQty').val(''); 
				}
			}
			
			
			var val=$(this).find('input[type=checkbox]:checked').val();
			if(val){
			    var quantity=parseFloat($(this).closest('tr').find('.returnQty').val());
			    var rate=parseFloat($(this).closest('tr').find('.rate').val());
				quantity=round(quantity,2);
				rate=round(rate,2);
				var amount=quantity*rate;
				amount=round(amount,2);
			    var discount=parseFloat($(this).closest('tr').find('.discount').val());
				
				var disAmt=0;
				var total_dis_amt=parseFloat($(this).find('.discountAmount').attr('total_dis'));
				var per_qt_dis=(quantity/total_quantity)*total_dis_amt;
				//alert(per_qt_dis);
				$(this).closest('tr').find('.discountAmount').val(per_qt_dis.toFixed(2));
				
				disAmt=parseFloat($(this).closest('tr').find('.discountAmount').val());
				
				/* var pnfAmt1=parseFloat($(this).closest('tr').find('.pnfAmount').attr('total_pnf_amt'));
				var per_qt_pnf=(quantity/total_quantity)*pnfAmt1;
				//alert(per_qt_dis);
				$(this).closest('tr').find('.pnfAmount').val(per_qt_pnf.toFixed(2)); */
				amountAfterDiscount=amount-disAmt;
				
				pnfAmt=parseFloat($(this).closest('tr').find('.pnfAmount').val());
				
				var Pnfper=(pnfAmt/amountAfterDiscount)*100;
				$(this).closest('tr').find('.pnf').val(Pnfper.toFixed(2));
				
				
				amount=round(amount,2);
				disAmt=round(disAmt,2);
				total_dis+=disAmt;
				total_pnf+=pnfAmt;
				amountAfterDiscount=amount-disAmt;
				
				//alert(total_quantity);
				taxableAmt=(amount-disAmt)+pnfAmt;
				taxableAmt=round(taxableAmt,2);
				
				$(this).closest('tr').find('.taxableValue').val(taxableAmt.toFixed(2));
				total_taxable=total_taxable+taxableAmt;
				var gstTax=parseFloat($(this).closest('tr').find('.gst_figure_id').val());
				if(!gstTax){ 
					var gstAmt=0;
					$(this).closest('tr').find('.gstValue').val(gstAmt.toFixed(2));
				}else{
					var supplier_state_id=$('.supplier_state_id').val();
					var state_id=$('.state_id').val();
					
					if(supplier_state_id!=state_id)
					{ 
						gstTax=round(gstTax,2);
						//alert(supplier_state_id);
						
						var amt2=(taxableAmt*gstTax)/100;
						$(this).closest('tr').find('.gstValue').val(amt2.toFixed(2));
						var gstamt1=parseFloat($(this).closest('tr').find('.gstValue').val());
						total_gst=total_gst+gstamt1;
					}else{ 
						gstTax=round(gstTax,2);
						gstTax=gstTax/2;
						gstTax=round(gstTax,2);
						var gstAmt1=(taxableAmt*gstTax)/100;
						var gstAmt2=(taxableAmt*gstTax)/100;
						gstAmt1=round(gstAmt1,2);
						gstAmt2=round(gstAmt2,2);
						
						amt2=gstAmt1+gstAmt2;
						amt2=round(amt2,2);
						
						$(this).closest('tr').find('.gstValue').val(amt2.toFixed(2));
						var gstamt11=parseFloat($(this).closest('tr').find('.gstValue').val());
						total_gst=total_gst+gstamt11;
					}
					
					
				}
				
				var totalAmount=taxableAmt+amt2;
				var Actualquantity=parseFloat($(this).closest('tr').find('.quantity').val());
				 var round_of_amt=parseFloat($(this).closest('tr').find('.actroundOff').val());
				 if(isNaN(round_of_amt)){
					 var round_of=0;
					  $(this).closest('tr').find('.round_of').val(round_of.toFixed(2));
				 }else{
					var round_of=(quantity/Actualquantity)*round_of_amt;
					 // var round_of=round_of_amt;
				 }
				// alert(round_of);
				total_round=total_round+round_of;
				$(this).closest('tr').find('.roundOff').val(round_of.toFixed(2));
				var totalAmountAfterRound=totalAmount+round_of;
				//$(this).closest('tr').find('.netAmount').val(parseFloat(totalAmountAfterRound).toFixed(2));
				var netAmount =parseFloat($(this).closest('tr').find('.netAmount ').val());
				var totalAmountReturn=0; 
				if(isNaN(quantity) || (quantity<=0)){
					var totalAmountReturn=0; 
					total_amt=total_amt+totalAmountReturn;
					$(this).closest('tr').find('.returnQty').val(parseFloat(0).toFixed(2));
					$(this).closest('tr').find('.returnAmt').val(parseFloat(totalAmountReturn).toFixed(2));
				 }else{
					 totalAmountAfterRound=round(totalAmountAfterRound,2);
					 total_amt=total_amt+totalAmountAfterRound;
					$(this).closest('tr').find('.returnAmt').val(parseFloat(totalAmountAfterRound).toFixed(2));
				 }
				}
			});
			 total_amt=round(total_amt,2);
			$('.amount_before_tax').val(parseFloat(total_amt).toFixed(2));
			 
			
			//rename_rows();
		}
		
	function checkValidation() 
	{ 
		var total_amt  = parseFloat($('.total_amt').val());
		if(!total_amt || total_amt==0){
			alert('Error: zero amount invoice can not be generated.');
			return false;
		}
		
		if(!total_amt || total_amt < 0){
			alert('Error: Minus amount invoice can not be generated.');
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
		
	
	}
		

		
	
	
	
	});
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>