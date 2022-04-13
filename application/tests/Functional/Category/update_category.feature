Feature:

  Scenario: UC-1 Update a category with invalid UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "PUT" request to "/v1/categories/invalid-uuid"
    Then the response status code should be 404
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 404 |

  Scenario: UC-2 Update a category not found UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "PUT" request to "/v1/categories/9c71b2c4-81d4-4e2a-a8f8-ad977dba3716"
    Then the response status code should be 404
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 404 |
      | error.detail | Category 9c71b2c4-81d4-4e2a-a8f8-ad977dba3716 not found |

  Scenario: UC-3 Update a category with invalid body
    Given A category that exists:
      | id                                   | name  | description         |
      | 51dfe8c4-7c54-4733-83d9-2088a2d7c224 | Apple | description example |
    When I add "Content-Type" header equal to "application/json"
    And I send a "PUT" request to "/v1/categories/51dfe8c4-7c54-4733-83d9-2088a2d7c224" with body:
        """
        {
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Category name cannot be empty |
    And I send a "PUT" request to "/v1/categories/51dfe8c4-7c54-4733-83d9-2088a2d7c224" with body:
        """
        {
          "name": "name updated"
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Category description cannot be empty |
    And I send a "PUT" request to "/v1/categories/51dfe8c4-7c54-4733-83d9-2088a2d7c224" with body:
        """
        {
          "name": "name updated",
          "description": "description updated"
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Category description cannot be empty |

