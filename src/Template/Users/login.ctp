<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */

$this->set('title', 'Login');
?>
<!-- BEGIN LOGIN FORM -->
<?= $this->Form->create($user,['class'=>'login-form']) ?>
	<?= $this->Flash->render() ?>
	<div class="form-group">
		<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
		<label>Username</label>
		<?php  echo $this->Form->control('username',['label'=>false,'class'=>'form-control ','autocomplete'=>'off','placeholder'=>'Username','autofocus']); ?>
	</div>
	<div class="form-group">
		<label>Password</label>
		<?php  echo $this->Form->control('password',['label'=>false,'class'=>'form-control ','autocomplete'=>'off','placeholder'=>'Password']); ?>
	</div>
	<div class="form-actions">
		<?= $this->Form->button(__('Login'),['class'=>'btn btn-primary btn-block uppercase']) ?>
	</div>
<?= $this->Form->end() ?>
<!-- END LOGIN FORM -->