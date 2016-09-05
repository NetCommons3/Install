<?php
/**
 * DatabaseConfiguration::validate()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');
App::uses('DatabaseConfigurationFixture', 'Install.Test/Fixture');

/**
 * DatabaseConfiguration::validate()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Model\DatabaseConfiguration
 */
class DatabaseConfigurationValidateTest extends NetCommonsValidateTest {

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
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'DatabaseConfiguration';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'validates';

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ(省略可)
 *
 * @return array テストデータ
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	public function dataProviderValidationError() {
		$data['DatabaseConfiguration'] = array(
			'datasource' => 'Database/Mysql',
			'persistent' => '0',
			'host' => 'localhost',
			'port' => '3306',
			'database' => 'unit_test',
			'schema' => '',
			'prefix' => 'unit_',
			'login' => 'test',
			'password' => 'test',
		);

		return array(
			//datasource
			array('data' => $data, 'field' => 'datasource', 'value' => null,
				'message' => __d('net_commons', 'Invalid request.')
			),
			array('data' => $data, 'field' => 'datasource', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')
			),
			array('data' => $data, 'field' => 'datasource', 'value' => 'ErrorDB',
				'message' => __d('net_commons', 'Invalid request.')
			),

			//persistent
			array('data' => $data, 'field' => 'persistent', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')
			),
			array('data' => $data, 'field' => 'persistent', 'value' => '2',
				'message' => __d('net_commons', 'Invalid request.')
			),
			array('data' => $data, 'field' => 'persistent', 'value' => 'a',
				'message' => __d('net_commons', 'Invalid request.')
			),

			//host
			array('data' => $data, 'field' => 'host', 'value' => null,
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Host')
				),
			),
			array('data' => $data, 'field' => 'host', 'value' => '',
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Host')
				),
			),
			array('data' => $data, 'field' => 'host', 'value' => 'あ',
				'message' => __d('net_commons', 'Only alphabets and numbers are allowed.')
			),
			array('data' => $data, 'field' => 'host', 'value' => 'ｱ',
				'message' => __d('net_commons', 'Only alphabets and numbers are allowed.')
			),

			//port
			array('data' => $data, 'field' => 'port', 'value' => null,
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Port')
				),
			),
			array('data' => $data, 'field' => 'port', 'value' => '',
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Port')
				),
			),
			array('data' => $data, 'field' => 'port', 'value' => '3306aa',
				'message' => sprintf(
					__d('net_commons', 'The input %s must be a number bigger than %d and less than %d.'),
					__d('install', 'Port'),
					0,
					65535
				),
			),
			array('data' => $data, 'field' => 'port', 'value' => -1,
				'message' => sprintf(
					__d('net_commons', 'The input %s must be a number bigger than %d and less than %d.'),
					__d('install', 'Port'),
					0,
					65535
				),
			),
			array('data' => $data, 'field' => 'port', 'value' => 65536,
				'message' => sprintf(
					__d('net_commons', 'The input %s must be a number bigger than %d and less than %d.'),
					__d('install', 'Port'),
					0,
					65535
				),
			),

			//database
			array('data' => $data, 'field' => 'database', 'value' => null,
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Database')
				),
			),
			array('data' => $data, 'field' => 'database', 'value' => '',
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Database')
				),
			),
			array('data' => $data, 'field' => 'database', 'value' => 'あ',
				'message' => __d('install', 'Only alphabets and numbers are allowed.')
			),
			array('data' => $data, 'field' => 'database', 'value' => 'a-b',
				'message' => __d('install', 'Only alphabets and numbers are allowed.')
			),

			//prefix
			array('data' => $data, 'field' => 'prefix', 'value' => 'あ',
				'message' => __d('install', 'Only alphabets and numbers are allowed.')
			),
			array('data' => $data, 'field' => 'prefix', 'value' => 'a-b',
				'message' => __d('install', 'Only alphabets and numbers are allowed.')
			),

			//login
			array('data' => $data, 'field' => 'login', 'value' => null,
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Login')
				),
			),
			array('data' => $data, 'field' => 'login', 'value' => '',
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Login')
				),
			),
			array('data' => $data, 'field' => 'login', 'value' => 'あ',
				'message' => __d('net_commons', 'Only alphabets and numbers are allowed.')
			),

			//password
			array('data' => $data, 'field' => 'password', 'value' => null,
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Password')
				),
			),
			array('data' => $data, 'field' => 'password', 'value' => '',
				'message' => sprintf(
					__d('net_commons', 'Please input %s.'), __d('install', 'Password')
				),
			),
			array('data' => $data, 'field' => 'password', 'value' => 'あ',
				'message' => __d('net_commons', 'Only alphabets and numbers are allowed.')
			),
		);
	}

}
