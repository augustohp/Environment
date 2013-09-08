@behavior
Feature: Ensure a value is never ovewriten.
    In order to guarantee that a value is never overwriten by an adapter
    As a I developer
    I must be able to decorate my adapter with this behavior

Background:
    Given I have a "Stub" adapter named "stub"
    And I get my "stub" adapter
    And I as a constructor param "environmentData" I provide this array
    """
    ENVIRONMENT_NAME = live
    """

Scenario: Write to an existing value on a decorated adapter
    Given I get my "stub" adapter
    And I decorate it with "PreventOverwrite"
    When I write "boo" on "ENVIRONMENT_NAME"
    Then I should get a "Environment\Exception\WriteNotAllowed" exception instance
    And I should get "'ENVIRONMENT_NAME' is already set, overwrite is not allowed." as exception message
    And I should get "Environment\Error::WRITE_NOT_ALLOWED" as exception code

Scenario: Overwrite a value on a regular adapter
    Given I get my "stub" adapter
    And I write "boo" on "ENVIRONMENT_NAME"
    When I read "ENVIRONMENT_NAME" from it
    Then I should get "boo"