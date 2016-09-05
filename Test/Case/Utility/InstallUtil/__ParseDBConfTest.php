<?php
/**
 * InstallUtil::__parseDBConf()のテスト
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
 * InstallUtil::__parseDBConf()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Utility\InstallUtil
 */
class PrivateInstallUtilityInstallUtilParseDBConfTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * __parseDBConf()のテスト
 *
 * @return void
 */
	public function testParseDBConf() {
		//データ生成
		$conf = <<<EOF
	public \$test = array(
		'datasource' => '{test_datasource}',
		'persistent' => {test_persistent_null},
		'persistent' => {test_persistent_false},
		'persistent' => {test_persistent_true},
		'host' => '{test_host}',
		'port' => '{test_port}',
		'login' => '{test_login}',
		'password' => '{test_password}',
		'database' => '{test_database}',
		'prefix' => '{test_prefix}',
		'schema' => '{test_schema}',
		'encoding' => '{test_encoding}',
	);
EOF;
		$params = array(
			'persistent_null' => null,
			'persistent_false' => false,
			'persistent_true' => true,
			'database' => 'nc3_test_dummy'
		);
		$dbPrefix = 'test';

		//テスト実施
		$instance = new InstallUtil();
		$result = $this->_testReflectionMethod(
			$instance, '__parseDBConf', array($conf, $params, $dbPrefix)
		);

		//チェック
		$expected = <<<EOF2
	public \$test = array(
		'datasource' => '{test_datasource}',
		'persistent' => null,
		'persistent' => false,
		'persistent' => true,
		'host' => '{test_host}',
		'port' => '{test_port}',
		'login' => '{test_login}',
		'password' => '{test_password}',
		'database' => 'nc3_test_dummy',
		'prefix' => '{test_prefix}',
		'schema' => '{test_schema}',
		'encoding' => '{test_encoding}',
	);
EOF2;

		$this->assertEquals($expected, $result);
	}

}
