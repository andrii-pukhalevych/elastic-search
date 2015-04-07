<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

Configure::write('App', [
    'namespace' => 'App'
]);

if (!getenv('db_dsn')) {
    putenv('db_dsn=Cake\ElasticSearch\Datasource\Connection://127.0.0.1:9200?index=cake_test_db&driver=Cake\ElasticSearch\Datasource\Connection');
}

ConnectionManager::config('test', ['url' => getenv('db_dsn')]);
