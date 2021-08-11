<?php
/**
 * InstallUtil::installMigrations()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('InstallUtil', 'Install.Utility');

/**
 * InstallUtil::installMigrations()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Utility\InstallUtil
 */
class InstallUtilityInstallUtilInstallMigrationsTest extends NetCommonsCakeTestCase {

/**
 * Fixture merge
 *
 * @var array
 */
	protected $_isFixtureMerged = false;

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * 初期の件数
 *
 * @var string
 */
	private $__initTableCount = 0;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログ出力のMock
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Install', 'TestInstall');
		CakeLog::config('TestMockLog', array('engine' => 'TestInstall.TestMock'));

		$this->__databaseClear();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//$this->__databaseClear();

		parent::tearDown();
	}

/**
 * テーブルクリア
 *
 * @return void
 */
	private function __databaseClear() {
		$db = ConnectionManager::getDataSource('test');

		$tables = $db->query('SHOW TABLES');

		$this->__initTableCount = 0;
		foreach ($tables as $table) {
			$tableName = null;
			if (array_key_exists('TABLE_NAMES', $table)) {
				$tableName = array_shift($table['TABLE_NAMES']);
			} elseif (array_key_exists('TABLES', $table)) {
				$tableName = array_shift($table['TABLES']);
			}

			if (preg_match('/schema_migrations$/', $tableName)) {
				$db->query('DELETE FROM `' . $tableName . '` WHERE type != \'Migrations\'');
				$this->__initTableCount++;
			} else {
				$db->query('DROP TABLE `' . $tableName . '`');
			}
		}
	}

/**
 * installMigrations()のテスト
 *
 * @return void
 */
	public function testInstallMigrations() {
		$instance = new InstallUtil(true);

		//データ生成
		$connection = 'test';
		$addPlugins = array_unique(array_merge(
			App::objects('plugins'),
			array_map('basename', glob(ROOT . DS . 'app' . DS . 'Plugin' . DS . '*', GLOB_ONLYDIR))
		));

		//テスト実施
		Configure::write('debug', 0);
		$db = ConnectionManager::getDataSource('test');
		$tables = $db->query('SHOW TABLES');
		$this->assertCount($this->__initTableCount, $tables);

		$result = $instance->installMigrations($connection, $addPlugins);

		//チェック
		$this->assertTrue($result);

		$logger = CakeLog::stream('TestMockLog');
		$result = preg_grep('/Failure/', $logger->output);
		$this->assertCount(0, $result);
	}

/**
 * installMigrations()のテスト
 *
 * @return void
 */
	public function testFailurePlugin() {
		$instance = new InstallUtil(true);

		//データ生成
		$connection = 'test';
		$addPlugins = array('TestInstall');

		//テスト実施
		Configure::write('debug', 0);
		$db = ConnectionManager::getDataSource('test');
		$tables = $db->query('SHOW TABLES');
		$this->assertCount($this->__initTableCount, $tables);

		$result = $instance->installMigrations($connection, $addPlugins);

		//チェック
		$this->assertFalse($result);

		$logger = CakeLog::stream('TestMockLog');
		$result = preg_grep('/Failure/', $logger->output);
		$this->assertCount(2, $result);
	}

}
