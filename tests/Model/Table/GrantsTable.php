<?php
namespace Datalist\Test\Model\Table;

use Cake\ORM\Table;

class GrantsTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('grants');
        $this->setPrimaryKey('id');

        $this->addBehavior('Datalist.Datalist', ['Issuers' => 'name', 'Companies' => 'name']);

        $this->belongsTo('Issuers', [
            'foreignKey' => 'issuer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsToMany('Companies', [
            'foreignKey' => 'grant_id',
            'targetForeignKey' => 'company_id',
            'joinTable' => 'companies_grants'
        ]);

    }
}
