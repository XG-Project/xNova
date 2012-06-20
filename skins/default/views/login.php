<nav>
	<?php echo anchor('/', lang('login.index')); ?>
	<?php echo anchor('register', lang('login.register')); ?>
	<?php echo anchor('#', lang('login.board')); //$this->config->item('board_link') ?>
</nav>
<section class="login_introduction">
<?php echo $this->config->item('game_name').' '.lang('login.introduction'); ?>
</section>
<section class="login_form">
	<?php echo form_open('/'); ?>
		<label for="username"><?php echo lang('login.username'); ?></label>
		<input type="text" id="username" name="username">
		<label for="password"><?php echo lang('login.password'); ?></label>
		<input type="password" id="password" name="password">
		<label for="rememberme"><?php echo lang('login.rememberme'); ?></label>
		<input type="checkbox" id="rememberme" name="rememberme">
		<input type="submit" value="<?php echo lang('login.submit'); ?>">
	</form>
	<span class="forgot_password"><?php echo anchor('reset_password', lang('login.lost_password')); ?></span>
</section>