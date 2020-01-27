<?php 
namespace App\Model\Behavior;

use Cake\ORM\Behavior;

//
//  Helper Classes To Support with Packing and RePacking 
//  CakePHP extracted Data from Entities
//

class DataRePackerBehavior extends Behavior {

    public function initialize(array $config): void
    {
            
    }

    // take in an araay that may contain _ids shorthand
    // and repacks them correctly into longhand form
    public function rePackForeignKeys($data) {

        //writing Logic to flatten _ids to actual fields
        //Check to see if the item is an array of keys
        $keysShortHandIdentifier = '_ids';
        $newlyFormattedAssociateData = Array();

        // repack our array
        
        // Move to helper method
        if(is_array($data)){
            foreach($data as $associatedRecordKey => &$associatedRecordItem) { 
                // compare to the variable, so we can plug in changes if this formating of the array changes later
                if(is_array($associatedRecordItem)) {
                    if ($associatedRecordKey === $keysShortHandIdentifier) {
                        //we have found a shorthand Array, we need to do something with this
                        foreach($associatedRecordItem as $shortHandKey) {
                            // #note Move the keys into new array structure
                            // old structure was table->_ids->[1,2,3,4]  // which where the ids to update
                            //
                            // the new structure is
                            // table->element->id->value
                            // this means on update we can pass a mixture of associations and new data to update
                            $newlyFormattedAssociateData[] = array('id' => $shortHandKey);
                        }
                        $data = $newlyFormattedAssociateData;
                    }
                }
                else {
                    // The _id element is present but not intialised then we need to intialise it.
                    // If there are no associated items, then we need to turn this into an array so we can add items later
                    // #Passing an empty array I think is mostly harmless - but we need to test
                    if ($associatedRecordKey == $keysShortHandIdentifier) {
                        $data = array(); 
                    }
                }
            }
        }
        return $data;
    }
}
?>