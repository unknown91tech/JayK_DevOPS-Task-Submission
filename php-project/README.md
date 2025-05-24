# BLUMEX Crypto System

The BLUMEX Crypto System is a fictional Web3-based financial and trading platform built on the BLUMECHAIN blockchain. This repository contains the implementation of a robust DevOps pipeline, including Dockerization, CI/CD, Infrastructure as Code (IaC), and monitoring, all adhering to PHP exclusivity for automation tasks.



## Project Overview
BLUMEX is a decentralized platform for cryptocurrency transactions, including asset swapping, staking, and yield farming, integrated with the BLUMECHAIN blockchain. This project implements:
- **Dockerization**: Containers for the PHP-based application, BLUMECHAIN node, and MySQL database.
- **CI/CD Pipeline**: Automated linting, testing, building, and deployment using GitHub Actions.
- **Infrastructure as Code (IaC)**: AWS infrastructure provisioning using Terraform, automated via PHP.
- **Monitoring and Observability**: Real-time monitoring with Prometheus and Grafana, exposing metrics for blockchain performance, API health, and security insights.

All automation scripts are written in PHP to meet the PHP exclusivity requirement, and the codebase is manually developed to ensure originality.

## Repository Structure
```
blumex-crypto-system/
├── .github/
│   └── workflows/
│       └── ci-cd.yml          # GitHub Actions CI/CD pipeline
├── app/
│   └── metrics.php            # Prometheus metrics endpoint for BLUMEX app
├── blumechain/                # BLUMECHAIN node code
├── mysql-config/
│   └── my.cnf                # MySQL configuration
├── scripts/
│   ├── lint.php              # PHP linting script
│   ├── security_scan.php     # Security scanning script
│   ├── e2e_tests.php         # End-to-end testing script
│   ├── deploy.php            # Deployment script for AWS ECS
│   ├── smoke_tests.php       # Smoke testing script
│   ├── rollback.php          # Rollback script for failed deployments
│   ├── provision.php         # IaC provisioning script
│   └── setup_monitoring.php  # Monitoring setup script
├── iac/
│   ├── main.tf               # Terraform main configuration
│   ├── variables.tf          # Terraform variables
│   ├── outputs.tf            # Terraform outputs
├── monitoring/
│   ├── prometheus.yml        # Prometheus configuration
│   ├── alert_rules.yml       # Prometheus alerting rules
│   └── grafana/provisioning/
│       ├── datasources/
│       │   └── datasource.yml # Grafana data source
│       └── dashboards/
│           └── blumex.json    # Grafana dashboard
├── Dockerfile                 # BLUMEX app Dockerfile
├── Dockerfile.mysql           # MySQL Dockerfile
├── Dockerfile.blumechain      # BLUMECHAIN node Dockerfile
├── docker-compose.yml         # Docker Compose configuration
├── .env.example               # Example environment file
├── .gitignore                 # Git ignore file
└── README.md                  # This file
```

## Prerequisites
- **Docker**: For containerization (Docker Desktop or Docker CLI).
- **Docker Compose**: For orchestrating services locally.
- **PHP**: Version 8.2 with extensions (`pdo_mysql`, `gd`, `zip`).
- **Composer**: For PHP dependency management.
- **Terraform**: For IaC provisioning.
- **AWS CLI**: For interacting with AWS services.
- **GitHub Account**: For CI/CD and container registry.
- **Node.js**: For BLUMECHAIN node (assumed Node.js-based).
- **Git**: For version control.

## Setup Instructions

### Environment Configuration
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/[YourName]/blumex-crypto-system.git
   cd blumex-crypto-system
   ```

2. **Create `.env` File**:
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update `.env` with secure values:
     ```
     # BLUMEX Application
     BLUMEX_API_KEY=your_secure_api_key_here
     APP_ENV=production

     # MySQL Database
     MYSQL_ROOT_PASSWORD=secure_root_password
     MYSQL_DATABASE=blumex
     MYSQL_USER=blumexuser
     MYSQL_PASSWORD=secure_db_password

     # BLUMECHAIN Node
     BLUMECHAIN_NODE_URL=http://blumechain-node:8545
     NODE_ENV=production
     RPC_PORT=8545

     # AWS (CI/CD and IaC)
     AWS_REGION=us-east-1
     AWS_ACCESS_KEY_ID=your_aws_access_key
     AWS_SECRET_ACCESS_KEY=your_aws_secret_key

     # GitHub Container Registry
     GHCR_TOKEN=your_github_personal_access_token
     GITHUB_REPOSITORY=yourusername/blumex-crypto-system

     # Staging Environment
     STAGING_URL=http://your-staging-url.example.com

     # Grafana
     GRAFANA_ADMIN_PASSWORD=secure_grafana_password
     ```

3. **Secure `.env`**:
   - Ensure `.env` is in `.gitignore`:
     ```
     .env
     ```
   - Restrict file permissions:
     ```bash
     chmod 600 .env
     ```

### Docker Setup
1. **Build and Run Services**:
   ```bash
   docker-compose up --build -d
   ```

2. **Access Services**:
   - BLUMEX App: `http://localhost` (configure Nginx separately if needed).
   - BLUMECHAIN Node: `http://localhost:8545`.
   - MySQL: `db:3306` (internal network).
   - Prometheus: `http://localhost:9090`.
   - Grafana: `http://localhost:3000` (login: `admin`/`GRAFANA_ADMIN_PASSWORD`).
   - Node Exporter: `http://localhost:9100`.
   - MySQL Exporter: `http://localhost:9104`.

3. **Verify Container Security**:
   - Scan images with Trivy:
     ```bash
     trivy image blumex-app:latest
     trivy image blumechain-node:latest
     trivy image blumex-db:latest
     ```

### CI/CD Pipeline
1. **Configure GitHub Secrets**:
   - In GitHub, go to Settings > Secrets and variables > Actions > New repository secret.
   - Add: `MYSQL_ROOT_PASSWORD`, `MYSQL_PASSWORD`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `GHCR_TOKEN`, `STAGING_URL`, `GRAFANA_ADMIN_PASSWORD`.

2. **Push Code**:
   ```bash
   git add .
   git commit -m "Initial commit with CI/CD configuration"
   git push origin main
   ```

3. **Monitor Pipeline**:
   - Check the Actions tab in GitHub for pipeline execution (linting, testing, build, deployment, smoke tests).
   - Verify logs for each stage.

### Infrastructure as Code (IaC)
1. **Install Terraform**:
   ```bash
   sudo apt-get install terraform
   ```

2. **Provision Infrastructure**:
   ```bash
   php scripts/provision.php apply
   ```

3. **Verify Infrastructure**:
   - Check AWS Console for ECS cluster (`blumex-staging-cluster`), EC2 instance (`blumechain-node`), and RDS instance (`blumex-staging-db`).
   - View outputs:
     ```bash
     terraform -chdir=iac output
     ```

4. **Teardown (if needed)**:
   ```bash
   php scripts/provision.php destroy
   ```

### Monitoring and Observability
1. **Run Monitoring Setup Script**:
   ```bash
   php scripts/setup_monitoring.php
   ```

2. **Access Grafana Dashboard**:
   - Log in to Grafana (`http://localhost:3000`).
   - Navigate to Dashboards > BLUMEX Crypto System Dashboard.
   - Monitor:
     - **Blockchain Performance**: BLUMECHAIN node status, transaction throughput.
     - **API Health**: Error rates, response times, authentication failures.
     - **Security Insights**: Suspicious transactions, unauthorized access attempts.

3. **Configure Alerts**:
   - In Grafana, go to Alerting > Notification Channels.
   - Add email or Slack integration for alerts (e.g., `HighApiErrorRate`, `BlumechainNodeDown`).

## Security Measures
- **Docker**: Uses minimalist base images (`php:8.2-fpm-alpine`, `node:18-alpine`, `mysql:8.0`), non-root execution, and Trivy for vulnerability scanning.
- **CI/CD**: Secrets stored in GitHub Secrets, with PHP scripts for linting (`phpcs`), security scanning (`composer audit`), and testing (`phpunit`).
- **IaC**: VPC with public/private subnets, least privilege IAM roles, and encrypted RDS credentials.
- **Monitoring**: Alerts for high error rates, node downtime, and suspicious transactions; metrics exposed via PHP endpoint (`metrics.php`).
- **Secrets Management**: Sensitive data in `.env` or GitHub Secrets, never hardcoded.

