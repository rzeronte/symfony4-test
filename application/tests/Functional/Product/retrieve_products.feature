Feature:

  Scenario: UC-1 Retrieve empty list of products
    When I add "Content-Type" header equal to "application/json"
    And I send a "GET" request to "/v1/products"
    Then the response status code should be 200
    And the JSON node products should exist
    And the JSON node meta should exist
    And the JSON nodes should be equal to:
      | meta.currentPage | 1 |
      | meta.lastPage | 0 |
      | meta.size | 100 |
      | meta.total | 0 |

  Scenario: UC-2 Retrieve non empty list of products
    Given A product that exists:
      | id                                   | name   | price  | currency | featured |
      | 51dfe8c4-7c54-4733-83d9-2088a2d7c224 | Apple  | 1      | USD      | 0    |
      | c2864f60-aa03-47f1-9171-dd23f8f52ae8 | Orange | 2      | EUR      | 1    |
    When I add "Content-Type" header equal to "application/json"
    And I send a "GET" request to "/v1/products"
    Then the response status code should be 200
    And the JSON node products should exist
    And the JSON node meta should exist
    And the JSON nodes should be equal to:
      | products[0].id | 51dfe8c4-7c54-4733-83d9-2088a2d7c224 |
      | products[0].name | Apple |
      | products[0].price | 1 |
      | products[0].currency | USD |
      | products[0].featured | 0 |
      | products[1].id | c2864f60-aa03-47f1-9171-dd23f8f52ae8 |
      | products[1].name | Orange |
      | products[1].currency | EUR |
      | products[1].price | 2 |
      | products[1].featured | 1 |
      | meta.currentPage | 1 |
      | meta.lastPage | 1 |
      | meta.size | 100 |
      | meta.total | 2 |

  Scenario: UC-3 Retrieve filtered list
    When I add "Content-Type" header equal to "application/json"
    And I send a "GET" request to "/v1/products?featured=1"
    Then the response status code should be 200
    And the JSON node products should exist
    And the JSON node meta should exist
    And the JSON nodes should be equal to:
      | products[0].id | c2864f60-aa03-47f1-9171-dd23f8f52ae8 |
      | products[0].name | Orange |
      | products[0].currency | EUR |
      | products[0].price | 2 |
      | products[0].featured | 1 |
      | meta.currentPage | 1 |
      | meta.lastPage | 1 |
      | meta.size | 100 |
      | meta.total | 1 |

