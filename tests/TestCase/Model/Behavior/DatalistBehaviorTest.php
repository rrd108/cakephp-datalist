<?php
namespace Datalist\Test\TestCase\Model\Behavior;

use Datalist\Model\Behavior\DatalistBehavior;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Behavior\DatalistBehavior Test Case
 */
class DatalistBehaviorTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Datalist\Model\Behavior\DatalistBehavior
     */
    public $Datalist;

     public $fixtures = [
        'plugin.Datalist.companies',
        'plugin.Datalist.grants',
        'plugin.Datalist.issuers',
        'plugin.Datalist.companies_grants',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Companies = TableRegistry::getTableLocator()->get(
            'Companies',
            ['className' => 'Datalist\Test\Model\Table\CompaniesTable']
        );
        $this->Grants = TableRegistry::getTableLocator()->get(
            'Grants',
            ['className' => 'Datalist\Test\Model\Table\GrantsTable']
        );
        $this->Issuers = TableRegistry::getTableLocator()->get(
            'Issuers',
            ['className' => 'Datalist\Test\Model\Table\IssuersTable']
        );
        $this->CompaniesGrants = TableRegistry::getTableLocator()->get(
            'CompaniesGrants',
            ['className' => 'Datalist\Test\Model\Table\CompaniesGrantsTable']
        );
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Datalist, $this->Companies, $this->Grants, $this->Issuers, $this->CompaniesGrants);
        parent::tearDown();
    }

    /**
     * Test beforeMarshal method
     *
     * @return void
     */
    public function testBeforeMarshal()
    {
        $data = new ArrayObject([
            'issuer_id' => 'this is a string',
            'name' => 'Name of the element',
        ]);
        $behavior = $this->Grants->behaviors()->get('Datalist');
        $behavior->beforeMarshal(new Event('Model.beforeSave'), $data, new ArrayObject());
        $this->assertEquals('this is a string', $data['issuer']['name']);

        $data = new ArrayObject([
            'name' => 'Name of the element',
            'companies' => ['_ids' => 'New Company']
        ]);
        $behavior = $this->Grants->behaviors()->get('Datalist');
        $behavior->beforeMarshal(new Event('Model.beforeSave'), $data, new ArrayObject());
        $this->assertEquals('New Company', $data['companies'][0]['name']);

        $data = new ArrayObject([
            'name' => 'Name of the element',
            'issuer_id' => 'this is a string',
            'companies' => ['_ids' => 'New Company']
        ]);
        $behavior = $this->Grants->behaviors()->get('Datalist');
        $behavior->beforeMarshal(new Event('Model.beforeSave'), $data, new ArrayObject());
        $this->assertEquals('this is a string', $data['issuer']['name']);
        $this->assertEquals('New Company', $data['companies'][0]['name']);
    }

    public function testSave()
    {
        $data = [
            'name' => 'Grant 3',
            'issuer_id' => 'New issuer - 3'
        ];
        $grant = $this->Grants->newEntity();
        $grant = $this->Grants->patchEntity($grant, $data);
        $this->Grants->save($grant);

        $newGrant = $this->Grants->get(3, ['contain' => 'Issuers']);
        //check if the new issuer added
        $this->assertEquals(3, $newGrant->issuer->id);
        $this->assertEquals('New issuer - 3', $newGrant->issuer->name);

        $data = [
            'name' => 'Grant 4',
            'issuer_id' => 1,
            'companies' => ['_ids' => 'New Company - 3']
        ];
        $grant = $this->Grants->newEntity();
        $grant = $this->Grants->patchEntity($grant, $data);
        $this->Grants->save($grant);
        $newGrant = $this->Grants->find()->where(['Grants.id' => 4])->matching('Companies');
        //check if the new company added
        $this->assertEquals(3, $newGrant->toArray()[0]->_matchingData['Companies']->id);

        $data = [
            'name' => 'Grant 5',
            'issuer_id' => 'New Isssuer 4',
            'companies' => ['_ids' => 'New Company - 4']
        ];
        $grant = $this->Grants->newEntity();
        $grant = $this->Grants->patchEntity($grant, $data);
        $this->Grants->save($grant);
        $newGrant = $this->Grants->find()->where(['Grants.id' => 5])->contain('Issuers')->matching('Companies');
        $this->assertEquals(4, $newGrant->toArray()[0]->issuer->id);
        $this->assertEquals(4, $newGrant->toArray()[0]->_matchingData['Companies']->id);
    }

    public function testSaveWithExistingCompanies()
    {
        $data = [
            'name' => 'Grant 6',
            'issuer_id' => 1,
            'companies' => ['_ids' => [1,2]]
        ];
        $grant = $this->Grants->newEntity();
        $grant = $this->Grants->patchEntity($grant, $data);
        $newGrant = $this->Grants->save($grant);
        $this->assertEquals(1, $newGrant->toArray()['companies'][0]['id']);
        $this->assertEquals(2, $newGrant->toArray()['companies'][1]['id']);

        $data = [
            'name' => 'Grant 7',
            'issuer_id' => 1,
            'companies' => ['_ids' => 2]
        ];
        $grant = $this->Grants->newEntity();
        $grant = $this->Grants->patchEntity($grant, $data);
        $newGrant = $this->Grants->save($grant);
        $this->assertEquals(2, $newGrant->toArray()['companies'][0]['id']);
    }
}
