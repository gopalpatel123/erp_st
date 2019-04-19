<?php
$url_excel="/?".$url; 
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Trial balance report');
?>

<?php
	if($status=='excel'){
		$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="trialbalance_report_".$date.'_'.$time;
	//$from_date=date('d-m-Y',strtotime($from_date));
	//$to_date=date('d-m-Y',strtotime($to_date));
	
	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" ); 
	echo '<table border="0"><tr style="font-size:14px;"><td colspan="7" align="center" style="text-align:center;">'.$companies->name .'<br/>' .$companies->address .',<br/>'. $companies->state->name .'</span><br/>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>'.  $companies->phone_no . ' | Mobile : '. $companies->mobile .'<br/> GSTIN NO:'.
				$companies->gstin .'</span></td></tr></table>';
	}

 ?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
			<?php if($status!='excel'){ ?>
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Trial Balance Report</span>
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Ledgers/trialBalance/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<form method="get">
					<div class="col-md-3">
						<div class="form-group">
							<label>From Date</label>
							<?php 
							if(@$from_date=='1970-01-01')
							{
								$from_date = '';
							}
							elseif(!empty($from_date))
							{
								$from_date = date("d-m-Y",strtotime(@$from_date));
							}
							echo $this->Form->control('from_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker from_date','data-date-format '=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','value'=>@$from_date,'data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo]]); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>To Date</label>
							<?php 
							if(@$to_date=='1970-01-01')
							{
								$to_date = '';
							}
							elseif(!empty($to_date))
							{
								$to_date = date("d-m-Y",strtotime(@$to_date));
							}
							echo $this->Form->control('to_date',['autocomplete'=>'off','class'=>'form-control input-sm date-picker to_date','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','value'=>@$to_date,'data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo]]); ?>
						</div>
					</div>
					<div class="col-md-2" >
							<div class="form-group" style="padding-top:22px;"> 
								<button type="submit" class="btn btn-xs blue input-sm srch">Go</button>
							</div>
					</div>	
					</form>
				</div>
				<?php 
				}
				if(!empty($ClosingBalanceForPrint))
				{
				?>
				<table class="table table-bordered table-hover table-condensed" width="100%">
					<thead>
						<tr>
							<th scope="col" width="25%"></th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";>Opening Balance</th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";>Transactions</th>
							<th width="25%" scope="col" colspan="2" style="text-align:center";>Closing balance</th>
						</tr>
						<tr>
							<th scope="col">Ledgers</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
							<th scope="col" style="text-align:center";>Debit</th>
							<th scope="col" style="text-align:center";>Credit</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$openingBalanceDebitTotal=0;
							$openingBalanceCreditTotal=0;
							$transactionDebitTotal=0;
							$transactionCreditTotal=0;
							$closingBalanceDebitTotal=0;
							$closingBalanceCreditTotal=0;
							$total1=0;
							$total2=0;
							foreach($ClosingBalanceForPrint as $key=>$ClosingBalance)
							{ //pr(@$OpeningBalanceForPrint[$key]['balance']);
								    $closing_credit=0;
									$closing_debit=0;
							?>
									<tr>
										<td style="width:200px"><a href="#" role='button' status='close' class="group_name" child='no' parent='yes' group_id='<?php  echo $key; ?>' style='color:black;'>
														<?php echo $ClosingBalance['name']; ?>
															 </a>
													</td>
										<?php if(@$OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($OpeningBalanceForPrint[$key]['balance']));
											$openingBalanceDebitTotal +=abs($OpeningBalanceForPrint[$key]['balance']);
										?>
										</td>
										<td scope="col" align="right">-</td>
										<?php } else{ ?>
										<td scope="col" align="right">-</td>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs(@$OpeningBalanceForPrint[$key]['balance']));
											$openingBalanceCreditTotal +=abs($OpeningBalanceForPrint[$key]['balance']);
										?>
										</td>
										<?php }?>
										<td scope="col" align="right">
										<?php echo $this->Money->moneyFormatIndia(abs(@$TransactionsDr[$key]['balance'])); ?>
										</td>
										<td scope="col" align="right">
										<?php echo $this->Money->moneyFormatIndia(abs(@$TransactionsCr[$key]['balance'])); ?>
										</td>
										<?php if(@$ClosingBalance['balance'] > 0){ ?>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($ClosingBalance['balance']));
											$closingBalanceDebitTotal+=abs($ClosingBalance['balance']);
										?>
										</td>
										<td scope="col" align="right">-</td>
										<?php } else{ ?>
										<td scope="col" align="right">-</td>
										<td scope="col" align="right">
										<?php
										
											echo $this->Money->moneyFormatIndia(abs($ClosingBalance['balance']));
											$closingBalanceCreditTotal +=abs($ClosingBalance['balance']);
										?>
										</td>
										<?php }?>
									</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						
						<tr>
							
							<th colspan="5" style="text-align:left";>Opening Stock</th>
							<th  style="text-align:right";>
								<?php echo $openingValue; ?>
							</th>
							<th style="text-align:right";></th>
						</tr>
						<tr style="color:red;">
							<th colspan="5" style="text-align:left";>Diffrence of opening balance</th>
							
								<?php if($openingBalanceDebitTotal>@$openingBalanceCreditTotal)
									{
										$cedit_diff = $openingBalanceDebitTotal-@$openingBalanceCreditTotal;?>
										<th  style="text-align:right";>
										</th>
										<th style="text-align:right";><?php echo $this->Money->moneyFormatIndia(@$cedit_diff); ?></th>
										
								<?php } else {  
									 ?>
										
										<th style="text-align:right";><?php echo $this->Money->moneyFormatIndia(@$cedit_diff); ?></th>
										<th  style="text-align:right";>
										</th>
								<?php } ?>
							
						</tr>
						<tr>
							<th colspan="5" style="text-align:left";>Total</th>
							
							<th scope="col" style="text-align:right";>
							<?php echo $this->Money->moneyFormatIndia(@$closingBalanceDebitTotal); ?>
							</th>
							<th scope="col" style="text-align:right";>
							<?php echo $this->Money->moneyFormatIndia(round(@$closingBalanceCreditTotal,2)); ?>
							</th>
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
						var url='".$this->Url->build(['controller'=>'AccountingEntries','action'=>'firstSubGroupsTb']) ."';
						url=url+'/'+group_id +'/'+from_date+'/'+to_date, 
						$.ajax({
							url: url,
						}).done(function(response) { 
							current_obj.attr('status','open');
							current_obj.attr('child','yes');
							 current_obj.addClass('group_a');
							
							current_obj.closest('tr').find('span').addClass('group_a');
							var a='<tr class=append_tr row_for_'+group_id+'><td colspan=7>'+response+'</td></tr>';
							$(a).insertAfter(current_obj.closest('tr'));
						});	
					}  
		  
			});	
         ComponentsPickers.init();
	})";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>