<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Import CSV');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Instructions Steps </span>
				</div>
			</div>
			<div class="portlet-body">
			<?= $this->Form->create($import_csv) ?>
				<div class="row">
					<div class="col-md-6 col-sm-6">
						<div class="portlet box blue-steel">
							<div class="portlet-title">
								<div class="caption">
									Follow the Instructions Steps given Below
								</div>
							</div>
							<div class="portlet-body">
								<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 360px;"><div class="scroller" style="height: 360px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
									<b></b><ol class="numbers">
										
										<?php $url=$this->request->webroot.'samplecsv/importGRNSapmleStep1.csv';
										?><br/>
										<li><b>First Download the Sample CSV file
											<span><a href="<?php echo $url; ?>"> Click here to Download </b></a></span>
										</li>
										<br/>
										<li><b>Open the CSV file and fill the records(Item code, Quantity,Sales Rate,PurchaseRate) in given format <u>CSV Format For File 1</u> </b>
										</li>
										<br/>
										<li><b>Upload the CSV File and submit it <span><?php echo $this->Html->link('Click Here to go to link',['controller'=>'FirstTampGrnRecords','action' => 'Import'],['escape'=>false]); ?></span></b>
										</li>
										<br/>
										<li><b>Download the CSV file for furher Process
											<?php echo $this->Html->link('Click Here to Download',['controller'=>'FirstTampGrnRecords','action' => 'csvDownload'],['escape'=>false]); ?></span></b>
										</li>
										</br>
										<li><b>Open the CSV file and if Addition Item Data Required is "Yes" then fill the other records( item name, hsn code,unit, gst rate fix or fluid, first gst rate,amount in refence to gst rate(if gst rate is fluid), second gst rate(if gst rate is fluid),shade,size,description) in given format <u>CSV Format For File 2 </u> or .<span><a href="#step_2_format"> Click here to show format</b></a> </b>
										</li>
										<br/>
										<li><b>Upload the CSV File and submit it, After that Edit the Invalid data if have and Click on Final Import  <span><?php echo $this->Html->link('Click Here to go to link',['controller'=>'Grns','action' => 'ImportStep2'],['escape'=>false]); ?></span></b>
										</li>
										<br/>
									</ol>
								</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6" id="step_1_format">
						<div class="portlet box blue-steel">
							<div class="portlet-title">
								<div class="caption">
									CSV Format For File 1
								</div>
							</div>
							<div class="portlet-body">
								<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 360px;"><div class="scroller" style="height: 360px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
								<table class="table table-bordered" width="100%">
									<thead><tr>
										<th>Item Code </th>
										<th>Quantity</th>
										<th>Sales Rate</th>
										<th>Purchase Rate</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td>1.Item code of the particular item. In case Item code is empty, System automatic generate Item Code. </td>
										<td>1. Mandatory or cannot be empty</br>2.Should be  Positive Integer or Decimal only</td>
										<td>1. Mandatory or cannot be empty </br>2.Should be Positive Integer or Decimal only</td>
										<td>1. Mandatory or cannot be empty</br>2.Should be Positive Integer or Decimal only</td>
									</tr>
									</tbody>
								</table>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</br>
				
				<div class="row">
					<div class="col-md-12 col-sm-6" id="step_2_format">
						<div class="portlet box blue-steel">
							<div class="portlet-title">
								<div class="caption">
									CSV Format For File 2
								</div>
							</div>
							<div class="portlet-body">
								<div class="table-scrollable">
								<table class="table table-bordered" width="100%">
									<thead><tr>
										<th>Item Code </th>
										<th>Quantity</th>
										<th>Sales Rate</th>
										<th>Purchase Rate</th>
										<th>Is Addition Item Data Required</th>
										<th>Item Name</th>
										<th>Hsn Code</th>
										<th>Unit</th>
										<th>Gst Rate Fixed or Fluid</th>
										<th>First Gst Rate</th>
										<th>Amount In Ref Of Gst Rate</th>
										<th>Second Gst Rate</th>
										<th>Shade</th>
										<th>Size</th>
										<th>Description</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td>1.Item code of the particular item. In case Item code is empty, System automatic generate Item Code. </td>
										<td>1.Mandatory or cannot be empty. </br>2.Should be  Positive Integer or Decimal only. </td>
										<td>1.Mandatory or cannot be empty. </br>2.Should be Positive Integer or Decimal only.</td>
										<td>1.Mandatory or cannot be empty. </br>2.Should be Positive Integer or Decimal only. </td>
										<td>1.If yes then fill all next columns else leave empty.</td>
										<td>1.Mandatory or cannot be empty. </td>
										<td>1.Optional. </td>
										<td>1.Mandatory or cannot be empty. </br>2. Should be from given List
										<ul><?php foreach ($units as $unit) { ?>
										<li><?= h($unit->name) ?></li>	
										<?php } ?></ul></td>
										<td>1.Mandatory or cannot be empty. </br>2.If gst rate  amount vary then fill fulid else fill fix</td>
										<td>1.Mandatory or cannot be empty. </br>2.Should be Positive Integer or Decimal only. </td>
										<td>1.If gst rate fix or fluid column is fluid then fill this.</br>2.Should be Positive Integer or Decimal only</td>
										<td>1.If gst rate fix or fluid column is fluid then fill this. </br>2.Should be Positive Integer or Decimal only.</td>
										<td>1.Optional. </br>2. Should be from given List
										<ul><?php foreach ($shades as $shade) { ?>
										<li><?= h($shade->name) ?></li>	
										<?php } ?></ul></td>
										<td>1.Optional. </br>2. Should be from given List
										<ul><?php foreach ($sizes as $size) { ?>
										<li align="left"><?= h($size->name) ?></li>	
										<?php } ?></ul></td>
										<td>1.Optional. </td>
									</tr>
									</tbody>
								</table>
								
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
			</div>
		</div>
	</div>
</div>
