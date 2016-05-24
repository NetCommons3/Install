<?php
/**
 * Installの開始
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('InstallAppTask', 'Install.Console/Command');

/**
 * Migrationの実行
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Console\Command
 */
class InstallMigrationsTask extends InstallAppTask {

/**
 * 接続先のオプションKey
 *
 * @var string
 */
	const KEY_CONNECTION = 'connection';

/**
 * Override startup
 *
 * @return void
 */
	public function startup() {
		$this->hr();
		$this->out(__d('install', 'NetCommons Install Step 4'));
		$this->hr();
	}

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		parent::execute();

		//引数のセット
		if (isset($this->params[self::KEY_CONNECTION])) {
			$connection = Hash::get($this->params, self::KEY_CONNECTION);
		} else {
			$connection = 'master';
		}

		if (! $this->InstallUtil->installMigrations($connection)) {
			return $this->error(__d('install', 'Failed to install migrations.'));
		}
	}

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->description(__d('install', 'NetCommons Install Step 4'))
			->addOption(self::KEY_CONNECTION, array(
				'short' => 'c',
				'help' => __d('install', 'Database connection.'),
				'required' => false
			));

		return $parser;
	}
}
