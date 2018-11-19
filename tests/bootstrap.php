<?php
/**
 * Test suite bootstrap for Datalist.
 *
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */

use Cake\Datasource\ConnectionManager;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('WINDOWS')) {
    if (DS === '\\' || substr(PHP_OS, 0, 3) === 'WIN') {
        define('WINDOWS', true);
    } else {
        define('WINDOWS', false);
    }
}
define('ROOT', dirname(__DIR__));
define('TMP', ROOT . DS . 'tmp' . DS);
define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);
define('APP', ROOT . DS . 'tests' . DS . 'test_app' . DS . 'src' . DS);
define('APP_DIR', 'src');
define('CAKE_CORE_INCLUDE_PATH', ROOT . '/vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . APP_DIR . DS);
define('WWW_ROOT', ROOT . DS . 'webroot' . DS);
define('CONFIG', dirname(__FILE__) . DS . 'config' . DS);

require ROOT . '/vendor/autoload.php';
require CORE_PATH . 'config/bootstrap.php';

Cake\Core\Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'paths' => [
        'templates' => [ROOT . DS . 'tests' . DS . 'test_app' . DS . 'src' . DS . 'Template' . DS],
    ]
]);
Cake\Core\Configure::write('datalistJs', '<input type="text" id="__{{id}}" name="__{{name}}" list="datalist-{{id}}" autocomplete="off"{{inputAttrs}}>'
. '<datalist id="datalist-{{id}}"{{datalistAttrs}}>{{content}}</datalist>'
. '<input type="hidden" name="{{name}}" id="{{id}}">'
. '<script>
                if (CakePHP_datalist === undefined) {
                    var CakePHP_datalist = {};
                }
                
                CakePHP_datalist["{{id}}"] = {};
                [].forEach.call(
                    document.querySelectorAll("#datalist-{{id}} option"), 
                    function(element){
                        CakePHP_datalist["{{id}}"][element.value] = element.getAttribute("data-value");
                    });
                
                document.getElementById("__{{id}}")
                    .addEventListener("blur", 
                        function (e) {
                            document.getElementById("{{id}}").value = CakePHP_datalist["{{id}}"][e.target.value] 
                                ? CakePHP_datalist["{{id}}"][e.target.value] 
                                : document.getElementById("__{{id}}").value;
                        });
            </script>');
Cake\Core\Configure::write('debug', true);
$Tmp = new \Cake\Filesystem\Folder(TMP);
$Tmp->create(TMP . 'cache/models', 0770);
$Tmp->create(TMP . 'cache/persistent', 0770);
$Tmp->create(TMP . 'cache/views', 0770);
$cache = [
    'default' => [
        'engine' => 'File',
        'path' => CACHE,
    ],
    '_cake_core_' => [
        'className' => 'File',
        'prefix' => 'crud_myapp_cake_core_',
        'path' => CACHE . 'persistent/',
        'serialize' => true,
        'duration' => '+10 seconds',
    ],
    '_cake_model_' => [
        'className' => 'File',
        'prefix' => 'crud_my_app_cake_model_',
        'path' => CACHE . 'models/',
        'serialize' => 'File',
        'duration' => '+10 seconds',
    ],
];
Cake\Cache\Cache::setConfig($cache);

Cake\Mailer\Email::setConfigTransport('default', [
    'className' => 'Debug',
]);
Cake\Mailer\Email::setConfig('default', [
    'transport' => 'default',
]);
// Allow local overwrite
// E.g. in your console: export db_dsn="mysql://root:secret@127.0.0.1/cake_test"
if (!getenv('db_class') && getenv('db_dsn')) {
    ConnectionManager::setConfig('test', ['url' => getenv('db_dsn')]);
    return;
}
if (!getenv('db_class')) {
    putenv('db_class=Cake\Database\Driver\Sqlite');
    putenv('db_dsn=sqlite::memory:');
}
// Uses Travis config then (MySQL, Postgres, ...)
ConnectionManager::setConfig('test', [
    'className' => 'Cake\Database\Connection',
    'driver' => getenv('db_class'),
    'dsn' => getenv('db_dsn'),
    'database' => getenv('db_database'),
    'username' => getenv('db_username'),
    'password' => getenv('db_password'),
    'timezone' => 'UTC',
    'quoteIdentifiers' => true,
    'cacheMetadata' => true,
]);
