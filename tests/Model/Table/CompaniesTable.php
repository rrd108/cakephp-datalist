<?php

namespace Datalist\Test\Model\Table;

use Cake\ORM\Table;

class CompaniesTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('companies');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Grants', [
            'foreignKey' => 'company_id',
            'targetForeignKey' => 'grant_id',
            'joinTable' => 'companies_grants'
        ]);
    }
}
