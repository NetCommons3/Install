<?php echo $this->element('scripts'); ?>
<?php echo $this->Form->create(false,
			array(
				'url' => array(
					'plugin' => 'install',
					'controller' => 'install',
					'action' => 'init_permission'))) ?>
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo __('Permissions') ?></div>
		<div>
			<?php foreach ($permissions as $permission): ?>
			<?php   if ($permission['error']): ?>
			<p class="bg-danger message">
				<span class="glyphicon glyphicon-remove"></span>
				<?php   echo h($permission['message']) ?>
			</p>
			<?php   else: ?>
			<p class="bg-info message">
				<span class="glyphicon glyphicon-ok"></span>
				<?php   echo h($permission['message']) ?>
			</p>
			<?php   endif; ?>
			<?php endforeach ?>
		</div>
	</div>
	<button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo __('Next') ?></button>
<?php echo $this->Form->end() ?>
