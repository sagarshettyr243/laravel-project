# Laravel Kubernetes Deployment (Simulated CI/CD)

This repository contains a Laravel 10 application with production-grade deployment configurations using Docker, Kubernetes (Helm), and a simulated CI/CD pipeline built with Jenkins.

---

## Overview

The architecture supports both **local development** and **cloud-ready environments**, including simulated deployment to **development**, **staging**, and **production** setups.

### Local Environment
- Uses Docker Compose to run Laravel app with MySQL, Redis, and MongoDB.
- To verify the MongoDB and ElasticSearch conatiner
  Command:
  - docker ps | grep mongo
  - docker ps | grep elasticsearch
  
- `.env` file controls local config.
- Ideal for rapid iteration and local testing.

### 🛠️ Kubernetes Environments
- Helm chart defines configurable deployments via `values.dev.yaml`, `values.staging.yaml`, and `values.yaml` (production).
- PVC, Ingress, ConfigMaps, and Secrets are used for runtime customization.
- Simulated EKS cluster assumed — no real AWS infrastructure is needed.

---

## CI/CD Workflow (Simulated via Jenkins)

The Jenkins pipeline contains the following **stages**:

### Test
- Runs PHPUnit tests on **Merge Requests** to `main` branch.
- If tests fail, the merge is blocked.

# ⚙️Buid
- Triggered only after merge to `main`.
- Builds Docker image from Laravel app.
- Tags and simulates push to a mocked ECR repository.

# Deploy
- Simulates deployment to Kubernetes via Helm for:
  - `development` (uses `values.dev.yaml`)
  - `staging` (uses `values.staging.yaml`)
  - `production` (uses `values.yaml`)

- All deployments are dry-run or placeholder actions — no real EKS/ECR credentials are required.

---

# Getting Started

## 1. Run Project Locally via Docker Compose

```bash
# Start containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# Access Laravel
http://localhost:9000

2. Build Docker Image Mnaually

docker build -t laravel-app .
docker tag laravel-app:latest your-ecr/laravel-app:latest
echo "Simulating push:"
echo "docker push your-ecr/laravel-app:latest"

3. Deploy using Helm(Simualated)

# Development environment
helm upgrade --install laravel-dev ./laravel-app-chart -f values.dev.yaml

# Staging environment
helm upgrade --install laravel-staging ./laravel-app-chart -f values.staging.yaml

# Production environment
helm upgrade --install laravel-prod ./laravel-app-chart -f values.yaml

Directory like below structure way:

laravel-app-chart/
├── templates/           # Helm deployment templates
├── values.yaml          # Production config
├── values.dev.yaml      # Dev config
├── values.staging.yaml  # Staging config

Jenkinsfile              # Simulated CI/CD pipeline
Dockerfile               # Laravel app Docker build
.env                     # Local env
.env.production          # Production env
.env.dev                 # Development env
.env.staging             # Staging env

