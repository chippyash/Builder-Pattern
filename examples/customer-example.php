<?php
/* 
 * Build a simple customer
 */
include_once '../vendor/autoload.php';
include_once './Director/CustomerDirector.php';

use chippyash\BuilderPattern\Example\Director\CustomerDirector;
use chippyash\BuilderPattern\Modifier;
use Zend\Debug\Debug;

$director = new CustomerDirector(new Modifier());
echo "new customer details";
Debug::dump($director->build());

echo 'add some purchases';
$director->buyItem('GH41097', 10.52);
$director->buyItem('XC91347', 15.62);
Debug::dump($director->build());