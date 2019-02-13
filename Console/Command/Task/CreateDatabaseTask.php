<?php
/**
 * データベースの作成
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('InstallAppTask', 'Install.Console/Command');

/**
 * データベースの作成
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Console\Command
 */
class CreateDatabaseTask extends InstallAppTask {

/**
 * datasourceのオプションKey
 *
 * @var string
 */
	const KEY_DATASOURCE = 'datasource';

/**
 * hostのオプションKey
 *
 * @var string
 */
	const KEY_HOST = 'host';

/**
 * portのオプションKey
 *
 * @var string
 */
	const KEY_PORT = 'port';

/**
 * databaseのオプションKey
 *
 * @var string
 */
	const KEY_DATABASE = 'database';

/**
 * prefixのオプションKey
 *
 * @var string
 */
	const KEY_PREFIX = 'prefix';

/**
 * loginのオプションKey
 *
 * @var string
 */
	const KEY_LOGIN = 'login';

/**
 * passwordのオプションKey
 *
 * @var string
 */
	const KEY_PASSWORD = 'password';

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		parent::execute();

		//database.phpの初期化処理
		$configs = $this->InstallUtil->chooseDBByEnvironment();
		if (! $this->InstallUtil->saveDBConf($configs)) {
			$message = __d(
				'install',
				'Failed to write %s. Please check permission.',
				array(APP . 'Config' . DS . 'database.php')
			);
			return $this->error($message);
		}

		//引数のセット
		$this->__prepare();

		//データベースの設定
		if ($this->InstallUtil->validatesDBConf($this->data)) {
			$this->InstallUtil->saveDBConf($this->data);
		} else {
			$message = var_export($this->InstallUtil->validationErrors, true);
			return $this->error('validation error', $message);
		}

		//データベース作成
		if (! $this->InstallUtil->createDB($this->data)) {
			return $this->error(__d('install', 'Failed to create database.'));
		}
	}

/**
 * 引数のデータセット
 *
 * @return void
 */
	private function __prepare() {
		//引数のセット
		$this->data = array();

		// * datasource
		if (array_key_exists(self::KEY_DATASOURCE, $this->params)) {
			$this->data[self::KEY_DATASOURCE] = Hash::get($this->params, self::KEY_DATASOURCE, 'Mysql');
		} else {
			$this->data[self::KEY_DATASOURCE] = $this->in(
				__d('install', 'Database configuration\'s datasource?'), ['Mysql'], 'Mysql'
			);
		}
		$this->data[self::KEY_DATASOURCE] = 'Database/' . $this->data[self::KEY_DATASOURCE];

		// * host
		if (array_key_exists(self::KEY_HOST, $this->params)) {
			$this->data[self::KEY_HOST] = Hash::get($this->params, self::KEY_HOST, 'localhost');
		} else {
			$this->data[self::KEY_HOST] = $this->in(
				__d('install', 'Database configuration\'s host?'), '', 'localhost'
			);
		}

		// * port
		if (array_key_exists(self::KEY_PORT, $this->params)) {
			$this->data[self::KEY_PORT] = Hash::get($this->params, self::KEY_PORT, '3306');
		} else {
			$this->data[self::KEY_PORT] = $this->in(
				__d('install', 'Database configuration\'s port?'), '', '3306'
			);
		}

		// * database
		if (array_key_exists(self::KEY_DATABASE, $this->params)) {
			$this->data[self::KEY_DATABASE] = Hash::get($this->params, self::KEY_DATABASE, 'nc3');
		} else {
			$this->data[self::KEY_DATABASE] = $this->in(
				__d('install', 'Database configuration\'s database?'), '', 'nc3'
			);
		}

		// * prefix
		if (array_key_exists(self::KEY_PREFIX, $this->params)) {
			$this->data[self::KEY_PREFIX] = Hash::get($this->params, self::KEY_PREFIX, '');
		} else {
			$this->data[self::KEY_PREFIX] = $this->in(
				__d('install', 'Database configuration\'s prefix?'), '', ''
			);
		}

		// * login
		if (array_key_exists(self::KEY_LOGIN, $this->params)) {
			$this->data[self::KEY_LOGIN] = Hash::get($this->params, self::KEY_LOGIN);
		} else {
			$this->data[self::KEY_LOGIN] = $this->in(
				__d('install', 'Database configuration\'s login?')
			);
		}

		// * password
		if (array_key_exists(self::KEY_PASSWORD, $this->params)) {
			$this->data[self::KEY_PASSWORD] = Hash::get($this->params, self::KEY_PASSWORD);
		} else {
			$this->data[self::KEY_PASSWORD] = $this->in(
				__d('install', 'Database configuration\'s password?')
			);
		}
	}

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->description(__d('install', 'NetCommons Install Step 3'))
			->addOption(self::KEY_DATASOURCE, array(
				'help' => __d('install', 'Database configuration\'s datasource. (Mysql) [Mysql]'),
				'required' => false
			))
			->addOption(self::KEY_HOST, array(
				'help' => __d('install', 'Database configuration\'s host. [localhost]'),
				'required' => false
			))
			->addOption(self::KEY_PORT, array(
				'help' => __d('install', 'Database configuration\'s port. [3306]'),
				'required' => false
			))
			->addOption(self::KEY_DATABASE, array(
				'help' => __d('install', 'Database configuration\'s database. [nc3]'),
				'required' => false
			))
			->addOption(self::KEY_PREFIX, array(
				'help' => __d('install', 'Database configuration\'s prefix. [\'\']'),
				'required' => false
			))
			->addOption(self::KEY_LOGIN, array(
				'help' => __d('install', 'Database configuration\'s login.'),
				'required' => false
			))
			->addOption(self::KEY_PASSWORD, array(
				'help' => __d('install', 'Database configuration\'s password.'),
				'required' => false
			));

		return $parser;
	}
}
