<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

error_reporting(-1);
define('DS',DIRECTORY_SEPARATOR);
require_once __DIR__.DS.'..'.DS.'..'.DS.'vendor'.DS.'autoload.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    public function __construct(array $parameters)
    {
        $this->useContext('adapter', new AdapterContext($parameters));
    }
}
