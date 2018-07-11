<?php
namespace Datalist\Test\Model\Table;

use Cake\ORM\Table;

class CompaniesGrantsTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('companies_grants');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Grants', [
            'foreignKey' => 'grant_id',
            'joinType' => 'INNER'
        ]);
    }
}
