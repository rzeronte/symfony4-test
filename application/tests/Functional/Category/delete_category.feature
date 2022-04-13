Feature:

  Scenario: UC-1 Delete a category with invalid UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "DELETE" request to "/v1/categories/invalid-uuid"
    Then the response status code should be 404
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 404 |

  Scenario: UC-2 Delete a category not found UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "DELETE" request to "/v1/categories/9c71b2c4-81d4-4e2a-a8f8-ad977dba3716"
    Then the response status code should be 404
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 404 |
      | error.detail | Category 9c71b2c4-81d4-4e2a-a8f8-ad977dba3716 not found |

  Scenario: UC-3 Delete a category that exists
    Given A category that exists:
      | id                                   | name  | description         |
      | 51dfe8c4-7c54-4733-83d9-2088a2d7c224 | Apple | description example |
    When I add "Content-Type" header equal to "application/json"
    And I send a "DELETE" request to "/v1/categories/51dfe8c4-7c54-4733-83d9-2088a2d7c224"
    Then the response status code should be 200
