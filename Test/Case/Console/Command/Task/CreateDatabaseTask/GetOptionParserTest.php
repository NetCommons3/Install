<?php
/**
 * CreateDatabaseTask::getOptionParser()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsConsoleTestCase', 'NetCommons.TestSuite');
App::uses('CreateDatabaseTask', 'Install.Console/Command/Task');

/**
 * CreateDatabaseTask::getOptionParser()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Console\Command\Task\CreateDatabaseTask
 */
class InstallConsoleCommandTaskCreateDatabaseTaskGetOptionParserTest extends NetCommonsConsoleTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * Task name
 *
 * @var string
 */
	protected $_shellName = 'CreateDatabaseTask';

/**
 * getOptionParser()のテスト
 *
 * @return void
 */
	public function testGetOptionParser() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadTask($shell);

		//テスト実施
		$result = $this->$shell->getOptionParser();

		//チェック
		$this->assertEquals('ConsoleOptionParser', get_class($result));

		$expected = array(
			'--' . CreateDatabaseTask::KEY_DATASOURCE . ' ' .
				__d('install', 'Database configuration\'s datasource. (Mysql) [Mysql]'),
			'--' . CreateDatabaseTask::KEY_HOST . ' ' .
				__d('install', 'Database configuration\'s host. [localhost]'),
			'--' . CreateDatabaseTask::KEY_PORT . ' ' .
				__d('install', 'Database configuration\'s port. [3306]'),
			'--' . CreateDatabaseTask::KEY_DATABASE . ' ' .
				__d('install', 'Database configuration\'s database. [nc3]'),
			'--' . CreateDatabaseTask::KEY_PREFIX . ' ' .
				__d('install', 'Database configuration\'s prefix. [\'\']'),
			'--' . CreateDatabaseTask::KEY_LOGIN . ' ' .
				__d('install', 'Database configuration\'s login.'),
			'--' . CreateDatabaseTask::KEY_PASSWORD . ' ' .
				__d('install', 'Database configuration\'s password.'),
		);
		$actual = array(
			$result->options()[CreateDatabaseTask::KEY_DATASOURCE]
				->help(strlen(CreateDatabaseTask::KEY_DATASOURCE) + 3),
			$result->options()[CreateDatabaseTask::KEY_HOST]
				->help(strlen(CreateDatabaseTask::KEY_HOST) + 3),
			$result->options()[CreateDatabaseTask::KEY_PORT]
				->help(strlen(CreateDatabaseTask::KEY_PORT) + 3),
			$result->options()[CreateDatabaseTask::KEY_DATABASE]
				->help(strlen(CreateDatabaseTask::KEY_DATABASE) + 3),
			$result->options()[CreateDatabaseTask::KEY_PREFIX]
				->help(strlen(CreateDatabaseTask::KEY_PREFIX) + 3),
			$result->options()[CreateDatabaseTask::KEY_LOGIN]
				->help(strlen(CreateDatabaseTask::KEY_LOGIN) + 3),
			$result->options()[CreateDatabaseTask::KEY_PASSWORD]
				->help(strlen(CreateDatabaseTask::KEY_PASSWORD) + 3),
		);
		$this->assertEquals($expected, $actual);
	}

}
