@behavior
Feature: Ensure an adapter never returns empty values.
    In order to guarantee that an adapter never returns empty values
    As a I developer
    I must be able to decorate my adapter with this behavior

Background:
    Given I have a "Stub" adapter named "stub"
    And I get my "stub" adapter
    And I as a constructor param "environmentData" I provide this array
    """
    ENVIRONMENT_NAME = live
    """

Scenario: Read a value that does not exist on decorated adapter
    Given I get my "stub" adapter
    And I decorate it with "NoEmptyReturn"
    When I read "something" from it
    Then I should get a "Environment\Exception\KeyNotFound" exception instance
    And I should get "'something' key was not found." as exception message
    And I should get "Environment\Error::KEY_NOT_FOUND" as exception code

Scenario: Read a value on a regular adapter
    Given I get my "stub" adapter
    When I read "something" from it
    Then I should get ""