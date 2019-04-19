<?php //pr($salesInvoice); exit;
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Purchase Invoices');


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
				<?= $this->Form->create($purchaseInvoice,['onsubmit'=>'return checkValidation()']) ?>
					<div class="row">
						<div class="col-md-12 caption-subject font-green-sharp bold " align="center" style="font-size:16px"><b>PURCHASE INVOICE EDIT</b></div>
						
					</div><br><br>
					
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label><b> Voucher No :</b></label>&nbsp;&nbsp;<br>
								<?= h('#'.str_pad($purchaseInvoice->voucher_no, 4, '0', STR_PAD_LEFT)) ?>
							</div>
						</div>
						
						<input type="hidden" name="state_id" class="state_id" value="<?php echo $state_id;?>">
						<input type="hidden" name="is_interstate" id="is_interstate" value="<?php echo $is_interstate;?>">
						<div class="col-md-3">
								<label>Supplier</label>
								<?php echo $this->Form->control('q',['class'=>'form-control input-sm supplier_state_id ','label'=>false,'type'=>'hidden','value'=>$supplier_state_id]);
									 
									echo $this->Form->control('supplier_ledger_id',['class'=>'form-control input-sm supplier_ledger select2me','label'=>false, 'options' => $partyOptions,'required'=>'required','disabled']);
								?>
						</div>
						
						<div class="col-md-3">
								<label>Purchase Account</label>
								<?php echo $this->Form->control('purchase_ledger_id',['empty'=>'-Select Purchase-', 'class'=>'form-control input-sm supplier_ledger_id select2me','label'=>false, 'options' => $Accountledgers,'required'=>'required','value'=>$purchaseInvoice->purchase_ledger_id]);
								?>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Transaction Date <span class="required">*</span></label>
								<?php echo $this->Form->input('transaction_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => $purchaseInvoice->transaction_date,'data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo]]); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label>Narration </label>
								<?php echo $this->Form->control('narration',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'Narration','rows'=>'4','value'=>$purchaseInvoice->narration]); ?>
							</div>
						</div>
					</div>
				   <div class="row">
				  <div class="table-responsive">
								<table id="main_table" class="table table-condensed table-bordered" style="height: 24px; padding: 0px 0px;font-size: 12px;" width="100%">
								<thead>
								<tr align="center">
									<th rowspan="2" style="text-align:center;"><label>Item<label></td>
									<th rowspan="2" style="text-align:center;"><label>Qty<label></td>
									<th rowspan="2" style="text-align:center;"><label>Rate<label></td>
									<th  colspan="2" style="text-align:center;"><label align="center">Discount (%)</label></th>
									<th  colspan="2" style="text-align:center;"><label align="center">PNF (%)</label></th>
									<th rowspan="2" style="text-align:center;"><label>Taxable Value<label></td>
									<th colspan="2" style="text-align:center;"><label id="gstDisplay">GST<label></th>
									<th rowspan="2" style="text-align:center;"><label>Round off<label></td>
									<th rowspan="2" style="text-align:center;"><label>Total<label></td>
								</tr>
								<tr>
									<th><div align="center">%</div></th>
									<th><div align="center">Rs</div></th>
									<th><div align="center">%</div></th>
									<th><div align="center">Rs</div></th>
									<th><div align="center">%</div></th>
									<th><div align="center">Rs</div></th>
									
								</tr>
								</thead>
								<tbody id='main_tbody' class="tab">
								 <?php $i=0;
								 foreach($purchaseInvoice->purchase_invoice_rows as $purchase_invoice_row)
								 {
									//pr($purchase_invoice_row); exit;
							     ?>
								<tr class="main_tr" class="tab">
									<td width="15%" align="left">
									<input type="hidden" name="q" class="purchase_invoice_row_id calculation" value="<?php echo $purchase_invoice_row->id; ?>">
									
									<input type="hidden" name="q" class="attrGet calculation" value="<?php echo $purchase_invoice_row->item_id; ?>">
									<?php echo $purchase_invoice_row->item->name; ?></td>
									<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm calculation quantity rightAligntextClass','required'=>'required','placeholder'=>'Quantity', 'value'=>$purchase_invoice_row->quantity]); 
										echo $purchase_invoice_row->quantity;
										?>
									</td>
									<td width="8%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'text','label' => false,'class' => 'form-control input-sm  rate numberOnly rightAligntextClass','value'=>$purchase_invoice_row->rate]); 
										//echo $purchase_invoice_row->rate;
										?>
									</td>
									<td  width="6%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm discount numberOnly','placeholder'=>'Discount','style'=>'text-align:right','type'=>'text','value'=>$purchase_invoice_row->discount_percentage]);
										?>	
									</td>
									<td  width="8%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm numberOnly discountAmount','type'=>'text','style'=>'text-align:right','value'=>$purchase_invoice_row->discount_amount]);
										?>	
									</td>
									<td  width="6%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm pnf numberOnly','placeholder'=>'PNF','style'=>'text-align:right','type'=>'text','value'=>$purchase_invoice_row->pnf_percentage]);
										?>	
									</td>
									<td  width="8%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm numberOnly pnfAmount','type'=>'text','style'=>'text-align:right','value'=>$purchase_invoice_row->pnf_amount]);
										?>	
									</td>
									
									<td  width="10%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm taxableValue','style'=>'text-align:right','type'=>'text','value'=>$purchase_invoice_row->taxable_value]);
										?>	
									</td>
									
									<td  width="6%" align="center">
										<?php
											echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm item_gst_figure_id numberOnly','placeholder'=>'','type'=>'hidden','value'=>$purchase_invoice_row->item->FirstGstFigures->id]);
											
											echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm gst_figure_id numberOnly','style'=>'text-align:right','placeholder'=>'','type'=>'text','value'=>$purchase_invoice_row->item->FirstGstFigures->tax_percentage]);
										?>	
									</td>
									<td  width="8%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm gstValue','type'=>'text','style'=>'text-align:right','value'=>$purchase_invoice_row->gst_value]);
										?>	
									</td>
									<td  width="7%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm roundOff','placeholder'=>'','style'=>'text-align:right','type'=>'text','value'=>$purchase_invoice_row->round_off]);
										?>	
									</td>
									<td  width="10%" align="center">
										<?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm netAmount','type'=>'text','style'=>'text-align:right','value'=>$purchase_invoice_row->net_amount]);
										?>	
									</td>
								
															
								
							</tr>
								<?php $i++; } ?>
								<tr>
									<td  colspan="3" align="right">
										<?php echo "Total";?>	
									</td>
									<td  colspan="2" align="right" >
										<?php echo $this->Form->input('total_discount_amt', ['style'=>'text-align:center','label' => false,'class' => 'form-control input-sm total_discount_amt','type'=>'text','style'=>'text-align:right','readonly']);	 ?>
									</td>
									<td  colspan="2" align="right">
										<?php echo $this->Form->input('total_pnf_amt', ['style'=>'text-align:center','readonly','label' => false,'class' => 'form-control input-sm total_pnf_amt','style'=>'text-align:right','type'=>'text']);	 ?>
									</td>
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_taxable_value', ['style'=>'text-align:center','readonly','label' => false,'class' => 'form-control input-sm total_taxable_value','style'=>'text-align:right','type'=>'text']);	 ?>
									</td>
									<td  colspan="2" align="right">
										<?php echo $this->Form->input('total_gst_value', ['style'=>'text-align:center','readonly','label' => false,'class' => 'form-control input-sm total_gst_value','style'=>'text-align:right','type'=>'text']);	 ?>
									</td>
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_round_amount', ['style'=>'text-align:center','readonly','label' => false,'class' => 'form-control input-sm total_round_amount','style'=>'text-align:right','type'=>'text']);	 ?>
									</td>
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_amount', ['style'=>'text-align:center','readonly',	'label' => false,'class' => 'form-control input-sm total_amount','style'=>'text-align:right','type'=>'text']);	 ?>
									</td>
								</tr>
								</tbody>
								<tfoot>
									
						
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
	
			var supplier_state_id=$('.supplier_state_id').val();
			var state_id=$('.state_id').val();
			if(supplier_state_id!=state_id)
			{
			if(supplier_state_id > 0)
			{
				$('#gstDisplay').html('IGST');
				$('#is_interstate').val('1');
			}
			else if(!supplier_state_id)
			{
				$('#gstDisplay').html('GST');
				$('#is_interstate').val('0');
			}
			else if(supplier_state_id==0)
			{
				$('#gstDisplay').html('GST');
				$('#is_interstate').val('0');
			}
			}
			else if(supplier_state_id==state_id){
				$('#gstDisplay').html('GST');
				$('#is_interstate').val('0');
			}
			//$(this).closest('tr').find('.output_igst_ledger_id').val(output_igst_ledger_id);
			forward_total_amount();
		
	
	
	
	rename_rows();
	function rename_rows()
	{
		var i=0;
		$('#main_table tbody#main_tbody tr.main_tr').each(function(){ 
			$(this).find('td:nth-child(1) input.purchase_invoice_row_id').attr({name:'purchase_invoice_rows['+i+'][id]',id:'purchase_invoice_rows['+i+'][id]'});
			$(this).find('td:nth-child(1) input.attrGet').attr({name:'purchase_invoice_rows['+i+'][item_id]',id:'purchase_invoice_rows['+i+'][item_id]'});
			
			$(this).find('.quantity').attr({name:'purchase_invoice_rows['+i+'][quantity]',id:'purchase_invoice_rows['+i+'][quantity]'});
			$(this).find('.rate').attr({name:'purchase_invoice_rows['+i+'][rate]',id:'purchase_invoice_rows['+i+'][rate]'});
			$(this).find('.discount').attr({name:'purchase_invoice_rows['+i+'][discount_percentage]',id:'purchase_invoice_rows['+i+'][discount_percentage]'});
			$(this).find('.discountAmount').attr({name:'purchase_invoice_rows['+i+'][discount_amount]',id:'purchase_invoice_rows['+i+'][discount_amount]'});
			$(this).find('.pnf').attr({name:'purchase_invoice_rows['+i+'][pnf_percentage]',id:'purchase_invoice_rows['+i+'][pnf_percentage]'});
			$(this).find('.pnfAmount').attr({name:'purchase_invoice_rows['+i+'][pnf_amount]',id:'purchase_invoice_rows['+i+'][pnf_amount]'});
			$(this).find('.taxableValue').attr({name:'purchase_invoice_rows['+i+'][taxable_value]',id:'purchase_invoice_rows['+i+'][taxable_value]'}).attr('readonly', true);
			
			$(this).find('.item_gst_figure_id').attr({name:'purchase_invoice_rows['+i+'][item_gst_figure_id]',id:'purchase_invoice_rows['+i+'][item_gst_figure_id]'}).attr('readonly', true);
			$(this).find('.gst_figure_id').attr({name:'purchase_invoice_rows['+i+'][gst_percentage]',id:'purchase_invoice_rows['+i+'][gst_percentage]'}).attr('readonly', true);
			$(this).find('.gstValue').attr({name:'purchase_invoice_rows['+i+'][gst_value]',id:'purchase_invoice_rows['+i+'][gst_value]'}).attr('readonly', true);
			$(this).find('.roundOff').attr({name:'purchase_invoice_rows['+i+'][round_off]',id:'purchase_invoice_rows['+i+'][round_off]'});
			$(this).find('.netAmount').attr({name:'purchase_invoice_rows['+i+'][net_amount]',id:'purchase_invoice_rows['+i+'][net_amount]'}).attr('readonly', true);
		i++;
		});
	}
	
	$('.rate').die().live('blur',function()
	{
		forward_total_amount();
	});
	$('.discount').die().live('blur',function()
	{
		var quantity=parseFloat($(this).closest('tr').find('.quantity').val());
			    var rate=parseFloat($(this).closest('tr').find('.rate').val());
				var amount=quantity*rate;
			    var discount=parseFloat($(this).closest('tr').find('.discount').val());
				if(!discount){discount=0;}
				//quantity=round(quantity,2);
				//rate=round(rate,2);
				//amount=round(amount,2);
				discount=round(discount,3);
				var disAmt=0;
				
				if(isNaN(discount)){ 
					disAmt=round(disAmt,2);
					$(this).closest('tr').find('.discountAmount').val(disAmt);
					$(this).closest('tr').find('.discount').val(disAmt);
				}else{
					var disAmt=(amount*discount)/100;
					
					disAmt=round(disAmt,2);
					$(this).closest('tr').find('.discountAmount').val(disAmt);
					//total_dis=total_dis+disAmt;
					
				}
		forward_total_amount();
	});
	
	$('.pnf').die().live('blur',function()
	{ 
		var quantity=parseFloat($(this).closest('tr').find('.quantity').val());
		var rate=parseFloat($(this).closest('tr').find('.rate').val());
		var amount=quantity*rate;
		var pnf=parseFloat($(this).closest('tr').find('.pnf').val());
				if(!pnf){pnf=0;}
				if(isNaN(pnf)){ 
					var pnfAmt=0;
					pnfAmt=round(pnfAmt,2);
					$(this).closest('tr').find('.pnfAmount').val(pnfAmt);
					$(this).closest('tr').find('.pnf').val(pnfAmt);
				}else{
					pnf=round(pnf,2);
					var pnfAmt=(amount*pnf)/100;
					pnfAmt=round(pnfAmt,2);
					$(this).closest('tr').find('.pnfAmount').val(pnfAmt);
					//total_pnf=total_pnf+pnfAmt;
				}
		forward_total_amount();
	});
	$('.roundOff').die().live('blur',function()
	{
		forward_total_amount();
	});
	
	forward_total_amount();

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
			    var quantity=parseFloat($(this).closest('tr').find('.quantity').val());
			    var rate=parseFloat($(this).closest('tr').find('.rate').val());
				var amount=quantity*rate;
			    var discount=parseFloat($(this).closest('tr').find('.discount').val());
				var disAmt=0;
				disAmt=parseFloat($(this).closest('tr').find('.discountAmount').val());
				var pnfAmt=parseFloat($(this).closest('tr').find('.pnfAmount').val());
				amount=round(amount,2);
				disAmt=round(disAmt,2);
				total_dis+=disAmt;
				total_pnf+=pnfAmt;
				amountAfterDiscount=amount-disAmt;
				
				taxableAmt=(amount-disAmt)+pnfAmt;
				$(this).closest('tr').find('.taxableValue').val(taxableAmt.toFixed(2));
				total_taxable=total_taxable+taxableAmt;
				var gstTax=parseFloat($(this).closest('tr').find('.gst_figure_id').val());
				if(!gstTax){ 
					var gstAmt=0;
					$(this).closest('tr').find('.gstValue').val(gstAmt.toFixed(2));
				}else{
					//var supplier_state_id =($('.supplier_ledger_id ').find('option:selected').attr('state_id'));
					var supplier_state_id=$('.supplier_state_id').val();
					var state_id=$('.state_id').val();
					if(supplier_state_id!=state_id)
					{ 
						var amt2=(taxableAmt*gstTax)/100;
						amt2=round(amt2,2);
						$(this).closest('tr').find('.gstValue').val(amt2);
						var gstamt1=parseFloat($(this).closest('tr').find('.gstValue').val());
						total_gst=total_gst+gstamt1;
					}else{ 
						gstTax=gstTax/2;
						var gstAmt1=(taxableAmt*gstTax)/100;
						var gstAmt2=(taxableAmt*gstTax)/100;
						
						gstAmt1=round(gstAmt1,2);
						gstAmt2=round(gstAmt2,2);
						
						amt2=gstAmt1+gstAmt2;
						amt2=round(amt2,2);
						$(this).closest('tr').find('.gstValue').val(amt2);
						var gstamt11=parseFloat($(this).closest('tr').find('.gstValue').val());
						total_gst=total_gst+gstamt11;
					}
					
					
				}
				
				var totalAmount=taxableAmt+amt2;
				
				 var round_of_amt=parseFloat($(this).closest('tr').find('.roundOff').val());
				 if(isNaN(round_of_amt)){
					 var round_of=0;
					 $(this).closest('tr').find('.round_of').val(round_of.toFixed(2));
				 }else{
					  var round_of=round_of_amt;
				 }
				 
				total_round=total_round+round_of;
				$(this).closest('tr').find('.roundOff').val(round_of.toFixed(2));
				var totalAmountAfterRound=totalAmount+round_of;
				total_amt=total_amt+totalAmountAfterRound;
				$(this).closest('tr').find('.netAmount').val(parseFloat(totalAmountAfterRound).toFixed(2));
			});
			$('.total_discount_amt').val(total_dis.toFixed(2));
			$('.total_pnf_amt').val(total_pnf.toFixed(2));
			$('.total_taxable_value').val(total_taxable.toFixed(2));
			$('.total_gst_value').val(total_gst.toFixed(2));
			$('.total_round_amount').val(total_round.toFixed(2));
			$('.total_amount').val(parseFloat(total_amt).toFixed(2));
			
			rename_rows();
		}
		
	
	$('.pnfAmount').die().live('blur',function()
	{
		var quantity=parseFloat($(this).closest('tr').find('.quantity').val());
			var rate=parseFloat($(this).closest('tr').find('.rate').val());
			var amount=quantity*rate;
			//var discountAmt=parseFloat($(this).closest('tr').find('.discountAmount').val());
				var pnfAmt=parseFloat($(this).closest('tr').find('.pnfAmount').val());
				if(isNaN(pnfAmt)){ 
					var pnfPer=0;
					var pnfAmt=0;
					$(this).closest('tr').find('.pnf').val(pnfPer.toFixed(2));
					$(this).closest('tr').find('.pnfAmount').val(pnfAmt.toFixed(2));
				}else{
					var pnfPer=(100*pnfAmt)/amount;
					pnfPer=round(pnfPer,3);
					//var pnfAmt=(amountAfterDiscount*pnf)/100;
					$(this).closest('tr').find('.pnf').val(pnfPer);
				}
		forward_total_amount();
	});
	
	$('.discountAmount').die().live('blur',function()
	{	
			var quantity=parseFloat($(this).closest('tr').find('.quantity').val());
			var rate=parseFloat($(this).closest('tr').find('.rate').val());
			var amount=quantity*rate;
			var discountAmt=parseFloat($(this).closest('tr').find('.discountAmount').val());
				if(isNaN(discountAmt)){ 
					var dis=0;
					var discountAmt=0;
					$(this).closest('tr').find('.discount').val(dis.toFixed(2));
					$(this).closest('tr').find('.discountAmount').val(discountAmt.toFixed(2));
					//total_dis=total_dis+discountAmt;
				}else{
					var dis=(100*discountAmt)/amount;
					dis=round(dis,3);
					
					$(this).closest('tr').find('.discount').val(dis);
					//total_dis=total_dis+discountAmt;
				}
		forward_total_amount();
		
	});
	
	
		function checkValidation() 
	{  
		var total_amount  = parseFloat($('.total_amount').val());
		if(!total_amount || total_amount==0){
			alert('Error: zero amount invoice can not be generated.');
			return false;
		}
		
		if(!total_amount || total_amount < 0){
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
		
	
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>