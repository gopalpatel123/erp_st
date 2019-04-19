<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'GST REPORT');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>GST REPORT
				</div>
				<div class="actions">
				<?php 
						$class1="btn btn-xs blue";
						$class2="btn btn-xs blue";
					
					?>
						<?php echo $this->Html->link('GSTR 1',['controller'=>'SalesInvoices','action' => 'gstReport'],['escape'=>false,'class'=>$class1,'style'=>'padding: 1px 5px;']); ?>
						<?php echo $this->Html->link('GSTR 3B',['controller'=>'AccountingEntries','action' => 'gstReportNew'],['escape'=>false,'class'=>$class2,'style'=>'padding: 1px 5px;']); ?>&nbsp;
					<?php  ?>
				</div>
			</div>
			<div class="portlet-body">
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
				<?php if($from_date){ 
				$LeftTotal=0; $RightTotal=0; ?>
				<div class="row">
							<div class="form-group" >
								<div class="col-md-12" align="center">
								</div>
						</div>
					
								<div class="col-md-6" align="center" style="font-weight: bold">OUTPUT TAX (GST) - SALES
										<?php $purchase="SALES REPORT";  ?>
										
										<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											
											<tr>
												<th style="background-color:#a3bad0"  scope="col">GST Type</th>
												<th style="background-color:#a3bad0"  scope="col">Taxable</th>
												<th style="background-color:#a3bad0"  scope="col" >GST Amount</th>
											</tr>
										</thead>
										<tbody>
										<?php $totalgstoutput=0; $totalgstoutputtaxable=0; foreach($GstFigures as $GstFigure) {?>
											<tr>
												<td><?php echo $GstFigure->name; ?></td>
												<td scope="col" style="text-align:right";><b><?php echo @$taxable_gst_wise[@$GstFigure->id]; ?></b></td>
												<?php $totalgstoutputtaxable+=@$taxable_gst_wise[@$GstFigure->id]; ?>
												<td  align="right"><?php echo @$outputgst[@$GstFigure->id]; ?></td>
												<?php $totalgstoutput+=@$outputgst[@$GstFigure->id]; ?>
											</tr>
										<?php } ?>
										</tbody>
										
										<tfoot>
											<tr>
												<td scope="col"  style="text-align:right";><b>Total GST</b></td>
												<td scope="col" style="text-align:right";><b><?php echo $this->Number->format(@$totalgstoutputtaxable,['places'=>2]); ?></b></td>
												<td scope="col" style="text-align:right";><b><?php echo $this->Number->format(@$totalgstoutput,['places'=>2]); ?></b></td>
												
											</tr>
											
										</tfoot>
									</table>
								</div>
								<div class="col-md-6" align="center" style="font-weight: bold">OUTPUT TAX (IGST) - SALES
										<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											
											<tr>
												<th style="background-color:#a3bad0"  scope="col">GST Type</th>
												<th style="background-color:#a3bad0"  scope="col">Taxable</th>
												<th style="background-color:#a3bad0"  scope="col" >GST Amount</th>
											</tr>
										</thead>
										<tbody>
										<?php $totalgstoutput1=0; $totalgstoutputtaxable=0; foreach($GstFigures as $GstFigure) {?>
											<tr>
												<td><?php echo $GstFigure->name; ?></td>
												<?php if(@$outputIgst[@$GstFigure->id]){ ?>
												<td scope="col" style="text-align:right";><b><?php echo @$taxable_gst_wise[@$GstFigure->id]; ?></b></td>
												<?php $totalgstoutputtaxable+=@$taxable_gst_wise[@$GstFigure->id]; ?>
												<?php }else{ ?>
												<td scope="col" style="text-align:right";><b></b></td>
												<?php } ?>
												<td  align="right"><?php echo @$outputIgst[@$GstFigure->id]; ?></td>
												<?php $totalgstoutput1+=@$outputIgst[@$GstFigure->id]; ?>
											</tr>
										<?php } ?>
										</tbody>
										
										<tfoot>
											<tr>
												<td scope="col"  style="text-align:right";><b>Total IGST</b></td>
												<td scope="col" style="text-align:right";><b><?php echo $this->Number->format(@$totalgstoutputtaxable,['places'=>2]); ?></b></td>
												<td scope="col" style="text-align:right";><b><?php echo $this->Number->format(@$totalgstoutput1,['places'=>2]); ?></b></td>
												
												
											</tr>
											
										</tfoot>
									</table>
								</div>
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

