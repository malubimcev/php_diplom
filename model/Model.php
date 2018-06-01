<?php

require_once 'model/ModelInterfaces.php';
require_once 'model/ModelTraits.php';

abstract class Model implements ModelInterface
{
    use RecordsTrait;
    
}//end class Model