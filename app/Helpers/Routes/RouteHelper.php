<?php

namespace App\Helpers\Routes;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteHelper
{

    public static function includeRoutesFile(string $folder)
    {
        //first we create a variable  that is an instance of
        $dirIterator = new RecursiveDirectoryIterator($folder);
        //then we wrap our variable with another recursive class that give us
        //access to other helper function
        /** @var RecursiveDirectoryIterator|RecursiveIteratorIterator $it */
        $it = new RecursiveIteratorIterator($dirIterator);
        //lets loop
        //checks if the iterator point at actual item
        while ($it->valid()) {
            //now lets check that the iterator points at a file
            if (
                !$it->isDot() &&
                $it->isFile() &&
                $it->isReadable() &&
                $it->current()->getExtension() == 'php'
            ) {
                require $it->key();
                //or
                //require $it->current()->getPathname();
                //the second one will return spl file object and get the full path to the file
            }
            //points the iterator to the next item
            $it->next();
        }
    }
}