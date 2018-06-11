<?php

namespace System\View;

interface ViewInterface
{
    /**
     * Get The View Output
     *
     * @return String
     */
    public function getOutput();

    /**
     * Convert The View Object To String in Printing
     * i.e echo $object
     */
    public function __toString();  
}