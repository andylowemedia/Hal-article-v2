Feature: Add Article
  Save article to MySQL database and Elasticsearch Index

  Scenario: Add article data to databases
    Given article data is prepared
    When save article post request has been sent
    Then a 201 repsonse is received
    And a successfully created message is received with an article ID
