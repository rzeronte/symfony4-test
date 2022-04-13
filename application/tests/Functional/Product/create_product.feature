Feature:

  Scenario: UC-1 Create a product without UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/products" with body:
        """
        {
          "name": "name",
          "price": 1,
          "currency": "USD"
        }
        """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "id" should exist
    And I send a "POST" request to "/v1/products" with body:
        """
        {
          "name": "name",
          "categoryId": null,
          "price": 1,
          "currency": "USD"
        }
        """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "id" should exist

  Scenario: UC-2 Create a product without invalid UUID
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/products" with body:
        """
        {
          "id": "invalid-uuid",
          "name": "name",
          "price": 1,
          "currency": "USD"
        }
        """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | ProductId must be a valid UUID. |

  Scenario: UC-3 Create a product with invalid body
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "/v1/products" with body:
        """
        {
          "id": "6dda12ae-9695-42ba-a915-8b8932c7998e",
          "name": "",
          "price": 1,
          "currency": "USD"
        }
        """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Product name cannot be empty |
    And I send a "POST" request to "/v1/products" with body:
        """
        {
          "id": "6dda12ae-9695-42ba-a915-8b8932c7998e",
          "name": "Name",
          "price": 0,
          "currency": "USD"
        }
        """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Provided "0" is not greater than "0". |
    And I send a "POST" request to "/v1/products" with body:
        """
        {
          "id": "6dda12ae-9695-42ba-a915-8b8932c7998e",
          "description": "test",
          "name": "Name",
          "price": 1,
          "currency": "BADCURRENCY"
        }
        """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      | error.status | 400 |
      | error.detail | Value "BADCURRENCY" is not an element of the valid values: EUR, USD |
