Feature: View Article By Slug
  Show article content by slug Elasticsearch index

  Scenario: Viewing "explainer--super-tuesday-contests-offer-big-rewards--challenges-in-u-s--democratic-presidential-race" Article Content
    Given the microservice view page URL is loaded with slug "explainer--super-tuesday-contests-offer-big-rewards--challenges-in-u-s--democratic-presidential-race"
    When a 200 article response is be recieved
    Then the "explainer--super-tuesday-contests-offer-big-rewards--challenges-in-u-s--democratic-presidential-race" article content will be returned in a JSON format

  Scenario: Viewing "coronavirus-tsar-mike-pence-jets-off-to-florida-for-republican-fundraiser-amid-health-crisis" Article Content
    Given the microservice view page URL is loaded with slug "coronavirus-tsar-mike-pence-jets-off-to-florida-for-republican-fundraiser-amid-health-crisis"
    When a 200 article response is be recieved
    Then the "coronavirus-tsar-mike-pence-jets-off-to-florida-for-republican-fundraiser-amid-health-crisis" article content will be returned in a JSON format

  Scenario: Viewing "france-warns-against-handshakes-as-corona-infections-soar" Article Content
    Given the microservice view page URL is loaded with slug "france-warns-against-handshakes-as-corona-infections-soar"
    When a 200 article response is be recieved
    Then the "france-warns-against-handshakes-as-corona-infections-soar" article content will be returned in a JSON format

  Scenario: Requesting an article with a slug that can't be found
    Given the microservice view page URL is loaded with slug 'something-that-does-not-exist'
    When a 404 article response is be recieved
    Then an empty article response will be returned