<?php
/**
 * InstallController::init_db()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * InstallController::init_db()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Controller\InstallController
 */
class InstallControllerInitDbTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'install';

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

		Configure::write('debug', 0);
		Configure::write('NetCommons.installed', false);
	}

/**
 * init_db()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testInitDbGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'init_db'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertInput('form', null, '/install/init_db', $this->view);
		$this->assertInput('input', 'data[DatabaseConfiguration][datasource]', null, $this->view);
		$this->assertInput('input', 'data[DatabaseConfiguration][host]', null, $this->view);
		$this->assertInput('input', 'data[DatabaseConfiguration][port]', null, $this->view);
		$this->assertInput('input', 'data[DatabaseConfiguration][database]', null, $this->view);
		$this->assertInput('input', 'data[DatabaseConfiguration][schema]', null, $this->view);
		$this->assertInput('input', 'data[DatabaseConfiguration][prefix]', null, $this->view);
		$this->assertInput('input', 'data[DatabaseConfiguration][login]', null, $this->view);
		$this->assertInput('input', 'data[DatabaseConfiguration][password]', null, $this->view);
	}

/**
 * POSTテストデータ取得
 *
 * @return array
 */
	private function __postData() {
		//テストデータ
		$data = array('DatabaseConfiguration' => array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'port' => '3306',
			'login' => 'root',
			'password' => 'root',
			'database' => 'unit_test',
			'prefix' => '',
			'schema' => '',
			'encoding' => 'utf8',
		));

		return $data;
	}

/**
 * init_db()アクションのPOSTリクエストテスト用DataProvider
 *
 * ### 戻り値
 *  - testPrefix テストプレフィックス
 *  - expectedPrefix テストプレフィックス
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		return array(
			array('testPrefix' => '', 'expectedPrefix' => ''),
			array('testPrefix' => 'test_prefix_', 'expectedPrefix' => 'test_prefix_'),
			array('testPrefix' => 'test_prefix', 'expectedPrefix' => 'test_prefix_'),
		);
	}

/**
 * init_db()アクションのPOSTリクエストテスト
 *
 * @param string $testPrefix テストプレフィックス
 * @param string $expectedPrefix 期待値のプレフィックス
 * @return void
 * @dataProvider dataProvider
 */
	public function testInitDbPost($testPrefix, $expectedPrefix) {
		//テストデータ
		$data = $this->__postData();
		$data['DatabaseConfiguration']['prefix'] = $testPrefix;

		$expected = $data;

		$this->controller->InstallUtil = $this->getMock(
			'InstallUtil', array('saveDBConf', 'createDB', 'installMigrations'), array('name' => 'InstallUtil')
		);

		$expected['DatabaseConfiguration']['prefix'] = $expectedPrefix;
		$this->controller->InstallUtil->expects($this->once())->method('saveDBConf')
			->with($expected['DatabaseConfiguration']);

		$this->controller->InstallUtil->expects($this->once())->method('createDB')
			->with($expected['DatabaseConfiguration'])
			->will($this->returnValue(true));

		$this->controller->InstallUtil->expects($this->once())->method('installMigrations')
			->will($this->returnValue(true));

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'init_db'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$pattern = '/' . preg_quote('/install/init_admin_user', '/') . '/';
		$this->assertRegExp($pattern, $header['Location']);
	}

/**
 * init_db()アクションのvalidate()エラーテスト用DataProvider
 *
 * ### 戻り値
 *  - $debug デバッグモード
 *
 * @return array テストデータ
 */
	public function dataProviderOnValidationError() {
		return array(
			array('debug' => false),
			array('debug' => true),
		);
	}

/**
 * init_db()アクションのvalidate()エラーテスト
 *
 * @param bool $debug デバッグモード
 * @return void
 * @dataProvider dataProviderOnValidationError
 */
	public function testValidationError($debug) {
		//テストデータ
		$data = $this->__postData();
		$data['DatabaseConfiguration']['database'] = '';

		$this->controller->InstallUtil = $this->getMock(
			'InstallUtil', array('saveDBConf', 'createDB', 'installMigrations'), ['name' => 'InstallUtil']
		);

		$this->controller->InstallUtil->expects($this->exactly(0))->method('saveDBConf')
			->will($this->returnValue(false));

		$this->controller->InstallUtil->expects($this->exactly(0))->method('createDB')
			->will($this->returnValue(true));

		$this->controller->InstallUtil->expects($this->exactly(0))->method('installMigrations')
			->will($this->returnValue(true));

		//テスト実行
		Configure::write('debug', $debug);
		$this->_testPostAction('post', $data, array('action' => 'init_db'), null, 'view');

		//チェック
		$error = __d('net_commons', 'Failed on validation errors. Please check the input data.');
		$this->assertEquals($this->controller->viewVars['errors'], [$error]);
		$this->assertTextContains($error, $this->view);
		$this->assertTextContains(
			sprintf(__d('net_commons', 'Please input %s.'), __d('install', 'Database')), $this->view
		);

		$logger = CakeLog::stream('TestMockLog');
		$result = preg_grep('/' . preg_quote('\'database\'', '/') . '/', $logger->output);
		if ($debug) {
			$this->assertCount(1, $result);
		} else {
			$this->assertCount(0, $result);
		}
	}

/**
 * init_db()アクションのcreateDB()エラーテスト
 *
 * @return void
 */
	public function testCreateDBError() {
		//テストデータ
		$data = $this->__postData();

		$this->controller->InstallUtil = $this->getMock(
			'InstallUtil', array('saveDBConf', 'createDB', 'installMigrations'), array('name' => 'InstallUtil')
		);

		$this->controller->InstallUtil->expects($this->once())->method('saveDBConf')
			->with($data['DatabaseConfiguration']);

		$this->controller->InstallUtil->expects($this->once())->method('createDB')
			->with($data['DatabaseConfiguration'])
			->will($this->returnValue(false));

		$this->controller->InstallUtil->expects($this->exactly(0))->method('installMigrations')
			->will($this->returnValue(true));

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'init_db'), null, 'view');

		//チェック
		$error = __d('install', 'Failed to create database.');
		$this->assertEquals($this->controller->viewVars['errors'], [$error]);
		$this->assertTextContains($error, $this->view);
	}

/**
 * init_db()アクションのinstallMigrations()エラーテスト
 *
 * @return void
 */
	public function testInstallMigrationsError() {
		//テストデータ
		$data = $this->__postData();

		$this->controller->InstallUtil = $this->getMock(
			'InstallUtil', array('saveDBConf', 'createDB', 'installMigrations'), array('name' => 'InstallUtil')
		);

		$this->controller->InstallUtil->expects($this->once())->method('saveDBConf')
			->with($data['DatabaseConfiguration']);

		$this->controller->InstallUtil->expects($this->once())->method('createDB')
			->with($data['DatabaseConfiguration'])
			->will($this->returnValue(true));

		$this->controller->InstallUtil->expects($this->once())->method('installMigrations')
			->will($this->returnValue(false));

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'init_db'), null, 'view');

		//チェック
		$error = __d('install', 'Failed to install migrations.');
		$this->assertEquals($this->controller->viewVars['errors'], [$error]);
		$this->assertTextContains($error, $this->view);
	}

}
