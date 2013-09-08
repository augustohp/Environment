@behavior
Feature: Ensure an adapter to be read-only.
    In order to only read from the environment
    As a I developer
    I must be able to decorate my adapter with this behavior

Background:
    Given I have a "Stub" adapter named "stub"
    And I get my "stub" adapter
    And I as a constructor param "environmentData" I provide this array
    """
    ENVIRONMENT_NAME = live
    """

Scenario: Unable to write on a read-only decorated adapter
    Given I get my "stub" adapter
    And I decorate it with "ReadOnly"
    When I write "boo" on "ENVIRONMENT_NAME"
    Then I should get a "Environment\Exception\ReadOnly" exception instance
    And I should get "'ENVIRONMENT_NAME' cannot be set while on read-only mode." as exception message
    And I should get "Environment\Error::READ_ONLY" as exception code

Scenario: Read a value from the adapter
    Given I get my "stub" adapter
    And I read "ENVIRONMENT_NAME" from it
    Then I should get "live"