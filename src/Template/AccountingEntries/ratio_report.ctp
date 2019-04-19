<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Ratio Analysis');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>Ratio Analysis
				</div>
			</div>
			<div class="portlet-body">
				<form method="get">
						<div class="row">
							<div class="col-md-3">
								<?php echo $this->Form->control('to_date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($to_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<span class="input-group-btn">
								<button class="btn blue" type="submit">Go</button>
								</span>
							</div>	
						</div>
				</form>
				<?php if($to_date){ 
				$LeftTotal=0; $RightTotal=0; ?>
				<div class="row">
					<table class="table table-bordered">
						<thead>
							<tr style="background-color: #c4ffbd;">
								<td>
									<table width="100%">
										<tbody>
											<tr>
												<td><b>Principal Groups</b></td>
												
											</tr>
										</tbody>
									</table>
								</td>
								<td>
									<table width="100%">
										<tbody>
											<tr>
												<td><b>Principal Ratios</b></td>
												
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<table width="100%">
										<tbody>
											<?php if($openingValue>=0) { ?>
												<tr>
													<td>Opening Stock</td>
													<td align="right">
														<?php 
														echo $openingValue;
														$LeftTotal+=$openingValue;
														?>
													</td>
												</tr>
											<?php } ?>
											<?php foreach($groupForPrint as $groupForPrintRow){ 
												if(($groupForPrintRow['balance']>0) or ($groupForPrintRow['balance']==0 && $groupForPrintRow['nature']==4)){ ?>
												<tr>
													<td><?php echo $groupForPrintRow['name']; ?></td>
													<td align="right">
														<?php if($groupForPrintRow['balance']!=0){
															echo abs($groupForPrintRow['balance']);
															$LeftTotal+=abs($groupForPrintRow['balance']);
														} ?>
													</td>
												</tr>
												<?php } ?>
											<?php } ?>
										</tbody>
									</table>
								</td>
								<td>
									<table width="100%">
										<tbody>
											<?php if($openingValue<0) { ?>
												<tr>
													<td>Opening Stock</td>
													<td align="right">
														<?php 
														echo $openingValue;
														$RightTotal+=$openingValue;
														?>
													</td>
												</tr>
											<?php } ?>
											<?php foreach($groupForPrint as $groupForPrintRow){ 
												if(($groupForPrintRow['balance']<0) or ($groupForPrintRow['balance']==0 && $groupForPrintRow['nature']==3)){ ?>
												<tr>
													<td><?php echo $groupForPrintRow['name']; ?></td>
													<td align="right">
														<?php if($groupForPrintRow['balance']!=0){
															echo abs($groupForPrintRow['balance']); 
															$RightTotal+=abs($groupForPrintRow['balance']); 
														} ?>
													</td>
												</tr>
												<?php } ?>
											<?php } ?>
												<tr>
													<td>Closing Stock</td>
													<td align="right">
														<?php 
														echo $closingValue; 
														$RightTotal+=$closingValue; 
														?>
													</td>
												</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<?php 
									$totalDiff=$RightTotal-$LeftTotal;
									if($totalDiff>=0){ ?>
									<table width="100%">
										<tbody>
											<tr>
												<td>Gross Profit</td>
												<td align="right">
													<?php echo $totalDiff; $LeftTotal+=$totalDiff; ?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php } ?>
								</td>
								<td>
									<?php if($totalDiff<0){ ?>
									<table width="100%">
										<tbody>
											<tr>
												<td>Gross Loss</td>
												<td align="right">
													<?php echo abs($totalDiff); $RightTotal+=abs($totalDiff); ?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php } ?>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td>
									<table width="100%">
										<tbody>
											<tr>
												<td><b>Total</b></td>
												<td align="right"><b><?php echo $LeftTotal; ?></b></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td>
									<table width="100%">
										<tbody>
											<tr>
												<td><b>Total</b></td>
												<td align="right"><b><?php echo $RightTotal; ?></b></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tfoot>
					</table>
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

