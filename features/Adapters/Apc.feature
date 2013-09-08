@adapter
Feature: An adapter to use PHP APC cache module.
    In order to use APC as an environment variable source
    As a developer
    I should be able to use this adapter as a proxy to APC functions

Background:
    Given I have a "Apc" adapter named "apc"
    And I get my "apc" adapter
    And I remove an APC cache variable named "name"
    And I write "live" on "name"

Scenario: Read an existing value
    Given I get my "apc" adapter
    When I read "name" from it
    Then I should get "live"

Scenario: Write a new value
    Given I get my "apc" adapter
    And I write "debug" on "logLevel"
    When I read "logLevel"
    Then I should get "debug"

Scenario: Write to an existing value
    Given I get my "apc" adapter
    And I write "staging" on "name"
    When I read "name" from it
    Then I should get "staging"

    