<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class AdapterContext extends BehatContext
{
    private $adapterReflections = array();
    private $adapterConstructorParams = array();
    private $adapters = array();
    private $useAdapterAlias;
    private $result;
    private $resultingException;

    public function __construct(array $parameters)
    {
        // noop.
    }

    private function getAdapterAlias($adapterAlias=null)
    {
        $adapterAlias = $adapterAlias ?: $this->useAdapterAlias;
        if (empty($adapterAlias)) {
            throw new UnexpectedValueException('Could not identify which adapter to use.');
        }

        return $adapterAlias;
    }

    private function getAdapter($adapterAlias=null)
    {
        $adapterAlias = $this->getAdapterAlias();
        // Build missing adapter with previously given information
        if (false === isset($this->adapters[$adapterAlias])) {
            $adapterReflection = $this->adapterReflections[$adapterAlias];
            $providedParamters = $this->adapterConstructorParams[$adapterAlias] ?: array();
            $newInstanceParams = array();
            $constructorReflection = $adapterReflection->getMethod('__construct');
            $constructorParams = $constructorReflection->getParameters();
            // Get the right order of arguments for the constructor
            foreach ($constructorParams as $reflectionParameter) {
                $paramName = $reflectionParameter->getName();
                if (isset($providedParamters[$paramName])) {
                    $newInstanceParams[] = $providedParamters[$paramName];
                    continue;
                }

                if ($reflectionParameter->isOptional()) {
                    $newInstanceParams[] = $reflectionParameter->getDefaultValue();
                }
            }
            // Create the instance
            $this->adapters[$adapterAlias] = $adapterReflection->newInstanceArgs($newInstanceParams);
        }

        return $this->adapters[$adapterAlias];
    }

    /**
     * @Given /^I have a "([^"]*)" adapter named "([^"]*)"$/
     */
    public function iHaveAAdapterNamed($className, $adapterAlias)
    {
        $fullyQualifiedClassName = sprintf('Environment\\Adapter\\%s', $className);
        if (false === class_exists($fullyQualifiedClassName)) {
            $exceptionMessage = sprintf('Adapter "%s" not found.', $fullyQualifiedClassName);
            throw new UnexpectedValueException($exceptionMessage);
        }

        $this->adapterReflections[$adapterAlias] = new ReflectionClass($fullyQualifiedClassName);
        $this->adapterConstructorParams[$adapterAlias] = array();
    }

    private function defineConstructorParamsForAdapter($adapterAlias, $paramName, $value)
    {
        $adapterAlias = $this->getAdapterAlias();
        if (false == isset($this->adapterReflections[$adapterAlias])) {
            throw new UnexpectedValueException('You have not declared an adapter nicknamed: '.$adapterAlias);
        }
        
        $reflectionMethod = $this->adapterReflections[$adapterAlias]->getMethod('__construct');
        $methodParameters = $reflectionMethod->getParameters();
        foreach ($methodParameters as $reflectionParameter) {
            $methodParameters[$reflectionParameter->getName()] = $reflectionParameter;
        }

        if (false === isset($methodParameters[$paramName])) {
            $fullyQualifiedClassName = $this->adapterReflections[$adapterAlias]->getName();
            $exceptionMessage = sprintf('Parameter "%s" is not a "%s" constructor parameter.', $paramName, $fullyQualifiedClassName);
            throw new UnexpectedValueException($exceptionMessage);
        }

        $this->adapterConstructorParams[$adapterAlias][$paramName] = $value;
    }

    /**
     * @Given /^I as a constructor param "([^"]*)" I provide this array$/
     */
    public function iAsAConstructorParamIProvideThisArray($paramName, PyStringNode $iniSyntaxString)
    {
        $adapterAlias = $this->getAdapterAlias();
        $values = parse_ini_string((string) $iniSyntaxString);
        $this->defineConstructorParamsForAdapter($adapterAlias, $paramName, $values);
    }

    /**
     * @Given /^I as a constructor param "([^"]*)" I provide boolean "([^"]*)"$/
     */
    public function iAsAConstructorParamIProvideBoolean($paramName, $booleanValue)
    {
        $adapterAlias = $this->getAdapterAlias();
        $value = (boolean) $booleanValue;
        $this->defineConstructorParamsForAdapter($adapterAlias, $paramName, $value);
    }

    /**
     * @Given /^I get my "([^"]*)" adapter$/
     */
    public function iGetMyAdapter($useAdapterAlias)
    {
        $this->useAdapterAlias = $useAdapterAlias;
    }

    /**
     * @Given /^I write "([^"]*)" on "([^"]*)"$/
     */
    public function iWriteOn($value, $key)
    {
        $adapter = $this->getAdapter();
        try {
            $this->result = $adapter->set($key, $value);
        } catch (Exception $keepThisExceptionForLaterUse) {
            $this->resultingException = $keepThisExceptionForLaterUse;
        }
    }

    /**
     * @When /^I read "([^"]*)"$/
     * @When /^I read "([^"]*)" from it$/
     * @When /^I read "([^"]*)" from "([^"]*)"$/
     */
    public function iReadFromIt($keyName, $adapterAlias=null)
    {
        $adapter = $this->getAdapter($adapterAlias);
        try {
            $this->result = $adapter->get($keyName);
        } catch (Exception $keepThisExceptionForLaterUse) {
            $this->resultingException = $keepThisExceptionForLaterUse;
        }
    }

    /**
     * @Then /^I should get "([^"]*)"$/
     */
    public function iShouldGet($expectedResult)
    {
        if (0 === strcmp($expectedResult, $this->result)) {
            return true;
        }

        $exceptionMessage = sprintf('Expected "%s" but "%s" was given.', $expectedResult, $this->result);
        throw new UnexpectedValueException($exceptionMessage);
    }

    /**
     * @Then /^I should get a "([^"]*)" exception instance$/
     */
    public function iShouldGetAExceptionInstance($fullyQualifiedClassName)
    {
        if (!$this->resultingException instanceof Exception) {
            throw new UnexpectedValueException('An exception was expected, but none happened.');
        }

        if ($this->resultingException instanceof $fullyQualifiedClassName) {
            return true;
        }

        $givenExceptionClassName = get_class($this->resultingException);
        $baseExceptionMessage = 'Expected exception "%s" but received "%s" with message: %s';
        $resultingExceptionMessage = $this->resultingException->getMessage();
        $exceptionMessage = sprintf($baseExceptionMessage, $fullyQualifiedClassName, $givenExceptionClassName, $resultingExceptionMessage);
        throw new UnexpectedValueException($exceptionMessage);
    }

    /**
     * @Given /^I should get "([^"]*)" as exception message$/
     */
    public function iShouldGetAsExceptionMessage($expectedMessage)
    {
        if (!$this->resultingException instanceof Exception) {
            throw new UnexpectedValueException('An exception was expected, but none happened.');
        }

        $givenExceptionMessage = $this->resultingException->getMessage();
        if (0 === strcmp($expectedMessage, $givenExceptionMessage)) {
            return true;
        }

        $expectedMessage = sprintf('Expected exception message "%s" but received "%s"', $expectedMessage, $givenExceptionMessage);
        throw new UnexpectedValueException($expectedMessage);
    }

    /**
     * @Given /^I should get "([^"]*)" as exception code$/
     */
    public function iShouldGetAsExceptionCode($expectedCode)
    {
        if (!$this->resultingException instanceof Exception) {
            throw new UnexpectedValueException('An exception was expected, but none happened.');
        }

        $expectedCode = constant($expectedCode);
        $givenCode = $this->resultingException->getCode();
        if ($expectedCode == $givenCode) {
            return true;
        }

        $exceptionMessage = sprintf('Expected exception code "%s" but received "%s"', $expectedCode, $givenCode);
        throw new UnexpectedValueException($exceptionMessage); 
    }
}