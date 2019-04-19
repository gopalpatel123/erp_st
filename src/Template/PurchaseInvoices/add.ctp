<?php if($supplier_status=="True"){ ?>
	
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject bold ">Please Select Supplier In GRN</span>
				</div>
			</div>
		</div>
	</div>
</div>
	
<?php }  else{ ?>

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
						<div class="col-md-12 caption-subject font-green-sharp bold " align="center" style="font-size:16px"><b>PURCHASE INVOICE</b></div>
						
					</div><br><br>
					
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label><b>GRN Voucher No :</b></label>&nbsp;&nbsp;<br>
								<?= h('#'.str_pad($Grns->voucher_no, 4, '0', STR_PAD_LEFT)) ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>GRN Transaction Date </label><br/>
								<?php echo $Grns->transaction_date; ?>
							</div>
						</div>
						
						<div class="col-md-3">
								<label>Supplier</label>
								<?php
									 echo $this->Form->control('q',['class'=>'form-control input-sm supplier_state_id ','label'=>false,'type'=>'hidden','value'=>$supplier_state_id]);
									echo $this->Form->control('supplier_ledger_id',['class'=>'form-control input-sm supplier_ledger select2me','label'=>false, 'options' => $partyOptions,'required'=>'required','value'=>$supplier_ledger_id,'disabled']);
								?>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Reference No</label><br/>
								<?php
									 echo $Grns->reference_no;
								?>
								</div>
						</div>
					</div><br>
						<input type="hidden" name="state_id" class="state_id" value="<?php echo $state_id;?>">
						<input type="hidden" name="is_interstate" id="is_interstate" value="<?php echo $is_interstate;?>">
						<input type="hidden" name="due_days" class="dueDays" value=0>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label><b>Purchase Invoice Voucher No :</b></label>&nbsp;&nbsp;<br>
								<?php if(empty($Voucher_no_last)){ ?>
									<?= h('#'.str_pad(1, 4, '0', STR_PAD_LEFT)) ?>
								<?php }else{ ?>
									<?= h('#'.str_pad($Voucher_no_last->voucher_no+1, 4, '0', STR_PAD_LEFT)) ?>
								<?php } ?>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label>Transaction Date <span class="required">*</span></label>
								
								<?php echo $this->Form->input('transaction_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => date("d-m-Y"),'data-date-start-date'=>$coreVariable['fyValidFrom'],'data-date-end-date'=>$coreVariable['fyValidTo']]); ?>
								
							</div>
						</div>
						<div class="col-md-3">
								<label>Purchase Account</label>
								<?php echo $this->Form->control('purchase_ledger_id',['class'=>'form-control input-sm  select2me','label'=>false, 'options' => $Accountledgers,'required'=>'required']);
								?>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label>Narration </label>
								<?php echo $this->Form->control('narration',['class'=>'form-control input-sm','label'=>false,'placeholder'=>'Narration','rows'=>'4']); ?>
							</div>
						</div>
					</div><BR/><BR/>
					
				   
				   <div class="row">
				  <div class="table-responsive">
								<table id="main_table" class="table table-condensed table-bordered" 
								style=" height: 24px; padding: 0px 0px;font-size: 12px;" width="100%">
								<thead>
								<tr align="center">
									<th rowspan="2" style="text-align:left;"><label>Item<label></td>
									<th rowspan="2" style="text-align:center;"><label>Qty<label></td>
									<th rowspan="2" style="text-align:center;"><label>Rate<label></td>
									<th  colspan="2" style="text-align:center;"><label align="center">Discount (%)</label></th>
									<th  colspan="2" style="text-align:center;"><label align="center">PNF (%)</label></th>
									<th rowspan="2" style="text-align:center;"><label>Taxable Value<label></td>
									<th colspan="2" style="text-align:center;"><label id="gstDisplay">GST<label></th>
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
								 foreach($Grns->grn_rows as $grn_row)
								 {
									
									
									$gst_per=0;
									$gst_per_id=0;
									if($grn_row->item->kind_of_gst=="fluid"){
										if($grn_row->item->gst_amount < $grn_row->purchase_rate){
											$gst_per=$grn_row->item->SecondGstFigures->tax_percentage;
											$gst_per_id=$grn_row->item->SecondGstFigures->id;
										}else{
											$gst_per=$grn_row->item->FirstGstFigures->tax_percentage;
											$gst_per_id=$grn_row->item->FirstGstFigures->id;
										}
									}else{
										$gst_per=$grn_row->item->FirstGstFigures->tax_percentage;
										$gst_per_id=$grn_row->item->FirstGstFigures->id;
									}
									
							     ?>
								<tr class="main_tr" class="tab">
									<td width="15%" align="left">
									<input type="hidden" name="q" class="attrGet calculation" value="<?php echo $grn_row->item_id; ?>">
									<?php echo $grn_row->item->name; ?></td>
									<td width="5%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm calculation quantity rightAligntextClass','required'=>'required','placeholder'=>'Quantity', 'value'=>$grn_row->quantity]); 
										echo $grn_row->quantity;
										?>
									</td>
									<td width="8%" align="center">
										<?php echo $this->Form->input('q', ['type'=>'text','label' => false,'class' => 'form-control input-sm  rate numberOnly rightAligntextClass','value'=>$grn_row->purchase_rate]); 
										//echo $grn_row->purchase_rate;
										?>
									</td>
									<td  width="6%" align="center">
										<?php echo $this->Form->input('q', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm discount numberOnly','placeholder'=>'Discount','type'=>'text','value'=>0]);
										?>	
									</td>
									<td  width="10%" align="center">
										<?php echo $this->Form->input('q', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm discountAmount numberOnly','type'=>'text','value'=>0]);
										?>	
									</td>
									<td  width="6%" align="center">
										<?php echo $this->Form->input('q', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm pnf numberOnly','placeholder'=>'PNF','type'=>'text','value'=>0]);
										?>	
									</td>
									<td  width="10%" align="center">
										<?php echo $this->Form->input('q', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm pnfAmount numberOnly','type'=>'text','value'=>0]);
										?>	
									</td>
									
									<td  width="10%" align="center">
										<?php echo $this->Form->input('q', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm taxableValue','type'=>'text','tabindex'=>'-1']);
										?>	
									</td>
									
									<td  width="6%" align="center">
										<?php
											echo $this->Form->control('q',['class'=>'form-control input-sm  select2me gst_figure_id','label'=>false, 'options' => $Gstfigures,'required'=>'required','value'=>$gst_per_id]);
											
											 echo $this->Form->input('q', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm gst_percentage','type'=>'hidden','tabindex'=>'-1']);
											
										
										?>	
									</td>
									<td  width="10%" align="center">
										<?php echo $this->Form->input('q', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm gstValue','type'=>'text','tabindex'=>'-1']);
										?>	
									</td>
									<!--<td  width="7%" align="center">
										<?php /*  echo $this->Form->input('q', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm roundOff','placeholder'=>'','type'=>'text']); */
										?>	
									</td>-->
									<td  width="20%" align="center">
										<?php echo $this->Form->input('q', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm netAmount','type'=>'text','tabindex'=>'-1']);
										?>	
									</td>
								
															
								
							</tr>
								<?php $i++; } ?>
								
								<tr>
									<td  colspan="6" align="right">
										<?php echo "Freight Charge";?>	
									</td>
									<td  colspan="1" align="right">
										<?php echo $this->Form->control('fright_ledger_id',['class'=>'form-control input-sm  select2me ','label'=>false, 'options' => $freightLedgers,'required'=>'required','value'=>'']); ?>
									</td>
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('fright_amount', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm fright_amount','type'=>'text']);	 ?>
									</td>
									<td  colspan="1" align="right">
										<?php echo $this->Form->control('fright_gst',['class'=>'form-control input-sm  select2me fright_gst','label'=>false, 'options' => $Gstfigures,'required'=>'required','value'=>'']); ?>
									</td>
									
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('fright_gst_amount', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm fright_gst_amount','type'=>'text']);	 ?>
									</td>
									<!--<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_round_off', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm total_round_off','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>-->
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_fright_amount', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm total_fright_amount','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>
								</tr>
								
								<tr>
									<td  colspan="7" align="right">
										<?php echo "Round Off";?>	
									</td>
									<td  colspan="3" align="right"></td>
									
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_round_off', ['style'=>'text-align:right','label' => false,'class' => 'form-control input-sm total_round_off','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>
								</tr>
								
								<tr>
									<td  colspan="1" align="right">
										<?php echo "Total";?>	
									</td>
									<td  colspan="2" align="left">
										<?php echo $this->Form->input('total_qty', ['style'=>'text-align:left','readonly','label' => false,'class' => 'form-control input-sm total_qty','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>
									<td  colspan="2" align="right">
										<?php echo $this->Form->input('total_discount_amt', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm total_discount_amt','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>
									<td  colspan="2" align="right">
										<?php echo $this->Form->input('total_pnf_amt', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm total_pnf_amt','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_taxable_value', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm total_taxable_value','type'=>'hidden','tabindex'=>'-1']);	 ?>
										<?php echo $this->Form->input('total_taxable_value1', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm total_taxable_value1','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>
									<td  colspan="2" align="right">
										<?php echo $this->Form->input('total_gst_value', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm total_gst_value','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>
									<!--<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_round_amount', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm total_round_amount','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>-->
									<td  colspan="1" align="right">
										<?php echo $this->Form->input('total_amount', ['style'=>'text-align:right','readonly','label' => false,'class' => 'form-control input-sm total_amount','type'=>'text','tabindex'=>'-1']);	 ?>
									</td>
								</tr>
								
								</tbody>
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
			var due_days=$('select[name=supplier_ledger_id] :selected').attr('default_days');
			$('.dueDays').val(due_days);
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
		
	
	
	
	rename_rows();
	function rename_rows()
	{
		var i=0;
		$('#main_table tbody#main_tbody tr.main_tr').each(function(){ 
			$(this).find('td:nth-child(1) input.attrGet').attr({name:'purchase_invoice_rows['+i+'][item_id]',id:'purchase_invoice_rows['+i+'][item_id]'});
			$(this).find('.quantity').attr({name:'purchase_invoice_rows['+i+'][quantity]',id:'purchase_invoice_rows['+i+'][quantity]'});
			$(this).find('.rate').attr({name:'purchase_invoice_rows['+i+'][rate]',id:'purchase_invoice_rows['+i+'][rate]'});
			$(this).find('.discount').attr({name:'purchase_invoice_rows['+i+'][discount_percentage]',id:'purchase_invoice_rows['+i+'][discount_percentage]'});
			$(this).find('.discountAmount').attr({name:'purchase_invoice_rows['+i+'][discount_amount]',id:'purchase_invoice_rows['+i+'][discount_amount]'});
			$(this).find('.pnf').attr({name:'purchase_invoice_rows['+i+'][pnf_percentage]',id:'purchase_invoice_rows['+i+'][pnf_percentage]'});
			$(this).find('.pnfAmount').attr({name:'purchase_invoice_rows['+i+'][pnf_amount]',id:'purchase_invoice_rows['+i+'][pnf_amount]'});
			$(this).find('.taxableValue').attr({name:'purchase_invoice_rows['+i+'][taxable_value]',id:'purchase_invoice_rows['+i+'][taxable_value]'}).attr('readonly', true);
			
			/* $(this).find('.item_gst_figure_id').attr({name:'purchase_invoice_rows['+i+'][item_gst_figure_id]',id:'purchase_invoice_rows['+i+'][item_gst_figure_id]'}).attr('readonly', true);
			 */
			$(this).find('.gst_figure_id').attr({name:'purchase_invoice_rows['+i+'][item_gst_figure_id]',id:'purchase_invoice_rows['+i+'][item_gst_figure_id]'});
			$(this).find('.gst_percentage').attr({name:'purchase_invoice_rows['+i+'][gst_percentage]',id:'purchase_invoice_rows['+i+'][gst_percentage]'});
			$(this).find('.gstValue').attr({name:'purchase_invoice_rows['+i+'][gst_value]',id:'purchase_invoice_rows['+i+'][gst_value]'}).attr('readonly', true);
			//$(this).find('.roundOff').attr({name:'purchase_invoice_rows['+i+'][round_off]',id:'purchase_invoice_rows['+i+'][round_off]'});
			$(this).find('.netAmount').attr({name:'purchase_invoice_rows['+i+'][net_amount]',id:'purchase_invoice_rows['+i+'][net_amount]'});
		i++;
		});
	}
	
	$('.rate ').die().live('blur',function()
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
	$('.roundOff').die().live('keyup',function()
	{
		forward_total_amount();
	});
	$('.fright_amount').die().live('keyup',function()
	{
		forward_total_amount();
	});
	$('.total_round_off').die().live('keyup',function()
	{
		forward_total_amount();
	});
	
	$('.gst_figure_id').die().live('change',function(){
		forward_total_amount();	
	});
	$('.fright_gst').die().live('change',function(){
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
			var total_qt=0;
			
			$('#main_table tbody#main_tbody tr.main_tr').each(function()
			{ 
			    var quantity=parseFloat($(this).closest('tr').find('.quantity').val());
			    var rate=parseFloat($(this).closest('tr').find('.rate').val());
				var amount=quantity*rate;
				total_qt=total_qt+quantity;
			    var discount=parseFloat($(this).closest('tr').find('.discount').val());
				if(!discount){discount=0;}
				
				discount=round(discount,3);
				var disAmt=0;
				disAmt=parseFloat($(this).closest('tr').find('.discountAmount').val());
				var pnfAmt=parseFloat($(this).closest('tr').find('.pnfAmount').val());
				amountAfterDiscount=amount-disAmt;
				
				amount=round(amount,2);
				disAmt=round(disAmt,2);
				total_dis+=disAmt;
				total_pnf+=pnfAmt;
				taxableAmt=(amount-disAmt)+pnfAmt;
				//taxableAmt=round(taxableAmt,2);
				$(this).closest('tr').find('.taxableValue').val(taxableAmt.toFixed(2));
				total_taxable=total_taxable+taxableAmt;
				var gstTax=parseFloat($(this).closest('tr').find('.gst_figure_id option:selected').attr('FirstGstFigure'));
				$(this).closest('tr').find('.gst_percentage').val(gstTax);
				
				//var gstTax=$('option:selected', this).attr('FirstGstFigure');
				//alert(gstTax);
				if(!gstTax){ 
					var gstAmt=0;
					$(this).closest('tr').find('.gstValue').val(gstAmt.toFixed(2));
				}else{
					//var supplier_state_id=$('option:selected', '.supplier_state_id').attr('state_id');
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
			//
			var fright_amount=parseFloat($('.fright_amount').val());
			
			if(isNaN(fright_amount)){
				fright_amount=0;
				total_fright_amt=0;
				$('.fright_gst_amount').val(fright_amount);
				$('.total_fright_amount').val(fright_amount);
			}else{
				var frightgstTax=parseFloat($('.fright_gst option:selected').attr('FirstGstFigure'));
				var amt12=(fright_amount*frightgstTax)/100;
				amt12=round(amt12,2);
				$('.fright_gst_amount').val(amt12);
				var total_fright_amt=amt12+fright_amount;
				$('.total_fright_amount').val(parseFloat(total_fright_amt).toFixed(2));
				
			}
			var total_round_off=parseFloat($('.total_round_off').val());
			if(isNaN(total_round_off)){
				total_round_off=0;
			}
			if(isNaN(amt12)){
				amt12=0;
			}
			total_round=total_round_off;
			total_amt=total_fright_amt+total_amt+total_round_off;
			total_gst=total_gst+amt12;
			total_taxable=total_taxable;
			var total_taxable1=total_taxable+fright_amount;
			
			$('.total_discount_amt').val(total_dis.toFixed(2));
			$('.total_pnf_amt').val(total_pnf.toFixed(2));
			$('.total_taxable_value').val(total_taxable.toFixed(2));
			$('.total_taxable_value1').val(total_taxable1.toFixed(2));
			$('.total_gst_value').val(total_gst.toFixed(2));
			$('.total_round_off').val(total_round.toFixed(2));
			$('.total_amount').val(parseFloat(total_amt).toFixed(2));
			$('.total_qty').val(total_qt);
			
			rename_rows();
		}
		
	
	$('.pnfAmount').die().live('blur',function()
	{
				var quantity=parseFloat($(this).closest('tr').find('.quantity').val());
			    var rate=parseFloat($(this).closest('tr').find('.rate').val());
				var amount=quantity*rate;
			   var pnfAmt=parseFloat($(this).closest('tr').find('.pnfAmount').val());
				if(isNaN(pnfAmt)){ 
					var pnfPer=0;
					var pnfAmt=0;
					$(this).closest('tr').find('.pnf').val(pnfPer.toFixed(2));
					$(this).closest('tr').find('.pnfAmount').val(pnfAmt.toFixed(2));
					//total_pnf=total_pnf+pnfAmt;
				}else{
					var pnfPer=(100*pnfAmt)/amount;
					pnfPer=round(pnfPer,3);
					//var pnfAmt=(amount*pnf)/100;
					$(this).closest('tr').find('.pnf').val(pnfPer);
					//total_pnf=total_pnf+pnfAmt;
				}
				forward_total_amount();
	});
	
	$('.discountAmount').die().live('blur',function()
	{	
			var quantity=parseFloat($(this).closest('tr').find('.quantity').val());
			    var rate=parseFloat($(this).closest('tr').find('.rate').val());
				var amount=quantity*rate;
			    var discountAmt=parseFloat($(this).closest('tr').find('.discountAmount').val());
				
				//var discountAmt=round(discountAmt,2)
				if(isNaN(discountAmt)){ 
					var dis=0;
					var discountAmt=0;
					$(this).closest('tr').find('.discount').val(dis.toFixed(2));
					$(this).closest('tr').find('.discountAmount').val(discountAmt);
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


<?php }   ?>