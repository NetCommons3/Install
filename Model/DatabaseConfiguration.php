<?php
/**
 * DatabaseConfiguration Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppModel', 'Model');

/**
 * DatabaseConfiguration Model
 *
 * @package NetCommons\Install\Model
 */
class DatabaseConfiguration extends AppModel {

/**
 * useTable
 *
 * @var bool
 */
	public $useTable = false;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'datasource' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
				'inList' => array(
					'rule' => array('inList', array('Database/Mysql', 'Database/Postgres')),
					'message' => __d('net_commons', 'Invalid request.'),
				)
			),
			'persistent' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'host' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('install', 'Host')
					),
					'required' => true,
				),
				'regex' => array(
					'rule' => array('custom', '/[\w' . preg_quote('!#%&()*+,-./;<=>?@[]^_{|}~', '/') . ']+$/'),
					'message' => __d('net_commons', 'Only alphabets and numbers are allowed.'),
					'required' => true,
				),
			),
			'port' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('install', 'Port')
					),
					'required' => true,
				),
				'numeric' => array(
					'rule' => array('range', -1, 65536),
					'message' => sprintf(
						__d('net_commons', 'The input %s must be a number bigger than %d and less than %d.'),
						__d('install', 'Port'),
						0,
						65535
					),
				),
			),
			'database' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('install', 'Database')
					),
					'required' => true,
				),
				'regex' => array(
					'rule' => array('custom', '/^[\w]+$/'),
					'message' => __d('install', 'Only alphabets and numbers are allowed.'),
				),
			),
			//'schema' => array(
			//	'regex' => array(
			//		'rule' => array('custom', '/^[\w]+$/'),
			//		'message' => __d('net_commons', 'Only alphabets and numbers are allowed.'),
			//		'allowEmpty' => true,
			//	),
			//),
			'prefix' => array(
				'regex' => array(
					'rule' => array('custom', '/^[\w]+$/'),
					'message' => __d('install', 'Only alphabets and numbers are allowed.'),
					'allowEmpty' => true,
				),
			),
			'login' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('install', 'Login')
					),
					'required' => true,
				),
				'regex' => array(
					'rule' => array('custom', '/[\w' . preg_quote('!#%&()*+,-./;<=>?@[]^_{|}~', '/') . ']+$/'),
					'message' => __d('net_commons', 'Only alphabets and numbers are allowed.'),
					'required' => true,
				),
			),
			'password' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('install', 'Password')
					),
					'required' => true,
				),
				'regex' => array(
					'rule' => array('custom', '/[\w' . preg_quote('!#%&()*+,-./;<=>?@[]^_{|}~', '/') . ']+$/'),
					'message' => __d('net_commons', 'Only alphabets and numbers are allowed.'),
					'allowEmpty' => true,
				),
			),
		));

		return parent::beforeValidate($options);
	}
}
