<?php


namespace App\Test\Utility\TestHelpers;

trait IntegrationHelperTrait {

    function mockSessionSignedIn() {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    // #Note in out users controller we use Email as the 
                    // username, but our Auth components maps this back
                    // to the correct session field
                    'username' => 'testing',     
                ]
            ]
        ]);
    }
 
}
?>