<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\PoliciesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;


//Extend the power the PoliciesControllerTest with IntegrationHelperTrait.
//Common helpers which can be used across all integration test cases
//Keepng things like signing in users dry

use App\Test\Utility\TestHelpers\IntegrationHelperTrait;

/**
 * App\Controller\PoliciesController Test Case
 *
 * @uses \App\Controller\PoliciesController
 */
class PoliciesControllerTest extends TestCase
{
    use IntegrationTestTrait;
    use IntegrationHelperTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Policies',
        'app.Users',
        'app.PolicyComponents',
        'app.PolicyComponentsPolicies'
    ];

    /**
     * Test index method
     *
     * @return void
     */

    public function setUp():void {
        parent::setUp();
      
        
    }
    public function testIndex(): void
    {
       
        //Use the method from our new trait to sign in a dummy user 
        //we do this here as we might not want to be signed in for all tests
        $this->mockSessionSignedIn();

        $this->get('/policies/index');

        $this->assertResponseOk();

        // Checked that we logged in that we are getting view that displays 
        // the current policies
        $this->assertResponseContains('Policies');

        //$this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView(): void
    {
        $this->mockSessionSignedIn();
        $this->get('/policies/view/1');
        
        $this->assertResponseOk();
        $this->assertResponseContains('Actions');
        
    }

    /**
     * Test editPolicyComponents method
     *
     * @return void
     */
    //public function testEditPolicyComponents(): void
    //{
        //$this->markTestIncomplete('Not implemented yet.');
    //}

    /**
     * Test add method
     *
     * @return void
     */
    //public function testAdd(): void
    //{
        //$this->markTestIncomplete('Not implemented yet.');
    //}

    /**
     * Test edit method
     *
     * @return void
     */
    //public function testEdit(): void
    //{
        //$this->markTestIncomplete('Not implemented yet.');
    //}

    /**
     * Test approve method
     *
     * @return void
     */
    //public function testApprove(): void
    //{
        //$this->markTestIncomplete('Not implemented yet.');
    //}

    /**
     * Test delete method
     *
     * @return void
     */
    //public function testDelete(): void
    //{
        //$this->markTestIncomplete('Not implemented yet.');
    //}
}
