Feature:

  Scenario: UC-1 Create a category without UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/categories" with body:
        """
        {
          "name": "Category",
          "description": "Description"
        }
        """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "id" should exist

  Scenario: UC-2 Create a category with UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/categories" with body:
        """
        {
          "id": "a3629ffa-c037-4b14-927c-a42fd5f46af9",
          "name": "Category",
          "description": "Description"
        }
        """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | id | a3629ffa-c037-4b14-927c-a42fd5f46af9 |

  Scenario: UC-3 Create a category with same UUID that another
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/categories" with body:
        """
        {
          "id": "a3629ffa-c037-4b14-927c-a42fd5f46af9",
          "name": "Category",
          "description": "Description"
        }
        """
    Then the response status code should be 409
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 409 |
      | error.detail | Category a3629ffa-c037-4b14-927c-a42fd5f46af9 already exists |

  Scenario: UC-4 Create a category with invalid UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/categories" with body:
        """
        {
          "id": "invalid-uuid",
          "name": "Category",
          "description": "Description"
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | CategoryId must be a valid UUID. |

  Scenario: UC-5 Create a category without name or description
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/categories" with body:
        """
        {
          "id": "a3629ffa-c037-4b14-927c-a42fd5f46af9",
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON node "error" should exist
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Category name cannot be empty |

  Scenario: UC-6 Create a category with name but without description
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/categories" with body:
        """
        {
          "id": "080f926d-d8d0-4e54-a9c9-645e395a8704",
          "name": "Category name"
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON node "error" should exist
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Category description cannot be empty |

  Scenario: UC-7 Create a category with description but without name
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/categories" with body:
        """
        {
          "id": "1fc10786-9879-4bb7-a582-fc861349a9c5",
          "description": "Description name"
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON node "error" should exist
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Category name cannot be empty |
