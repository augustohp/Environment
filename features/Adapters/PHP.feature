@adapter
Feature: An adapter to use PHP functions getenv() and putenv()
    In order to use PHP functionality with environment variables
    As a developer
    I should be able to use this adapter as a proxy to these functions

Background:
    Given I have a "PHP" adapter named "php"
    And I get my "php" adapter
    And I have a php environment variable named "name" with "live"
    And I have a php environment variable named "readOnly" with "false"

Scenario: Read an existing value
    Given I get my "php" adapter
    When I read "name" from it
    Then I should get "live"

Scenario: Write a new value
    Given I get my "php" adapter
    And I write "debug" on "logLevel"
    When I read "logLevel"
    Then I should get "debug"

Scenario: Write to an existing value
    Given I get my "php" adapter
    And I write "staging" on "name"
    When I read "name" from it
    Then I should get "staging"

    