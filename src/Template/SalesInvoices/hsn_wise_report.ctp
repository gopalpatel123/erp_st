<?php
 $url_excel="/?".$url;

/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'HSN WISE REPORT');
?>

<style>
table th {
    white-space: nowrap;
	font-size:12px !important;
}
table td {
	white-space: nowrap;
	font-size:11px !important;
}

<?php
	if($status=='excel'){
		$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Invoice_report_".$date.'_'.$time;
	//$from_date=date('d-m-Y',strtotime($from_date));
	//$to_date=date('d-m-Y',strtotime($to_date));
	
	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" ); 
	
	}

 ?>

</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
		<?php if($status!='excel'){ ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>HSN WISE REPORT
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/SalesInvoices/hsnWiseReport/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
			<?php } ?>
			<div class="portlet-body">
				<form method="get">
						<div class="row">
							<div class="col-md-3">
								<?php echo $this->Form->control('From',['autocomplete'=>'off','class'=>'form-control input-sm date-picker from_date','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($From)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->control('To',['autocomplete'=>'off','class'=>'form-control input-sm date-picker to_date','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($To)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<span class="input-group-btn">
								<button class="btn blue" type="submit">Go</button>
								</span>
							</div>	
						</div>
				</form>
				<?php if($From){ 
				$LeftTotal=0; $RightTotal=0; ?>
				<div class="row">
							<div class="form-group" >
								<div class="col-md-12" align="center">
								</div>
							</div>
					
								<div class="col-md-12" align="center" style="font-weight: bold">
										
										<table class="table table-bordered  table-condensed" width="100%" border="1">
										<thead>
											
											<tr>
												<th style="text-align:center;background-color:#a3bad0"  scope="col">S.N</th>
												<th style="text-align:center;background-color:#a3bad0"  scope="col">HSN No.</th>
												<th style="text-align:center;background-color:#a3bad0"  scope="col">Item Group</th>
												<th style="text-align:center;background-color:#a3bad0"  scope="col">Unit</th>
												<th style="text-align:center;background-color:#a3bad0"  scope="col">Quantity</th>
												<th style="text-align:center;background-color:#a3bad0"  scope="col">Taxable</th>
												<th style="text-align:center;background-color:#a3bad0"  scope="col" >GST Amount</th>
												<th style="text-align:center;background-color:#a3bad0"  scope="col" >Total Value</th>
											</tr>
										</thead>
										<tbody>
										<?php $i=0; $total_taxable=0; $total_tax=0; $total=0; $total_qty=0; 
										foreach ($hsn as $hsn): 	 
											if($hsn){	$i++; 	?>
											<tr>
												<td style="text-align:center;"><?= h($i) ?></td>
												<td style="text-align:center;"><?= h($hsn) ?></td>
												<td style="text-align:center;"><?= h($item_category[$hsn]) ?></td>
												<td style="text-align:center;"><?= h($unit[$hsn]) ?></td>
												<td style="text-align:center;"><?= h($quantity[$hsn]) ?></td>
												<td style="text-align:center;"><?= h(round($taxable_value[$hsn],2)) ?></td>
												<td style="text-align:center;"><?= h(round($gst[$hsn],2)) ?></td>
												<td style="text-align:center;"><?= h(round($total_value[$hsn],2)) ?></td>
												<?php 
												$total_qty+=$quantity[$hsn];
												$total_taxable+=$taxable_value[$hsn];
												$total_tax+=$gst[$hsn];
												$total+=$total_value[$hsn];
												?>
												
											</tr>
											<?php } endforeach; ?>
										</tbody>
										
										<tfoot>
											<tr>
												<td colspan="4" scope="col"  style="text-align:right";><b>Total GST</b></td>
												<td scope="col" style="text-align:center;"><b><?php echo $this->Number->format(@$total_qty,['places'=>2]); ?></b></td>
												<td scope="col" style="text-align:center;"><b><?php echo $this->Number->format(@$total_taxable,['places'=>2]); ?></b></td>
												<td scope="col" style="text-align:center"><b><?php echo $this->Number->format(@$total_tax,['places'=>2]); ?></b></td>
												<td scope="col" style="text-align:center"><b><?php echo $this->Number->format(@$total,['places'=>2]); ?></b></td>
												
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
			
			
		

			ComponentsPickers.init();

			
		});
	";
?>
<?php echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom'));  ?>