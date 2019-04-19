<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 <meta http-equiv="refresh" content="5" >
 */
$this->set('title', 'Create Sales Invoice');
?>
<style>
.disabledbutton {
    pointer-events: none;
    opacity: 0.7;
}
.checkbox{
	margin:0px;
}
</style>

<form method="GET" id="barcodeFrom">
	<div class="row">
		<div class="col-md-3">
			<?php echo $this->Form->input('itembarcode',['class'=>'form-control input-sm itembarcode','label'=>false, 'placeholder'=>'Item Code/Bar-Code','autofocus'=>'autofocus']);
			?>
		</div>
		<div class="col-md-1" align="left">
			<button type="submit" class="go btn blue-madison input-sm">Go</button>
		</div> 
	</div>
</form>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Sales Invoice</span>
				</div>
				<div class="actions">
				<a class="btn default" id="demo_1" style="background-color: #35aa47;color: #FFFFFF;">Add New Customer <i class="fa fa-plus"></i></a>
			</div>
			</div>
			<div class="portlet-body">
				
				<?= $this->Form->create($salesInvoice,['onsubmit'=>'return checkValidation()']) ?>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Voucher No :</label>&nbsp;&nbsp;
								
								<?php
								//echo $coreVariable['company_name']; exit;
								   // $date = date('Y-m-d');
								    $date=date('Y-m-d',strtotime($FinancialYearData->fy_from));
									$d = date_parse_from_format('Y-m-d',$date);
									$yr=$d["year"];$year= substr($yr, -2);
									if($d["month"]=='01' || $d["month"]=='02' || $d["month"]=='03')
									{
									  $startYear=$year-1;
									  $endYear=$year;
									  $financialyear=$startYear.'-'.$endYear;
									}
									else
									{
									  $startYear=$year;
									  $endYear=$year+1;
									  $financialyear=$startYear.'-'.$endYear;
									}
								$words = explode(" ", $coreVariable['company_name']);
								$acronym = "";
								foreach ($words as $w) {
								$acronym .= $w[0];
								}
								?>
								<?= $acronym.'/'.$financialyear.'/'. h(str_pad($voucher_no, 3, '0', STR_PAD_LEFT))?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Transaction Date <span class="required">*</span></label>
								<?php echo $this->Form->control('transaction_date',['class'=>'form-control input-sm date-picker transaction_date disabledbutton','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y'),'readonly'=>'readonly']); ?>
							</div>
						</div>
						<input type="hidden" name="outOfStock" class="outOfStock" value="0">
						<input type="hidden" name="due_days" class="dueDays">
						<input type="hidden" name="company_id" class="customer_id" value="<?php echo $company_id;?>">
						<input type="hidden" name="location_id" class="location_id" value="<?php echo $location_id;?>">
						<input type="hidden" name="state_id" class="state_id" value="<?php echo $state_id;?>">
						<input type="hidden" name="is_interstate" id="is_interstate" value="0">
						<input type="hidden" name="isRoundofType" id="isRoundofType" class="isRoundofType" value="0">
						<input type="hidden" name="voucher_no" id="" value="<?= h($voucher_no, 4, '0') ?>">
						<div class="col-md-3 test">
								<label>Party</label>
								<?php echo $this->Form->control('party_ledger_id',['empty'=>'-Select Party-', 'class'=>'form-control input-sm party_ledger_id select2me','label'=>false,'options' => $partyOptions,'required'=>'required','value'=>$CashPartyLedgers->id]);
								?>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Sales Account</label>
								<?php echo $this->Form->control('sales_ledger_id',['class'=>'form-control input-sm sales_ledger_id select2me','label'=>false, 'options' => $Accountledgers,'required'=>'required']);
								?>
							</div>
						</div> 
					</div>
					<br>
					<div class="row">
					<div class="table-responsive">
								<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
								<thead>
								<tr align="center">
									<td width="20%"><label>Item<label></td>
									<td><label>Qty<label></td>
									<td><label>Rate<label></td>
									<td><label>Discount(%)<label></td>
									<td><label>Discount(Amt.)<label></td>
									<td><label>Taxable Value<label></td>
									<td><label id="gstDisplay">GST<label></td>
									<td><label>Net Amount<label></td>
									<td></td>
								</tr>
								</thead>
								<tbody id='main_tbody' class="tab">
								</tbody>
								<tfoot>
									<tr>
										<td colspan="8">	
											<button type="button" class="add_row btn btn-default input-sm"><i class="fa fa-plus"></i> Add row</button>
										</td>
									</tr>
						<tr>
							<td colspan="6" align="right"><b>Amt Before Tax</b>
							</td>
							<td colspan="2">
							<?php echo $this->Form->input('amount_before_tax', ['label' => false,'class' => 'form-control input-sm amount_before_tax rightAligntextClass','required'=>'required', 'readonly'=>'readonly','placeholder'=>'', 'tabindex'=>'-1']); ?>	
							</td>
						</tr>
						<tr>
							<td colspan="6" align="right"><b>Discount Amount</b>
							</td>
							<td colspan="2">
							<?php echo $this->Form->input('discount_amount', ['label' => false,'class' => 'form-control input-sm rightAligntextClass toalDiscount','required'=>'required', 'readonly'=>'readonly','placeholder'=>'', 'tabindex'=>'-1']); ?>	
							</td>
						</tr>
						<tr id="add_cgst">
							<td colspan="6" align="right"><b>Total CGST</b>
							</td>
							<td colspan="2">
							<?php echo $this->Form->input('total_cgst', ['label' => false,'class' => 'form-control input-sm add_cgst rightAligntextClass','required'=>'required', 'readonly'=>'readonly','placeholder'=>'', 'tabindex'=>'-1']); ?>	
							</td>
						</tr>
						<tr id="add_sgst">
							<td colspan="6" align="right"><b>Total SGST</b>
							</td>
							<td colspan="2">
								<?php echo $this->Form->input('total_sgst', ['label' => false,'class' => 'form-control input-sm add_sgst rightAligntextClass','required'=>'required', 'readonly'=>'readonly','placeholder'=>'', 'tabindex'=>'-1']); ?>	
							</td>
						</tr>
						<tr id="add_igst" style="display:none">
							<td colspan="6" align="right"><b>Total IGST</b>
							</td>
							<td colspan="2">
								<?php echo $this->Form->input('total_igst', ['label' => false,'class' => 'form-control input-sm add_igst rightAligntextClass','required'=>'required', 'readonly'=>'readonly','placeholder'=>'', 'tabindex'=>'-1']); ?>	
							</td>
						</tr>
						<tr>
							<td colspan="6" align="right"><b>Round Off</b>
							</td>
							<td colspan="2">
							<?php echo $this->Form->input('round_off', ['label' => false,'class' => 'form-control input-sm roundValue rightAligntextClass','required'=>'required', 'readonly'=>'readonly','placeholder'=>'', 'tabindex'=>'-1']); ?>	
							</td>
						</tr>
						<tr>
							<td colspan="4" >
									<div class="radio-list" id="invoiceReceiptTd1" style="display:none">
									 <b>Check for Receipt</b>
										<div class="radio-inline" style="padding-left: 0px;">
											<?php echo $this->Form->radio(
											'invoice_receipt_type',
											[
												['value' => 'cash', 'text' => 'Cash','class' => ''],
												['value' => 'credit', 'text' => 'Credit','class' => '']
											],['value'=>'cash']
											); ?>
										</div>
                                    </div>
									<input type="hidden" id="invoiceReceiptTd2" name="invoiceReceiptTd">
									<input type="hidden" id="receipt_amount" name="receipt_amount">
							</td>
							
							<td colspan="2" align="right"><b>Amt After Tax</b>
							</td>
							
							<td colspan="2">
							<?php echo $this->Form->input('amount_after_tax', ['label' => false,'class' => 'form-control input-sm amount_after_tax rightAligntextClass','required'=>'required', 'readonly'=>'readonly','placeholder'=>'', 'tabindex'=>'-1']); ?>	
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

<table id="sample_table" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab">
			<td>
				<input type="hidden" name="" class="outStock" value="0">
				<input type="hidden" name="" class="totStock " value="0">
				<input type="hidden" name="gst_figure_id" class="gst_figure_id" value="">
				<input type="hidden" name="gst_amount" class="gst_amount" value="">
				<input type="hidden" name="gst_figure_tax_percentage" class="gst_figure_tax_percentage calculation" value="">
				<input type="hidden" name="tot" class="totamount calculation" value="">
				<input type="hidden" name="gst_value" class="gstValue calculation" value="">
				<input type="hidden" name="exactgst_value" class="exactgst_value calculation" value="">
				<input type="hidden" name="discountvalue" class="discountvalue calculation" value="">
				<?php echo $this->Form->input('item_id', ['empty'=>'-Item Name-', 'options'=>$itemOptions,'label' => false,'class' =>'form-control input-medium input-sm attrGet bottomSelect','required'=>'required']); ?>
				<span class="itemQty" style="font-size:10px;"></span>
			</td>			
			<td>
				<?php echo $this->Form->input('quantity', ['label' => false,'class' => 'form-control input-sm calculation quantity numberOnly rightAligntextClass','id'=>'check','required'=>'required','placeholder'=>'Quantity', 'value'=>1]); ?>
			</td>
			<td>
				<?php echo $this->Form->input('rate', ['label' => false,'class' => 'form-control input-sm calculation rate rightAligntextClass','required'=>'required','placeholder'=>'Rate', 'readonly'=>'readonly', 'tabindex'=>'-1']); ?>
			</td>
			<td>
				<?php echo $this->Form->input('discount_percentage', ['label' => false,'class' => 'form-control input-sm discount discalculation numberOnly rightAligntextClass','placeholder'=>'Dis.','value'=>0]); ?>	
			</td>
			<td>
				<?php echo $this->Form->input('discount', ['label' => false,'class' => 'form-control input-sm calculation dis_amount numberOnly rightAligntextClass','placeholder'=>'Dis.','value'=>0]); ?>	
			</td>
			
			<td>
				<?php echo $this->Form->input('taxable_value', ['label' => false,'class' => 'form-control input-sm gstAmount reverse_total_amount rightAligntextClass','required'=>'required','placeholder'=>'Amount', 'readonly'=>'readonly', 'tabindex'=>'-1']); ?>
			</td>
			<td>
				<?php echo $this->Form->input('gst_figure_tax_name', ['label' => false,'class' => 'form-control input-sm gst_figure_tax_name rightAligntextClass', 'readonly'=>'readonly','required'=>'required','placeholder'=>'GST', 'tabindex'=>'-1']); ?>	
			</td>
			<td>
				<?php echo $this->Form->input('net_amount', ['label' => false,'class' => 'form-control input-sm discountAmount calculation rightAligntextClass','required'=>'required', 'readonly'=>'readonly','placeholder'=>'Taxable Value', 'tabindex'=>'-1']); ?>					
			</td>
			<td align="center">
				<a class="btn btn-danger delete-tr btn-xs dlt" href="#" role="button" style="margin-bottom: 5px;"><i class="fa fa-times"></i></a>
				<?php echo $this->Form->input('is_gst_excluded1', ['label' => false,'class' => 'form-control input-sm is_gst_excluded tooltips', 'type'=>'checkbox', 'data-placement'=>'top', 'data-original-title'=>'Excluded GST?']); ?>
				<?php echo $this->Form->input('is_gst_excluded', ['label' => false,'class' => 'form-control input-sm is_gstvalue_excluded', 'type'=>'hidden']); ?>
				
			</td>
		</tr>
	</tbody>
</table>

<?php
	$js="
	$(document).ready(function() {
		
		var time = new Date().getTime();
		 $(document.body).bind('mousemove keypress', function(e) {
			 time = new Date().getTime();
		 });

		 function refresh() {
			 if(new Date().getTime() - time >= 300000) 
				 window.location.reload(true);
			 else 
				 setTimeout(refresh, 1000);
		 }

		 setTimeout(refresh, 1000);
		
		
		
		
		
		
		$('.attrGet').die().live('change',function(){ 
		var itemQ=$(this).closest('tr');
			var gst_amount=$('option:selected', this).attr('gst_amount');
			var sales_rate=$('option:selected', this).attr('sales_rate');
			$(this).closest('tr').find('.gst_amount').val(gst_amount);
			$(this).closest('tr').find('.rate').val(sales_rate);
			$(this).closest('tr').find('.gst_amount').val(gst_amount);
			
			var itemId=$(this).val();
			var url='".$this->Url->build(["controller" => "SalesInvoices", "action" => "ajaxItemQuantity"])."';
			url=url+'/'+itemId
			$.ajax({
				url: url,
				type: 'GET'
				//dataType: 'text'
			}).done(function(response) {
				var fetch=$.parseJSON(response);
				var text=fetch.text;
				var type=fetch.type;
				var mainStock=fetch.mainStock;
				itemQ.find('.itemQty').html(text);
				itemQ.find('.totStock').val(mainStock);
				if(type=='true')
				{
					itemQ.find('.outStock').val(1);
				}
				else{
					itemQ.find('.outStock').val(0);
				}
			});	
			var temp=$(this).closest('tr');
			forward_total_amount(temp);
			});
			
			$('#barcodeFrom').die().live('submit',function(e){
			e.preventDefault();
			var Inputitemcode=$('.itembarcode').val();
			var addQuantity=0;
			var addDB=0;
			/* $('#main_table tbody#main_tbody tr.main_tr').each(function(){
			var item_code=$(this).find('td:nth-child(1) select.attrGet option:selected').attr('item_code');
			var quantity=$(this).find('td:nth-child(2) input.quantity').val();
			addQuantity=parseFloat(quantity)+1;
			if(!item_code){item_code=0;}
			if(Inputitemcode==item_code)
			{
			addDB=1;
			//$(this).find('td:nth-child(2) input.quantity').val(addQuantity);
			}
			});
			if(addDB==1)
			{
			//$(this).find('td:nth-child(2) input.quantity').val(addQuantity);
			}
			else { */
				if(Inputitemcode){
					var item_id=$('select.bottomSelect option[item_code='+Inputitemcode+']').val();
					if(item_id){
						var l=$('#main_table tbody#main_tbody tr:last').length;
						if(l==1){
							var is_sel=$('#main_table tbody#main_tbody tr:last select.attrGet').val();
							if(!is_sel){
								$('#main_table tbody#main_tbody tr:nth-child(1) select.attrGet').val(item_id).trigger('change').select2();
								$('.itembarcode').val('');
							}else{
								add_row();
								$('#main_table tbody#main_tbody tr:last select.attrGet').val(item_id).trigger('change').select2();
								$('.itembarcode').val('');
							}
						}else{
							add_row();
							$('#main_table tbody#main_tbody tr:last select.attrGet').val(item_id).trigger('change').select2();
							$('.itembarcode').val('');
						}
						//console.log(l);
					}else{
						alert('Not found any item of this barcode.');
						$('.itembarcode').val('');
					}
				}
			// }
		});
		
		$(document).ready(onLoadInvoiceReceipt);
		function onLoadInvoiceReceipt()
		{
		 var partyexist=$('select[name=party_ledger_id] :selected').attr('partyexist');
		 if(partyexist=='1')
			{
				$('#invoiceReceiptTd1').show();
				$('#invoiceReceiptTd2').val('1');
			}
			else{
				$('#invoiceReceiptTd1').hide();
				$('#invoiceReceiptTd2').val('0');
			}
		}
		
		$('.party_ledger_id').die().live('change',function(){
			var customer_state_id=$('option:selected', this).attr('party_state_id');
			var partyexist=$('option:selected', this).attr('partyexist');
			var due_days=$('option:selected', this).attr('default_days');
			$('.dueDays').val(due_days);
			var state_id=$('.state_id').val();
			if(partyexist=='1')
			{
				$('#invoiceReceiptTd1').show();
				$('#invoiceReceiptTd2').val('1');
				
			}
			else{
				$('#invoiceReceiptTd1').hide();
				$('#invoiceReceiptTd2').val('0');
			}
			
			if(customer_state_id!=state_id)
			{
			if(customer_state_id>0)
			{
				$('#gstDisplay').html('IGST');
				$('#add_igst').show();
				$('#add_cgst').hide();
				$('#add_sgst').hide();
				$('#is_interstate').val('1');
			}
			else if(!customer_state_id)
			{
				$('#gstDisplay').html('GST');
				$('#add_cgst').show();
				$('#add_sgst').show();
				$('#add_igst').hide();
				$('#is_interstate').val('0');
			}
			else if(customer_state_id==0)
			{
				$('#gstDisplay').html('GST');
				$('#add_cgst').show();
				$('#add_sgst').show();
				$('#add_igst').hide();
				$('#is_interstate').val('0');
			}
			}
			else if(customer_state_id==state_id){
				$('#gstDisplay').html('GST');
				$('#add_cgst').show();
				$('#add_sgst').show();
				$('#add_igst').hide();
				$('#is_interstate').val('0');
			}
			//$(this).closest('tr').find('.output_igst_ledger_id').val(output_igst_ledger_id);
		});
		
		$('.delete-tr').die().live('click',function() 
		{
			$(this).closest('tr').remove();
			rename_rows();
			var temp=$(this).closest('tr');
			forward_total_amount(temp);
			//forward_total_amount();
		});
		ComponentsPickers.init();
		
	});
	
	$('.add_row').click(function(){
		add_row();
    }) ;
	
	$( document ).ready(add_row);
	function add_row()
	{
		var tr=$('#sample_table tbody tr.main_tr').clone();
		$('#main_table tbody#main_tbody').append(tr);
		var test = $('input[type=radio]:not(.toggle),input[type=checkbox]:not(.toggle)');
		if (test) { test.uniform(); }
		$('.tooltips').tooltip();
		var temp=$(this).remove();
		
		rename_rows();
		forward_total_amount(temp);
	}
	function rename_rows()
	{
		var i=0;
		$('#main_table tbody#main_tbody tr.main_tr').each(function(){
			
			$(this).find('td:nth-child(1) select.attrGet').select2().attr({name:'sales_invoice_rows['+i+'][item_id]',id:'sales_invoice_rows['+i+'][item_id]'});
			
			$(this).find('.quantity').attr({name:'sales_invoice_rows['+i+'][quantity]',id:'sales_invoice_rows['+i+'][quantity]'});
			$(this).find('.rate').attr({name:'sales_invoice_rows['+i+'][rate]',id:'sales_invoice_rows['+i+'][rate]'});
			$(this).find('.discount').attr({name:'sales_invoice_rows['+i+'][discount_percentage]',id:'sales_invoice_rows['+i+'][discount_percentage]'});

			$(this).find('.dis_amount').attr({name:'sales_invoice_rows['+i+'][discount]',id:'sales_invoice_rows['+i+'][discount]'});
			$(this).find('.gstAmount').attr({name:'sales_invoice_rows['+i+'][taxable_value]',id:'sales_invoice_rows['+i+'][taxable_value]'});

			$(this).find('.gst_figure_id').attr({name:'sales_invoice_rows['+i+'][gst_figure_id]',id:'sales_invoice_rows['+i+'][gst_figure_id]'});

			$(this).find('.discountAmount').attr({name:'sales_invoice_rows['+i+'][net_amount]',id:'sales_invoice_rows['+i+'][net_amount]'});
			$(this).find('.is_gstvalue_excluded').attr({name:'sales_invoice_rows['+i+'][is_gst_excluded]',id:'sales_invoice_rows['+i+'][is_gst_excluded]'});
			$(this).find('.gstValue').attr({name:'sales_invoice_rows['+i+'][gst_value]',id:'sales_invoice_rows['+i+'][gst_value]'});
			
			// if(i==0)
			// {
			 // $(this).closest('tr').find('.dlt').remove();
			// }
			i++;
		});
	}
	
	$('.calculation').die().live('keyup',function()
	{
		var temp=$(this).closest('tr');
		forward_total_amount(temp);
	});
	
	
	$('.itembarcode').die().keypress(function(e)
	{
	 if(e.which === 32) 
        return false;
	});
	
	$('.is_gst_excluded').die().live('click',function(){
		var temp=$(this).closest('tr');
		forward_total_amount(temp);
	});
	$('#demo_1').die().live('click',function(){ 
		$('#myModal2').show();
	});
	
	
	$('.closebtn2').die().live('click',function(){ 
		$('#myModal2').hide();
    });
	
	var addcustomer = $('.addcategories').val();
		$('#category_data_form').on('submit',function (e)
		{
			e.preventDefault();
			var formData = $(this).serialize();
			
			$.ajax(
			{
					type:'post',
					url:addcustomer,
					data:formData,
					success:function(response)
					{  
						$('.test').html(response);
						$('select[name=party_ledger_id]').select2();
						$('#myModal2').hide();
							
					}
			});
		});
	
	
	function forward_total_amount(temp){  
		var isExcludingCalculation;
		var total  = 0;
		var gst_amount  = 0;
		var gst_value  = 0;
		var s_cgst_value=0;
		var roundOff1=0;
		var round_of=0;
		var isRoundofType=0;
		var igst_value=0;
		var outOfStockValue=0;
		var s_igst=0;
		var newsgst=0;
		var newigst=0;
		var exactgstvalue=0;
		var totDiscounts=0;
		var convertDiscount=0;
		
			
			var length=temp.find('.is_gst_excluded:checked').length;
			if(length==1){
				isExcludingCalculation=1;
				temp.find('.is_gstvalue_excluded').val(1);
			}else{
				isExcludingCalculation=0;
				temp.find('.is_gstvalue_excluded').val(0);
			}
			
			if(!isExcludingCalculation){ isExcludingCalculation=0; }
			
			var outdata=temp.closest('tr').find('.outStock').val();
			
			if(!outdata){outdata=0;}
			var outOfStockValue=parseFloat(outOfStockValue)+parseFloat(outdata);
			$('.outOfStock').val(outOfStockValue);
			
			var quantity  = temp.closest('tr').find('.quantity').val();
			
			if(!quantity){quantity=0;}
			var quantity=round(quantity,2);
			
			var rate  = parseFloat(temp.closest('tr').find('.rate').val());
			if(!rate){rate=0;}
			var rate=round(rate,2);
			
			var amount=quantity*rate;
			if(!amount){amount=0;}
			var amount=round(amount,2);
			temp.closest('tr').find('.totamount').val(amount);
			
			var discountValue=temp.closest('tr').find('.dis_amount').val();
			var discountValue=round(discountValue,2);
			totDiscounts=round(parseFloat(totDiscounts)+parseFloat(discountValue), 2);
			
			var amountAfterDicsount=amount-discountValue;
			if(!amountAfterDicsount){amountAfterDicsount=0;}
			var amountAfterDicsount=round(amountAfterDicsount,2);
			
			var discountPercentage=discountValue*100/amount;
			var discountPercentage=round(discountPercentage,3);
			temp.closest('tr').find('.discount').val(discountPercentage);
			
			var gstComparableAmount  =temp.closest('tr').find('.gst_amount').val();
			
			if(isExcludingCalculation==1){
				temp.closest('tr').find('.gstAmount').val(amountAfterDicsount);
				var first_gst_figure_tax_percentage=parseFloat($('option:selected', temp).attr('FirstGstFigure'));
				var netValue=amountAfterDicsount*(100+first_gst_figure_tax_percentage)/100;
				if(!netValue){netValue=0;}
				var netValue=round(netValue,2);
				var netValuePerQty=netValue/quantity;
				if(netValuePerQty<=gstComparableAmount){
					var first_gst_figure_tax_name=$('option:selected', temp).attr('FirstGstFigure');
					var first_gst_figure_id=$('option:selected', temp).attr('first_gst_figure_id');
						
					temp.closest('tr').find('.gst_figure_id').val(first_gst_figure_id);
					temp.closest('tr').find('.gst_figure_tax_percentage').val(first_gst_figure_tax_percentage);
					temp.closest('tr').find('.gst_figure_tax_name').val(first_gst_figure_tax_name);
				}else{
					var second_gst_figure_tax_percentage=parseFloat($('option:selected', temp).attr('SecondGstFigure'));
					var second_gst_figure_tax_name=$('option:selected', temp).attr('SecondGstFigure');
					var second_gst_figure_id=$('option:selected', temp).attr('second_gst_figure_id');

					var netValue=amountAfterDicsount*(100+second_gst_figure_tax_percentage)/100;
					if(!netValue){netValue=0;}
					var netValue=round(netValue,2);
					
					temp.closest('tr').find('.gst_figure_id').val(second_gst_figure_id);
					temp.closest('tr').find('.gst_figure_tax_percentage').val(second_gst_figure_tax_percentage);
					temp.closest('tr').find('.gst_figure_tax_name').val(second_gst_figure_tax_name);
				}
				temp.closest('tr').find('.discountAmount').val(netValue);
				gstValue1 = round((netValue-amountAfterDicsount)/2,2);
				gstValue=gstValue1+gstValue1;
				temp.closest('tr').find('.gstValue').val(gstValue);
				gstAmount = amountAfterDicsount;
				
			}else{
				temp.closest('tr').find('.discountAmount').val(amountAfterDicsount);
				var netValuePerQty=amountAfterDicsount/quantity;
				if(netValuePerQty<=gstComparableAmount){
					var first_gst_figure_tax_percentage=parseFloat($('option:selected', temp).attr('FirstGstFigure'));
					
					var first_gst_figure_tax_name=$('option:selected', temp).attr('FirstGstFigure');
					var first_gst_figure_id=$('option:selected', temp).attr('first_gst_figure_id');
						
					temp.closest('tr').closest('tr').find('.gst_figure_id').val(first_gst_figure_id);
					temp.closest('tr').closest('tr').find('.gst_figure_tax_percentage').val(first_gst_figure_tax_percentage);
					temp.closest('tr').closest('tr').find('.gst_figure_tax_name').val(first_gst_figure_tax_name);
					
					var TaxableValue=amountAfterDicsount/(100+first_gst_figure_tax_percentage)*100;
				}else if(netValuePerQty>gstComparableAmount){
					var second_gst_figure_tax_percentage=parseFloat($('option:selected', temp).attr('SecondGstFigure')); 
					var second_gst_figure_tax_name=$('option:selected', temp).attr('SecondGstFigure');
					var second_gst_figure_id=$('option:selected', temp).attr('second_gst_figure_id');

					temp.closest('tr').find('.gst_figure_id').val(second_gst_figure_id);
					temp.closest('tr').find('.gst_figure_tax_percentage').val(second_gst_figure_tax_percentage);
					temp.closest('tr').find('.gst_figure_tax_name').val(second_gst_figure_tax_name);
					
					var TaxableValue=amountAfterDicsount/(100+second_gst_figure_tax_percentage)*100;
				}
				if(!TaxableValue){TaxableValue=0;}
				var TaxableValue=round(TaxableValue,2);
				temp.find('.gstAmount').val(TaxableValue);
				gstValue1 = round((amountAfterDicsount-TaxableValue)/2,2);
				gstValue=gstValue1+gstValue1;
				gstAmount = TaxableValue;
				temp.find('.gstValue').val(gstValue);
			}
			
			
			var is_interstate  = parseFloat($('#is_interstate').val());
			
			if(is_interstate=='0')
			{
			     exactgstvalue=round(gstValue/2,2);
			     temp.closest('tr').find('.exactgst_value').val(exactgstvalue);
			    var add_cgst  = temp.closest('tr').find('.exactgst_value').val();
				if(!add_cgst){add_cgst=0;}
				//alert(add_cgst);
				newsgst=round(parseFloat(newsgst)+parseFloat(add_cgst), 2);
				gst_amount=parseFloat(gst_amount.toFixed(2))+parseFloat(gstAmount.toFixed(2));
				total=gst_amount+newsgst+newsgst;
			    roundOff1=Math.round(total);
			}else{
			     exactgstvalue=round(gstValue,2);
			     temp.closest('tr').find('.exactgst_value').val(exactgstvalue);
				var add_igst  = parseFloat(temp.closest('tr').find('.exactgst_value').val());
				if(!add_igst){add_igst=0;}
				newigst=round(parseFloat(newigst)+parseFloat(add_igst), 2);
				gst_amount=parseFloat(gst_amount.toFixed(2))+parseFloat(gstAmount.toFixed(2));
				total=gst_amount+newigst;
			    roundOff1=Math.round(total);
			}
			
		
		
	}
	
	function final_calculation(){
		var isExcludingCalculation;
		var total  = 0;
		var gst_amount  = 0;
		var gst_value  = 0;
		var s_cgst_value=0;
		var roundOff1=0;
		var round_of=0;
		var isRoundofType=0;
		var igst_value=0;
		var outOfStockValue=0;
		var s_igst=0;
		var newsgst=0;
		var newigst=0;
		var exactgstvalue=0;
		var totDiscounts=0;
		var convertDiscount=0;
		$('#main_table tbody#main_tbody tr.main_tr').each(function(){
			
			var length=$(this).find('.is_gst_excluded:checked').length;
			if(length==1){
				isExcludingCalculation=1;
				$(this).find('.is_gstvalue_excluded').val(1);
			}else{
				isExcludingCalculation=0;
				$(this).find('.is_gstvalue_excluded').val(0);
			}
			if(!isExcludingCalculation){ isExcludingCalculation=0; }
			
			var outdata=$(this).closest('tr').find('.outStock').val();
			if(!outdata){outdata=0;}
			var outOfStockValue=parseFloat(outOfStockValue)+parseFloat(outdata);
			$('.outOfStock').val(outOfStockValue);
			
			var quantity  = $(this).find('.quantity').val();
			if(!quantity){quantity=0;}
			var quantity=round(quantity,2);
			
			var rate  = parseFloat($(this).find('.rate').val());
			if(!rate){rate=0;}
			var rate=round(rate,2);
			
			var amount=quantity*rate;
			if(!amount){amount=0;}
			var amount=round(amount,2);
			$(this).find('.totamount').val(amount);
			
			var discountValue=$(this).find('.dis_amount').val();
			var discountValue=round(discountValue,2);
			totDiscounts=round(parseFloat(totDiscounts)+parseFloat(discountValue), 2);
			
			var amountAfterDicsount=amount-discountValue;
			if(!amountAfterDicsount){amountAfterDicsount=0;}
			var amountAfterDicsount=round(amountAfterDicsount,2);
			
			var discountPercentage=discountValue*100/amount;
			var discountPercentage=round(discountPercentage,3);
			$(this).find('.discount').val(discountPercentage);
			
			var gstComparableAmount  = $(this).find('.gst_amount').val();
			
			if(isExcludingCalculation==1){
				$(this).find('.gstAmount').val(amountAfterDicsount);
				var first_gst_figure_tax_percentage=parseFloat($('option:selected', this).attr('FirstGstFigure'));
				
				var netValue=amountAfterDicsount*(100+first_gst_figure_tax_percentage)/100;
				if(!netValue){netValue=0;}
				var netValue=round(netValue,2);
				var netValuePerQty=netValue/quantity;
				if(netValuePerQty<=gstComparableAmount){
					var first_gst_figure_tax_name=$('option:selected', this).attr('FirstGstFigure');
					var first_gst_figure_id=$('option:selected', this).attr('first_gst_figure_id');
						
					$(this).closest('tr').find('.gst_figure_id').val(first_gst_figure_id);
					$(this).closest('tr').find('.gst_figure_tax_percentage').val(first_gst_figure_tax_percentage);
					$(this).closest('tr').find('.gst_figure_tax_name').val(first_gst_figure_tax_name);
				}else{
					var second_gst_figure_tax_percentage=parseFloat($('option:selected', this).attr('SecondGstFigure'));
					var second_gst_figure_tax_name=$('option:selected', this).attr('SecondGstFigure');
					var second_gst_figure_id=$('option:selected', this).attr('second_gst_figure_id');

					var netValue=amountAfterDicsount*(100+second_gst_figure_tax_percentage)/100;
					if(!netValue){netValue=0;}
					var netValue=round(netValue,2);
					
					$(this).closest('tr').find('.gst_figure_id').val(second_gst_figure_id);
					$(this).closest('tr').find('.gst_figure_tax_percentage').val(second_gst_figure_tax_percentage);
					$(this).closest('tr').find('.gst_figure_tax_name').val(second_gst_figure_tax_name);
				}
				$(this).find('.discountAmount').val(netValue);
				gstValue1 = round((netValue-amountAfterDicsount)/2,2);
				gstValue=gstValue1+gstValue1;
				$(this).find('.gstValue').val(gstValue);
				gstAmount = amountAfterDicsount;
				
			}else{
				$(this).find('.discountAmount').val(amountAfterDicsount);
				var netValuePerQty=amountAfterDicsount/quantity;
				if(netValuePerQty<=gstComparableAmount){
					var first_gst_figure_tax_percentage=parseFloat($('option:selected', this).attr('FirstGstFigure'));
					var first_gst_figure_tax_name=$('option:selected', this).attr('FirstGstFigure');
					var first_gst_figure_id=$('option:selected', this).attr('first_gst_figure_id');
						
					$(this).closest('tr').find('.gst_figure_id').val(first_gst_figure_id);
					$(this).closest('tr').find('.gst_figure_tax_percentage').val(first_gst_figure_tax_percentage);
					$(this).closest('tr').find('.gst_figure_tax_name').val(first_gst_figure_tax_name);
					
					var TaxableValue=amountAfterDicsount/(100+first_gst_figure_tax_percentage)*100;
				}else if(netValuePerQty>gstComparableAmount){
					var second_gst_figure_tax_percentage=parseFloat($('option:selected', this).attr('SecondGstFigure'));
					var second_gst_figure_tax_name=$('option:selected', this).attr('SecondGstFigure');
					var second_gst_figure_id=$('option:selected', this).attr('second_gst_figure_id');

					$(this).closest('tr').find('.gst_figure_id').val(second_gst_figure_id);
					$(this).closest('tr').find('.gst_figure_tax_percentage').val(second_gst_figure_tax_percentage);
					$(this).closest('tr').find('.gst_figure_tax_name').val(second_gst_figure_tax_name);
					
					var TaxableValue=amountAfterDicsount/(100+second_gst_figure_tax_percentage)*100;
				}
				if(!TaxableValue){TaxableValue=0;}
				var TaxableValue=round(TaxableValue,2);
				$(this).find('.gstAmount').val(TaxableValue);
				gstValue1 = round((amountAfterDicsount-TaxableValue)/2,2);
				gstValue=gstValue1+gstValue1;
				gstAmount = TaxableValue;
				$(this).find('.gstValue').val(gstValue);
			}
			
			
			var is_interstate  = parseFloat($('#is_interstate').val());
			
			if(is_interstate=='0')
			{
			     exactgstvalue=round(gstValue/2,2);
			     $(this).find('.exactgst_value').val(exactgstvalue);
			    var add_cgst  = $(this).find('.exactgst_value').val();
				if(!add_cgst){add_cgst=0;}
				//alert(add_cgst);
				newsgst=round(parseFloat(newsgst)+parseFloat(add_cgst), 2);
				gst_amount=parseFloat(gst_amount.toFixed(2))+parseFloat(gstAmount.toFixed(2));
				total=gst_amount+newsgst+newsgst;
			    roundOff1=Math.round(total);
			}else{
			     exactgstvalue=round(gstValue,2);
			     $(this).find('.exactgst_value').val(exactgstvalue);
				var add_igst  = parseFloat($(this).find('.exactgst_value').val());
				if(!add_igst){add_igst=0;}
				newigst=round(parseFloat(newigst)+parseFloat(add_igst), 2);
				gst_amount=parseFloat(gst_amount.toFixed(2))+parseFloat(gstAmount.toFixed(2));
				total=gst_amount+newigst;
			    roundOff1=Math.round(total);
			}
			if(total<roundOff1)
			{
				round_of=parseFloat(roundOff1)-parseFloat(total);
				isRoundofType='0';
			}
			if(total>roundOff1)
			{
				round_of=parseFloat(roundOff1)-parseFloat(total);
				isRoundofType='1';
			}
			if(total==roundOff1)
			{
				round_of=parseFloat(total)-parseFloat(roundOff1);
				isRoundofType='0';
			}
			
		});
		
		$('.amount_after_tax').val(roundOff1.toFixed(2));
		$('.amount_before_tax').val(gst_amount.toFixed(2));
		$('.add_cgst').val(newsgst);
		$('.add_sgst').val(newsgst);
		$('.add_igst').val(newigst);					
		$('.roundValue').val(round_of.toFixed(2));
		$('.isRoundofType').val(isRoundofType);
		$('.outOfStock').val(outOfStockValue);
		$('.toalDiscount').val(totDiscounts);
		//rename_rows();
	}
	
	$('.discalculation').die().live('blur',function()
	{
		var currentObj=$(this);
		reverse_total_amount(currentObj);
	});
	
	function reverse_total_amount(currentObj){
		var qty=currentObj.closest('.main_tr').find('.quantity').val();
		if(!qty){qty=0;}
		var qty=round(qty,2);
		
		var rate=currentObj.closest('.main_tr').find('.rate').val();
		if(!rate){rate=0;}
		var rate=round(rate,2);
		
		var totalAmount=qty*rate;
		
		var discountPer=currentObj.closest('.main_tr').find('.discalculation').val();
		if(!discountPer){discountPer=0;}
		var discountPer=round(discountPer,3);
		
		var DiscountValue=(totalAmount*discountPer)/100;
		if(!DiscountValue){DiscountValue=0;}
		var DiscountValue=round(DiscountValue,2);
		
		currentObj.closest('.main_tr').find('.dis_amount').val(DiscountValue);
		
		forward_total_amount();
	}
	
	
	
	function checkValidation() 
	{  
		final_calculation();
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
		
	}";
	
	$js.="
	$(document).ready(function() {
	$('.quantity,.discount,.dis_amount').keypress(function(event) {
			if ( event.which == 45 || event.which == 189 ) {
			event.preventDefault();
		}
		}); });";
echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>

<input type="hidden" class="addcategories" 
value="<?php echo $this->Url->build(['controller'=>'Customers','action'=>'addNew']); ?>">
<div id="myModal2" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false" style="display: none; padding-right: 12px;"><div class="modal-backdrop fade in" ></div>
	<div class="modal-dialog">
		<div class="modal-content">
		<?= $this->Form->create(@$Customers,['id'=>'category_data_form','type'=>'file']) ?>
			<div class="modal-body" id="result_ajax">
			<h4>Commercial Terms & Conditions</h4>
				<div style=" overflow: auto; height: 450px;">
				<table class="table table-hover tabl_tc">
					<tr>
						<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Customer Name <span class="required">*</span></label>
									<?php echo $this->Form->control('name',['class'=>'form-control input-sm','placeholder'=>'Customer Name','label'=>false,'autofocus']); ?>
								</div>
								
								<div class="form-group">
									<label>GSTIN </label>
									<?php echo $this->Form->control('gstin',['class'=>'form-control input-sm gst','placeholder'=>'Eg:22ASDFR0967W6Z5','label'=>false,'required'=>'']); ?>
								</div>
								<div class="form-group">
									<label>Mobile </label>
									<?php echo $this->Form->control('mobile',['class'=>'form-control input-sm','placeholder'=>'Mobile no','label'=>false,'autofocus','maxlength'=>10]); ?>
								</div>
								
								
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>State <span class="required">*</span></label>
									<?php echo $this->Form->control('state_id',['class'=>'form-control input-sm ','label'=>false,'empty'=>'-State-', 'options' => @$states,'required'=>'required']); ?>
								</div>
								<div class="form-group">
									<label>City <span class="required">*</span></label>
									<?php echo $this->Form->control('city_id',['class'=>'form-control input-sm ','label'=>false,'empty'=>'-City-', 'options' => @$cities,'required'=>'required']); ?>
								</div>
							</div>
						</div>
						
					</div>
					</tr>
				</table>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn default closebtn2">Close</button>
				<button  type="submit" class="btn btn-primary ">Save</button>
			</div>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
