<?php
 $url_excel="/?".$url; 
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Sales Invoice List');
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
	echo '<table border="1"><tr style="font-size:14px;"><td colspan="20" align="center" style="text-align:center;">'.$companies->name .'<br/>' .$companies->address .',<br/>'. $companies->state->name .'</span><br/>
				<span> <i class="fa fa-phone" aria-hidden="true"></i>'.  $companies->phone_no . ' | Mobile : '. $companies->mobile .'<br/> GSTIN NO:'.
				$companies->gstin .'</span></td></tr></table>';
	}

 ?>

</style>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
		<?php if($status!='excel'){ ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Sales Invoice</span>
				</div>
				<div class="actions">
					
					
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/SalesInvoices/invoiceReport/'.@$url_excel.'&status=excel',['class' =>'btn btn-sm green tooltips pull-right','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
				</div>
		<?php } ?>
			
			<div class="portlet-body">
				<div class="table-responsive">
					<?php  $page_no=0; 
									?>						
					<table class="table table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th scope="col" style="text-align:center"><?= __('Sr') ?></th>
								<th scope="col" style="text-align:center"><?= __('Invoice No') ?></th>
								<th scope="col" style="text-align:center"><?= __('Transaction date') ?></th>
								<th scope="col" style="text-align:center"><?= __('Taxable Amount') ?></th>
								<th scope="col" style="text-align:center"><?= __('GST Amount') ?></th>
								<th scope="col" style="text-align:center"><?= __('Net Amount') ?></th>
								
							</tr>
						</thead>
						<tbody>
							<?php $total_amount_before_tax=0; $total_amount_after_tax=0; $total_gst_amount=0;  foreach ($salesInvoices as $salesInvoice): ?>
							<?php if($salesInvoice->amount_before_tax > 0){ ?>
							<tr>
								<td><?= h(++$page_no) ?></td>
								<td>
								<?php $date=date('Y-m-d',strtotime($salesInvoice->transaction_date));
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
								<?= $acronym.'/'.$financialyear.'/'. h(str_pad($salesInvoice->voucher_no, 3, '0', STR_PAD_LEFT))
								?>
		                        </td>
								<td><?= h($salesInvoice->transaction_date) ?></td>
								<td class="rightAligntextClass"><?= h($salesInvoice->amount_before_tax) ?>
								<?php $total_amount_before_tax+=$salesInvoice->amount_before_tax; ?>
								</td>
								<?php if($salesInvoice->total_igst==0){ ?>
									<td class="rightAligntextClass"><?= h($salesInvoice->total_cgst+$salesInvoice->total_sgst) ?></td>
								<?php $total_gst_amount+=$salesInvoice->total_cgst+$salesInvoice->total_sgst;
								}else{ ?>
									<td class="rightAligntextClass"><?= h($salesInvoice->total_igst) ?></td>
								<?php $total_gst_amount+=$salesInvoice->total_igst;
								} ?>
								
								<td class="rightAligntextClass"><?= h($salesInvoice->amount_after_tax) ?></td>
								<?php $total_amount_after_tax+=$salesInvoice->amount_after_tax; ?>
								
							</tr>
							<?php } endforeach; ?>
							<tr>
								<td class="rightAligntextClass" colspan="3">Total</td>
								<td style="text-align:right;"><b><?php echo $this->Number->format(@$total_amount_before_tax,['places'=>2]); ?></b></td>
								<td style="text-align:right;"><b><?php echo $this->Number->format(@$total_gst_amount,['places'=>2]); ?></b></td>
								<td style="text-align:right;"><b><?php echo $this->Number->format(@$total_amount_after_tax,['places'=>2]); ?></b></td>
								
						</tbody>
					</table>
				</div>
				
				
			</div>
		</div>
	</div>
</div>