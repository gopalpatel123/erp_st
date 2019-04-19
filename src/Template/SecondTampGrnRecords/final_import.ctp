<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Create Grn');
?>
<div class="row">
	<div class="col-md-9">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Create Goods Recieve Note</span>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($grn,['enctype'=>'multipart/form-data','onsubmit'=>'return checkValidation()']) ?>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Voucher No :</label>&nbsp;&nbsp;
							<?= h('#'.str_pad($voucher_no, 4, '0', STR_PAD_LEFT)) ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Transaction Date <span class="required">*</span></label>
							<?php echo $this->Form->control('transaction_date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y'),'required'=>'required']); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Reference No.</label>
							<?php echo $this->Form->control('reference_no', ['label' => false,'class' => 'form-control input-sm ','placeholder'=>'Reference No.']); ?>
						</div>	
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Supplier</label>
							<?php echo $this->Form->control('supplier_ledger_id',['empty'=>'-Select Supplier-', 'class'=>'form-control input-sm supplier_ledger_id select2me','label'=>false, 'options' => $partyOptions,'required'=>'required']);
							?>
						</div>
					</div>
				</div>
				<br>
			</div>
			<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit']) ?>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
<?php
	$js="
	function checkValidation()
	{
	        $('.submit').attr('disabled','disabled');
	        $('.submit').text('Submiting...');
    }
	
";
echo $this->Html->scriptBlock($js, array('block' => 'scriptBottom')); 
?>


