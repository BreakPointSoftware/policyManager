<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;


use Cake\Controller\Controller;
use Cake\Core\Configure;


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        

        //Constant for disabling login for debuging perposes
        //#Learning - deines should not be stored here as it not maintabale and they 
        // get duplicate calls during unit testing
        //define("REQUIRE_LOGIN", true);    
        //define("LEARNING_OUTPUT", false);

        //Configure::write is the correct approach for setting globally accessable
        //variables in CakePhp

        Configure::write('requireLogin',true);
        Configure::write('learningOutput',false);

        //#GB Learning #Notes
        //All controlers extend the appController.
        //The Auth Component at this level, forces the 
        //users to the Users Controller / Login Action if they are logged out
                
        if(Configure::read('requireLogin')) {
            $this->loadComponent('Auth', [
                'authenticate' => [
                    'Form' => [  
                        'fields'    => [
                            'username'  => 'email',
                            'password'  => 'password'
                            ]
                    ]
                ],
                'userModel' => 'users',
                'loginAction' => [  
                    'controller' => 'Users',
                    'action'     => 'login'
                ]
            ]);
        }
        

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    
}
