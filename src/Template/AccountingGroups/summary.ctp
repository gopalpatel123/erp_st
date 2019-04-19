<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Accouting Group Summary');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Accouting Group Summary</span>
				</div>
				<div class="actions">
					
				</div>
			</div>
		
			<div class="portlet-body">
				<div class="row">
					<form method="GET" >
						<div class="col-md-3">
							<div class="form-group">
								<label>Accouting Groups</label>
								<?php 
								echo $this->Form->input('accounting-group-id', ['options'=>$AccountingGroups,'label' => false,'class' => 'form-control input-sm select2me' ,'value'=>$AccountingGroupId, 'required'=>'required']); 
								?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>From Date</label>
								<?php 
								if(!empty($from_date)){
									$from_date = date("d-m-Y",strtotime(@$from_date));
								}else{
									$from_date = @$coreVariable[fyValidFrom];
								}
								echo $this->Form->control('from-date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','value'=>@$from_date,'data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'required'=>'required']); ?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>To Date</label>
								<?php 
								if(!empty($to_date)){
									$to_date = date("d-m-Y",strtotime(@$to_date));
								}else{
									$to_date = @$coreVariable[fyValidTo];
								}
								echo $this->Form->control('to-date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','value'=>@$to_date,'data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'required'=>'required']); ?>
							</div>
						</div>
						<div class="col-md-2" >
							<div class="form-group" style="padding-top:22px;"> 
								<button type="submit" class="btn btn-xs blue input-sm srch"> Go</button>
							</div>
						</div>	
					</form>
				</div>
				<?php
				if(!empty($subGroups))
				{
				?>
					<table class="table table-bordered table-hover table-condensed" width="100%" border="1">
						<thead>
							<tr>
								<th width="70%" rowspan="2">Particulars</th>
								<th colspan="2" style="text-align:center">Closing Balance</th>
							</tr>
							<tr>
								<th style="text-align:center">Debit</th>
								<th style="text-align:center">Credit</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$totalDr=0; $totalCr=0;
							foreach($subGroups as $subGroup){ ?>
							<tr>
								<td>
									<?= $this->Html->link(__($subGroup->name), ['controller'=>'AccountingGroups','action' => 'summary?accounting-group-id='.$subGroup->id.'&from_date='.$from_date.'&to_date='.$to_date],['target'=>'_blank']) ?>
								</td>
								<td style="text-align:right">
									<?php if($subGroupBalances[$subGroup->id]>0){
										echo $subGroupBalances[$subGroup->id];
										$totalDr+=$subGroupBalances[$subGroup->id];
									} ?>
								</td>
								<td style="text-align:right">
									<?php if($subGroupBalances[$subGroup->id]<0){
										echo abs($subGroupBalances[$subGroup->id]);
										$totalCr+=abs($subGroupBalances[$subGroup->id]);
									} ?>
								</td>
							</tr>
							<?php } ?>
							<?php foreach($Ledgers as $Ledger){ ?>
							<tr>
								<td><?= h($Ledger->name) ?></td>
								<td style="text-align:right">
									<?php if($ledgerBalances[$Ledger->id]>0){
										echo $ledgerBalances[$Ledger->id];
										$totalDr+=$ledgerBalances[$Ledger->id];
									} ?>
								</td>
								<td style="text-align:right">
									<?php if($ledgerBalances[$Ledger->id]<0){
										echo abs($ledgerBalances[$Ledger->id]);
										$totalCr+=abs($ledgerBalances[$Ledger->id]);
									} ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th width="70%" style="text-align:right">Total</th>
								<th style="text-align:right"><?= h($totalDr) ?></th>
								<th style="text-align:right"><?= h($totalCr) ?></th>
							</tr>
						</tfoot>
					</table>
				<?php } ?>
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
	})";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>