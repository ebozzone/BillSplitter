<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CollectionIdManager {

    public function generateNewCollectionId(){
         // Generates a new collection ID using some algorithm, checks to make sure it hasn't been taken already
         return time();
    }
}
