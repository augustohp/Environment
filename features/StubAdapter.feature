@adapter
Feature: A stub adapter that accepts an array as source of information
    In order to be able to simulate an environment
    As a developer
    I should be able to feed the adapter an array so I can read and write to it

Background:
    Given I have a "Stub" adapter named "stub"
    And I get my "stub" adapter
    And I as a constructor param "environmentData" I provide this array
    """
    name = live
    readOnly = false
    """
    And I have a "Stub" adapter named "easyStub"
    And I get my "easyStub" adapter
    And I as a constructor param "environmentData" I provide this array
    """
    name = live
    """
    And I as a constructor param "allowOverwrite" I provide boolean "true"

Scenario: Read an existing value
    Given I get my "stub" adapter
    When I read "name" from it
    Then I should get "live"

Scenario: Read a value that does not exist
    Given I get my "stub" adapter
    When I read "something" from it
    Then I should get a "Environment\Exception\KeyNotFound" exception instance
    And I should get "'something' key was not found." as exception message
    And I should get "Environment\Error::KEY_NOT_FOUND" as exception code

Scenario: Write a new value
    Given I get my "stub" adapter
    And I write "debug" on "logLevel"
    When I read "logLevel"
    Then I should get "debug"

Scenario: Write to an existing value, with overwrite option disabled 
    Given I get my "stub" adapter
    And I write "staging" on "name"
    Then I should get a "Environment\Exception\WriteNotAllowed" exception instance
    And I should get "'name' is already set, and overwrite is not allowed." as exception message
    And I should get "Environment\Error::WRITE_NOT_ALLOWED" as exception code

Scenario: Write to an existing value, with overwrite option enabled
    Given I get my "easyStub" adapter
    And I write "staging" on "name"
    When I read "name" from it
    Then I should get "staging"

