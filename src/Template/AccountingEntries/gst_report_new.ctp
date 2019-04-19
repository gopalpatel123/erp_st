<?php
 $url_excel="/?".$url; 
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'GSTR 3B REPORT');
?>
<?php
	if($status=='excel'){
		$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="GSTR3B_report_".$date.'_'.$time;
	//$from_date=date('d-m-Y',strtotime($from_date));
	//$to_date=date('d-m-Y',strtotime($to_date));
	
	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" ); 
	echo '<table border="1"><tr style="font-size:14px;"><td colspan="6" align="center" style="text-align:center;">'.$companies->name .'<br/>' .$companies->address .',<br/>'. $companies->state->name .'</span><br/>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>'.  $companies->phone_no . ' | Mobile : '. $companies->mobile .'<br/> GSTIN NO:'.
				$companies->gstin .'</span></td></tr></table>';
	}

 ?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
		<?php if($status!='excel'){ ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>GST REPORT
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/AccountingEntries/gstReportNew/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
			<?php } ?>
			<div class="portlet-body">
			<?php if($status!='excel'){ ?>
				<form method="get">
						<div class="row">
							<div class="col-md-3">
								<?php echo $this->Form->control('from_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker from_date','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($from_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->control('to_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker to_date','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($to_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<span class="input-group-btn">
								<button class="btn blue" type="submit">Go</button>
								</span>
							</div>	
						</div>
				</form>
				<?php } ?>
				<?php if($from_date){ 
				$LeftTotal=0; $RightTotal=0; ?>
				<div class="row">
							<div class="form-group" >
								<div class="col-md-12" align="center">
								</div>
							</div>
								<div class="col-md-10" align="left" style="font-weight: bold">
								3.1 Details of Outward Supplies and inward supplies liable to reverse charge
									<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											<tr>
												<th style="background-color:#a3bad0"  scope="col">Nature of supply</th>
												<th style="background-color:#a3bad0"  scope="col">Total Taxable Value</th>
												<th style="background-color:#a3bad0"  scope="col" >Integrated Tax</th>
												<th style="background-color:#a3bad0"  scope="col" >Central Tax</th>
												<th style="background-color:#a3bad0"  scope="col" >State/UT Tax</th>
												<th style="background-color:#a3bad0"  scope="col" >Cess</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>(a) Outword taxable supplies(Other than zero rated,nil rated and exempted)</td>
												<td><?php echo $TotalTaxable; ?></td>
												<td><?php echo $TotalIGst; ?></td>
												<td><?php echo $TotalCGst; ?></td>
												<td><?php echo $TotalSGst; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<tr>
												<td>(b) Outward taxable supplies (zero rated)</td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<tr>
												<td>(c) Other outward supplies (Nil rated, exempted)</td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<tr>
												<td>(d) Inward supplies (liable to reverse charge)</td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<tr>
												<td>(e)Non-GST outward supplies</td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<br>
								<div class="col-md-10" align="left" style="font-weight: bold">
								3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons, composition taxable persons and UIN holders
									<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											<tr>
												<th style="background-color:#a3bad0"  scope="col"></th>
												<th style="background-color:#a3bad0"  scope="col">Place of Supply(State/UT)</th>
												<th style="background-color:#a3bad0"  scope="col" >Total Taxable Value</th>
												<th style="background-color:#a3bad0"  scope="col" >Amount of Integrated Tax</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($StateWiseTaxableAmt as $key=>$data){ ?>
											<tr>
												<td>Supplies made to unregistered Persons</td>
												<td><?php echo $StateName[$key]; ?></td>
												<td><?php echo $StateWiseTaxableAmt[$key]; ?></td>
												<td><?php echo $StateWiseGst[$key]; ?></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								
								<br>
								<div class="col-md-10" align="left" style="font-weight: bold">4. Eligible ITC
									<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											<tr>
												<th style="background-color:#a3bad0"  scope="col">Details</th>
												<th style="background-color:#a3bad0"  scope="col">Integrated Tax</th>
												<th style="background-color:#a3bad0"  scope="col">Central Tax</th>
												<th style="background-color:#a3bad0"  scope="col">State/UT Tax</th>
												<th style="background-color:#a3bad0"  scope="col">Cess</th>
												
											</tr>
										</thead>
										<tbody>
											<?php //foreach($StateWiseTaxableAmt as $key=>$data){ ?>
											<tr>
												<td>Net ITC</td>
												<td><?php echo @$PurchaseTotalIGst; ?></td>
												<td><?php echo @$PurchaseTotalGst/2; ?></td>
												<td><?php echo @$PurchaseTotalGst/2; ?></td>
												<td></td>
											</tr>
											<?php //} ?>
										</tbody>
									</table>
								</div>
								<br>
								<div class="col-md-10" align="left" style="font-weight: bold">5. Values of exempt, nil-rated and non-GST inward supplies
									<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											<tr>
												<th style="background-color:#a3bad0"  scope="col">Nature of supplies</th>
												<th style="background-color:#a3bad0"  scope="col">Inter-State supplies</th>
												<th style="background-color:#a3bad0"  scope="col">Intra-State supplies</th>
												
											</tr>
										</thead>
										<tbody>
											<?php //foreach($StateWiseTaxableAmt as $key=>$data){ ?>
											<tr>
												<td>From a supplier under composition scheme, Exempt and Nil rated supply</td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<tr>
												<td>Non GST supply</td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<?php //} ?>
										</tbody>
									</table>
								</div>
								<br>
								<div class="col-md-10" align="left" style="font-weight: bold">5.1 Payment of tax
								<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											<tr>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;" align="center" rowspan="2">Description</th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;"  rowspan="2">Tax payable</th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;"  colspan="4">Paid through ITC</th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;"  rowspan="2">Tax paid TDS/TCS</th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;"  rowspan="2">Tax/Cess paid in cash</th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;"  rowspan="2">Interest</th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;"  rowspan="2">Late Fee</th>
											</tr>
											<tr>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;" >Integrated Tax </th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;" >Central  Tax </th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;" >State/UT  Tax </th>
												<th style="background-color:#a3bad0;text-align:center;vertical-align: middle;" >Cess</th>
											</tr>
										</thead>
										<tbody>
										
												
												
											<?php //foreach($StateWiseTaxableAmt as $key=>$data){ ?>
											<tr>
												<td >Integrated Tax </td>
												<td><?php echo $TotalIGst; ?></td>
												<td><?php echo @$PurchaseTotalIGst; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												
											</tr>
											<tr>
												<td >Central  Tax </td>
												<td><?php echo $TotalCGst; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo @$PurchaseTotalGst/2; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												
											</tr>
											<tr>
												<td >State/UT  Tax </td>
												<td><?php echo $TotalSGst; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo @$PurchaseTotalGst/2; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<tr>
												<td  >Cess</td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											
											<?php //} ?>
										</tbody>
									</table>
								</div>
								
								<br>
								<div class="col-md-10" align="left" style="font-weight: bold">6 TDS/TCS Credit
									<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											<tr>
												<th style="background-color:#a3bad0"  scope="col">Details</th>
												<th style="background-color:#a3bad0"  scope="col">Integrated Tax</th>
												<th style="background-color:#a3bad0"  scope="col">Central Tax</th>
												<th style="background-color:#a3bad0"  scope="col">State/UT Tax</th>
												
											</tr>
										</thead>
										<tbody>
											<?php //foreach($StateWiseTaxableAmt as $key=>$data){ ?>
											<tr>
												<td>TDS </td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<tr>
												<td>TDC</td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
												<td><?php echo "0.00"; ?></td>
											</tr>
											<?php //} ?>
										</tbody>
									</table>
								</div>
								<br>
							</div>
					<?php } ?>
				</ul>
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
	$js="
		$(document).ready(function() {
			$('.group_name').die().live('click',function(e){
				   var current_obj=$(this);
				   var group_id=$(this).attr('group_id');
				   var child=$(this).attr('child');
				   var status=$(this).attr('status');
				   var parent=$(this).attr('parent');
					if(child == 'yes' && status=='open' && parent=='no')
					{
						current_obj.attr('status','open');
						current_obj.attr('child','no');
						current_obj.closest('tr').next().remove();
						
					}else if(status=='open' && parent=='yes')
					{ 
						current_obj.attr('status','close');
						current_obj.attr('child','no');
						current_obj.closest('tr').next().remove();
						
						
					} else{  
						var from_date = $('.from_date').val();
						var to_date = $('.to_date').val(); 
						var url='".$this->Url->build(['controller'=>'AccountingEntries','action'=>'firstSubGroupsPnl']) ."';
						url=url+'/'+group_id +'/'+from_date+'/'+to_date,
						$.ajax({
							url: url,
						}).done(function(response) { 
							current_obj.attr('status','open');
							current_obj.attr('child','yes');
							 current_obj.addClass('group_a');
							current_obj.closest('tr').find('span').addClass('group_a');
							var a='<tr><td colspan=2>'+response+'</td></tr>';
							$(a).insertAfter(current_obj.closest('tr'));
						});	
					}  
		  
			});	
			
		

			ComponentsPickers.init();

			
		});
	";
?>
<?php echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom'));  ?>

