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
    And I have a "PHP" adapter named "easyPHP"
    And I get my "easyPHP" adapter
    And I have a php environment variable named "ENV_NAME" with "live"
    And I as a constructor param "allowOverwrite" I provide boolean "true"

Scenario: Read an existing value
    Given I get my "php" adapter
    When I read "name" from it
    Then I should get "live"

Scenario: Write a new value
    Given I get my "php" adapter
    And I write "debug" on "logLevel"
    When I read "logLevel"
    Then I should get "debug"

Scenario: Write to an existing value, with overwrite option disabled 
    Given I get my "php" adapter
    And I write "staging" on "name"
    Then I should get a "Environment\Exception\WriteNotAllowed" exception instance
    And I should get "'name' is already set, and overwrite is not allowed." as exception message
    And I should get "Environment\Error::WRITE_NOT_ALLOWED" as exception code

Scenario: Write to an existing value, with overwrite option enabled
    Given I get my "easyPHP" adapter
    And I write "staging" on "ENV_NAME"
    When I read "ENV_NAME" from it
    Then I should get "staging"

    