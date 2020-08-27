Feature: Hal Article Microservice Health Check
  In order to check the microservice is alive and available

  Scenario: Loading health check
    Given the health check page is accessed
    Then a 204 health check response will be recieved