<?php
namespace Datalist\Test\Model\Table;

use Cake\ORM\Table;

class IssuersTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('issuers');
        $this->setPrimaryKey('id');

        $this->hasMany('Grants', [
            'foreignKey' => 'issuer_id'
        ]);
    }
}
