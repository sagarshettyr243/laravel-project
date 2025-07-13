pipeline {
  agent any

  environment {
    APP_NAME      = "laravel-app"
    IMAGE_TAG     = "latest"
    ECR_REGISTRY  = "123456789012.dkr.ecr.ap-south-1.amazonaws.com"
    DOCKER_IMAGE  = "${ECR_REGISTRY}/${APP_NAME}:${IMAGE_TAG}"
    HELM_CHART    = "./laravel-app-chart"
    HELM_CMD      = "helm upgrade --install"
  }

  options {
    disableConcurrentBuilds()
    skipDefaultCheckout()
  }

  stages {

    stage('Checkout') {
      steps {
        echo 'Checking out source code...'
        checkout scm
      }
    }

    stage('Test') {
      when {
        expression { return env.GIT_BRANCH ==~ /.*merge-request.*/ }
      }
      steps {
        echo 'Running PHPUnit tests...'
        sh '''
          composer install
          ./vendor/bin/phpunit || exit 1
        '''
      }
    }

    stage('Build Docker Image') {
      when {
        branch 'main'
      }
      steps {
        echo "Building and tagging Docker image..."
        sh """
          docker build -t ${APP_NAME} .
          docker tag ${APP_NAME}:latest ${DOCKER_IMAGE}
          echo 'Simulated push to ECR:'
          echo 'docker push ${DOCKER_IMAGE}'
        """
      }
    }

    stage('Deploy to Development') {
      when {
        branch 'main'
      }
      steps {
        echo 'Simulating deployment to Development (EKS)...'
        sh "${HELM_CMD} ${APP_NAME}-dev ${HELM_CHART} -f values.dev.yaml"
      }
    }

    stage('Deploy to Staging') {
      when {
        branch 'main'
      }
      steps {
        echo 'Simulating deployment to Staging (EKS)...'
        sh "${HELM_CMD} ${APP_NAME}-staging ${HELM_CHART} -f values.staging.yaml"
      }
    }

    stage('Deploy to Production') {
      when {
        branch 'main'
      }
      steps {
        echo 'Simulating deployment to Production (EKS)...'
        sh "${HELM_CMD} ${APP_NAME}-prod ${HELM_CHART} -f values.yaml"
      }
    }
  }

  post {
    success {
      echo 'CI/CD pipeline completed successfully!'
    }
    failure {
      echo 'Pipeline failed — merge is blocked until tests pass.'
    }
  }
}
