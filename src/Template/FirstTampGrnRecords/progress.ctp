<?php /**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Progress Csv');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Progress CSV Data</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($FirstTampGrnRecords,['enctype'=>'multipart/form-data']) ?>
				<div class="row" id="Process_div">
				    <div class="col-md-3"></div>
				    <div class="col-md-6">
					    <div class="progress progress-striped active" style=" margin-bottom: 2px; ">
							<div class="progress-bar progress-bar-danger progress_bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="500" style="width: 0%">
								<span class="sr-only">
								80% Complete (danger) </span>
							</div>
						</div>
						<div id="Processed_text">0% Processed</div>
					</div>
					<div class="col-md-3"></div>
				</div>
				<div class="row">
				    <div class="col-md-3"></div>
				    <div class="col-md-6 show_link" style="display:none;">
					     <?php echo $this->Html->link(' Download CSV File', '/FirstTampGrnRecords/csvDownload',['escape' => false,'class'=>'']); ?>
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	$js="
	$(document).ready(function() {
		process_data();
		function process_data(){ 
			var success_url='".$this->Url->build(['controller'=>'Grns','action'=>'import_csv/'])."';
			var url='".$this->Url->build(['controller'=>'FirstTampGrnRecords','action'=>'ProcessData'])."'
			$.ajax({
				url: url,
				type: 'GET',
			}).done(function(response) {  
			    response = $.parseJSON(response);
				$('.progress_bar').css('width',response.percantage+'%');
				$('#Processed_text').html(response.percantage+'% Progressed');
				if(response.status=='true')
				{
					process_data();
				}else{
					window.location.href = success_url;
				}
				
			});
		}
    });
	";

echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>