<?php
/**
 * All cakephp-datalist plugin tests
 */
class Allcakephp-datalistTest extends CakeTestCase {

/**
 * Suite define the tests for this plugin
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All cakephp-datalist test');

		$path = CakePlugin::path('cakephp-datalist') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
