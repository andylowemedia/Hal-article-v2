Feature: Search Articles By Query String
  Search articles in Elasticsearch index

  Scenario: Searching article index filter by category 'Current Affairs' size of 5
    Given the microservice search URL is loaded "/search?index=articles&type=article&filter[categories]=currentAffairs&size=5"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&filter[categories]=currentAffairs&size=5" search content will be returned in a JSON format

  Scenario: Searching article index filter by category 'Entertainment' size of 5
    Given the microservice search URL is loaded "/search?index=articles&type=article&filter[categories]=entertainment&size=5"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&filter[categories]=entertainment&size=5" search content will be returned in a JSON format

  Scenario: Searching article index filter by category 'Weather'
    Given the microservice search URL is loaded "/search?index=articles&type=article&filter[categories]=weather"
    When a 404 search response is be received
    Then an empty search response will be returned

  Scenario: Searching article index filter by category that is not 'Entertainment' size of 5
    Given the microservice search URL is loaded "/search?index=articles&type=article&not-filter[categories]=entertainment&size=5&sort=id:asc"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&not-filter[categories]=entertainment&size=5&sort=id:asc" search content will be returned in a JSON format

  Scenario: Searching article index filter by term 'brexit' size of 5
    Given the microservice search URL is loaded "/search?index=articles&type=article&search=brexit&size=5"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&search=brexit&size=5" search content will be returned in a JSON format

  Scenario: Searching article index filter and exclude term 'brexit' from 'title' size of 5
    Given the microservice search URL is loaded "/search?index=articles&type=article&excludes[title]=brexit&size=5"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&excludes[title]=brexit&size=5" search content will be returned in a JSON format

  Scenario: Searching articles index filter by term 'obscure search'
    Given the microservice search URL is loaded "/search?index=articles&type=article&search=obscure+search"
    When a 404 search response is be received
    Then an empty search response will be returned

  Scenario: Searching articles making sure image exists and is not empty
    Given the microservice search URL is loaded "/search?index=articles&type=article&exists=image&size=5"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&exists=image&size=5" search content will be returned in a JSON format

  Scenario: Searching articles making sure image does not exist
    Given the microservice search URL is loaded "/search?index=articles&type=article&not-exists=image&size=5&sort=id:asc"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&not-exists=image&size=5&sort=id:asc" search content will be returned in a JSON format

  Scenario: Searching articles making sure image does not exist and ordered by publish date descending
    Given the microservice search URL is loaded "/search?index=articles&type=article&not-exists=image&size=5&sort=publishDate:desc"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&not-exists=image&size=5&sort=publishDate:desc" search content will be returned in a JSON format

  Scenario: Searching articles between date range '2010-01-01' to '2020-02-29'
    Given the microservice search URL is loaded "/search?index=articles&type=article&date-fr=2020-01-01&date-to=2020-02-29&sort=publishDate:desc&size=5"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&date-fr=2020-01-01&date-to=2020-02-29&sort=publishDate:desc&size=5" search content will be returned in a JSON format

  Scenario: Searching Articles with the keyword 'alesha dixon'
    Given the microservice search URL is loaded "/search?index=articles&type=article&keywords=alesha+dixon&sort=publishDate:desc"
    When a 200 search response is be received
    Then URL "/search?index=articles&type=article&keywords=alesha+dixon&sort=publishDate:desc" search content will be returned in a JSON format
