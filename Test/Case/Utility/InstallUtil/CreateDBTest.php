<?php
/**
 * InstallUtil::createDB()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('InstallUtil', 'Install.Utility');
App::uses('ConnectionManager', 'Model');

/**
 * InstallUtil::createDB()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Utility\InstallUtil
 */
class InstallUtilityInstallUtilCreateDBTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

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
		$this->__databaseClear();

		parent::tearDown();
	}

/**
 * configurationデータ取得
 *
 * @return array
 */
	private function __data() {
		//データ生成
		$dbObject = ConnectionManager::enumConnectionObjects();
		$configuration = $dbObject['test'];
		$configuration['port'] = Hash::get($dbObject['test'], 'port', '3306');
		$configuration['encoding'] = Hash::get($dbObject['test'], 'encoding', 'utf8');

		$configuration['database'] = 'nc3_test_dummy';

		switch ($configuration['datasource']) {
			case 'Database/Mysql':
				$driver = 'mysql';
				break;
			//case 'Database/Postgres':
			//	$driver = 'pgsql';
			//	break;
		}
		$configuration['driver'] = $driver;

		return $configuration;
	}

/**
 * テーブルクリア
 *
 * @return void
 */
	private function __databaseClear() {
		$configuration = $this->__data();
		$db = new PDO(
			sprintf(
				'%s:host=%s;port=%s',
				$configuration['driver'],
				$configuration['host'],
				$configuration['port']
			),
			$configuration['login'], $configuration['password']
		);
		$db->query(sprintf('DROP DATABASE IF EXISTS `%s`', $configuration['database']));
	}

/**
 * createDB()のテスト
 *
 * @return void
 */
	public function testCreateDB() {
		//データ生成
		$configuration = $this->__data();

		//テスト実施
		$instance = new InstallUtil();
		$result = $instance->createDB($configuration);

		//チェック
		$this->assertTrue($result);
		$logger = CakeLog::stream('TestMockLog');

		$expected = array(
			'Info: DB Connected',
			'Info: Database nc3_test_dummy for Database/Mysql created successfully',
		);
		$this->assertEquals($expected, $logger->output);

		$db = new PDO(
			sprintf(
				'%s:host=%s;dbname=%s;port=%s',
				$configuration['driver'],
				$configuration['host'],
				$configuration['database'],
				$configuration['port']
			),
			$configuration['login'], $configuration['password']
		);
		$this->assertEquals('PDO', get_class($db));
	}

/**
 * datasourceがMySQL以外の時のテスト
 *
 * @return void
 */
	public function testOnDatasourceError() {
		//データ生成
		$configuration = $this->__data();
		$configuration['datasource'] = 'error_db';

		//テスト実施
		$instance = new InstallUtil();
		$result = $instance->createDB($configuration);

		//チェック
		$this->assertFalse($result);
		$logger = CakeLog::stream('TestMockLog');

		$expected = array(
			'Error: Unknown datasource error_db',
		);
		$this->assertEquals($expected, $logger->output);
	}

/**
 * PDO接続エラーテスト
 *
 * @return void
 */
	public function testOnPDOError() {
		//データ生成
		$configuration = $this->__data();
		$configuration['host'] = 'dummy';
		$configuration['login'] = 'dummy';
		$configuration['password'] = 'dummy';

		//テスト実施
		$instance = new InstallUtil();
		$result = $instance->createDB($configuration);

		//チェック
		$this->assertFalse($result);
		$logger = CakeLog::stream('TestMockLog');

		$expected = 'Error: SQLSTATE[HY000] [2005] Unknown MySQL server host';
		$this->assertContains($expected, $logger->output[0]);
	}

/**
 * CREATE TABLE エラーテスト
 *
 * @return void
 */
	public function testOnCreateTableError() {
		//データ生成
		$configuration = $this->__data();
		$configuration['database'] = '``';

		//テスト実施
		$instance = new InstallUtil();
		$result = $instance->createDB($configuration);

		//チェック
		$this->assertFalse($result);
		$logger = CakeLog::stream('TestMockLog');

		$expected = array(
			'Info: DB Connected',
			'Info: Database  for Database/Mysql created failure',
		);
		$this->assertEquals($expected, $logger->output);

		try {
			$db = new PDO(
				sprintf(
					'%s:host=%s;dbname=%s;port=%s',
					$configuration['driver'],
					$configuration['host'],
					$configuration['database'],
					$configuration['port']
				),
				$configuration['login'], $configuration['password']
			);
		} catch (Exception $ex) {
			$db = false;
		}
		$this->assertFalse($db);
	}

}
