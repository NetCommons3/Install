<?php
/**
 * データベース設定
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->script('/install/js/install.js');
echo $this->NetCommonsHtml->css('/install/css/install.css');
?>

<div class="loader hidden text-center">
	<?php echo $this->Html->image('/net_commons/img/loader.gif', array('plugin' => false)); ?>
</div>

<?php foreach ($errors as $error): ?>
	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo $error; ?>
	</div>
<?php endforeach; ?>
<?php
	echo $this->NetCommonsForm->create('DatabaseConfiguration',
		array(
			'url' => array(
				'plugin' => 'install',
				'controller' => 'install',
				'action' => 'init_db'
			)
		)
	);
?>
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo __d('install', 'Database Settings'); ?></div>
		<div class="panel-body">
			<div class="form-group form-inline">
				<?php echo $this->NetCommonsForm->input('datasource',
						array(
							'type' => 'select',
							'options' => array(
								'Database/Mysql' => 'Mysql',
								//'Database/Postgres' => 'Postgresql'
							),
							'empty' => false,
							'label' => __d('install', 'Datasource'),
							'div' => false
						)
					); ?>
			</div>
			<?php echo $this->NetCommonsForm->input('host',
					array(
						'label' => __d('install', 'Host'),
						'default' => $masterDB['host'],
					)
				); ?>
			<?php echo $this->NetCommonsForm->input('port',
					array(
						'label' => __d('install', 'Port'),
						'default' => $masterDB['port'],
					)
				); ?>
			<?php echo $this->NetCommonsForm->input('database',
					array(
						'label' => __d('install', 'Database'),
						'default' => 'nc3',
					)
				); ?>

			<?php echo $this->NetCommonsForm->hidden('schema',
					array(
						'value' => 'public',
					)
				); ?>

			<?php echo $this->NetCommonsForm->input('prefix',
					array(
						'label' => __d('install', 'Prefix'),
						'placeholder' => 'nc3_',
					)
				); ?>
			<?php echo $this->NetCommonsForm->input('login',
					array(
						'label' => __d('install', 'ID'),
						'default' => $masterDB['login'],
						'placeholder' => __d('install', 'Username'),
					)
				); ?>
			<?php echo $this->NetCommonsForm->input('password',
					array(
						'label' => __d('install', 'Password'),
						'placeholder' => __d('install', 'Password'),
					)
				); ?>
		</div>
	</div>
	<button class="btn btn-lg btn-primary btn-block" type="submit">
		<?php echo __d('install', 'Next'); ?>
	</button>
<?php echo $this->NetCommonsForm->end();
