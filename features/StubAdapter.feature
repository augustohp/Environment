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

Scenario: Read an existing value
    Given I get my "stub" adapter
    When I read "name" from it
    Then I should get "live"

Scenario: Write a new value
    Given I get my "stub" adapter
    And I write "debug" on "logLevel"
    When I read "logLevel"
    Then I should get "debug"

Scenario: Write to an existing value
    Given I get my "stub" adapter
    And I write "staging" on "name"
    When I read "name" from it
    Then I should get "staging"

