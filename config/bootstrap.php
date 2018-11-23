<?php

use Cake\Core\Configure;
if (file_exists(ROOT . DS . 'config' . DS . 'form-templates.php')) {
    Configure::load('form-templates');
}
