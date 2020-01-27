<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PolicyComponentsPoliciesFixture
 */
class PolicyComponentsPoliciesFixture extends TestFixture
{ 
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'policy_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'policy_component_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'policy_components_policies_ibfk_2' => ['type' => 'index', 'columns' => ['policy_component_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['policy_id', 'policy_component_id'], 'length' => []],
            'policy_components_policies_ibfk_1' => ['type' => 'foreign', 'columns' => ['policy_id'], 'references' => ['policies', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'policy_components_policies_ibfk_2' => ['type' => 'foreign', 'columns' => ['policy_component_id'], 'references' => ['policy_components', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'policy_id' => 1,
                'policy_component_id' => 1,
            ],
        ];
        parent::init();
    }
}
