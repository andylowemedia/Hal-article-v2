pipeline {
  agent any
  stages {
    stage('build') {
      steps {
        sh '''#!/bin/bash

echo "Build script"
pwd
docker-compose build --no-cache && docker-compose up -d'''
      }
    }
    stage('test') {
      steps {
        sh '''#!/bin/bash

echo "Test script"'''
      }
    }
    stage('deploy') {
      steps {
        sh '''#!/bin/bash

echo "deploy script"'''
      }
    }
  }
}
