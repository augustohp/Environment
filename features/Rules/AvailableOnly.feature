@behavior
Feature: Ensure an adapter is always available
    In order to guarantee that an adapter is available so we can retrieve information
    As a I developer
    I must be able to compose my adapter with this behavior

Background:
    Given I have a "Stub" adapter named "stub"
    And I get my "stub" adapter
    And I as a constructor param "environmentData" I provide this array
    """
    ENVIRONMENT_NAME = live
    """

Scenario: Read a value that does not exist on decorated adapter
    Given I get my "stub" adapter
    And I add rule "AvailableOnly"
    When I read "ENVIRONMENT_NAME" from it
    Then I should get "live"