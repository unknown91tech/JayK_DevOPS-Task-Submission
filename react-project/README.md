# Next.js Enterprise Authentication Platform

A production-ready, enterprise-grade Next.js authentication platform with comprehensive CI/CD pipeline, Infrastructure as Code, and monitoring solutions. Built with modern DevOps practices and cloud-native architecture.

## Overview

This project demonstrates a complete enterprise-ready authentication system built with Next.js, featuring:

- **Modern Web Application**: Next.js 14 with TypeScript
- **Secure Authentication**: JWT-based auth with refresh tokens
- **Database Management**: PostgreSQL with Prisma ORM
- **Container Orchestration**: Kubernetes on AWS EKS
- **Infrastructure as Code**: Terraform for AWS resources
- **Configuration Management**: Ansible playbooks
- **CI/CD Pipeline**: GitHub Actions & GitLab CI
- **Comprehensive Monitoring**: Prometheus + Grafana + Alertmanager
- **Production-Ready**: Load balancing, auto-scaling, health checks

## Architecture

```mermaid
graph TB
    subgraph "External"
        U[Users] --> LB[Load Balancer]
        DEV[Developers] --> GH[GitHub/GitLab]
    end
    
    subgraph "CI/CD Pipeline"
        GH --> CI[CI/CD Pipeline]
        CI --> REG[Container Registry]
        CI --> K8S[Kubernetes Cluster]
    end
    
    subgraph "AWS Infrastructure"
        LB --> IG[Internet Gateway]
        IG --> ALB[Application Load Balancer]
        
        subgraph "EKS Cluster"
            ALB --> ING[Ingress Controller]
            ING --> SVC[Service]
            SVC --> POD[Next.js Pods]
            POD --> CM[ConfigMap]
            POD --> SEC[Secrets]
        end
        
        subgraph "Data Layer"
            POD --> RDS[(PostgreSQL RDS)]
            POD --> REDIS[(Redis Cache)]
        end
        
        subgraph "Monitoring"
            POD --> PROM[Prometheus]
            PROM --> GRAF[Grafana]
            PROM --> AM[Alertmanager]
        end
    end
    
    subgraph "External Services"
        AM --> SLACK[Slack Notifications]
        AM --> EMAIL[Email Alerts]
    end
```

## Quick Start

### Prerequisites

Ensure you have the following installed:

- **Node.js** 18+ ([Download](https://nodejs.org/))
- **Docker** & Docker Compose ([Download](https://docker.com/))
- **kubectl** ([Install Guide](https://kubernetes.io/docs/tasks/tools/))
- **Terraform** 1.0+ ([Download](https://terraform.io/))
- **AWS CLI** ([Install Guide](https://aws.amazon.com/cli/))

### 1. Clone & Install

```bash
# Clone the repository
git clone https://github.com/unknown/JayK_DevOPS Task - Submission.git
cd JayK_DevOPS Task - Submission

# Install dependencies
npm install

# Set up environment variables
cp .env.example .env
# Edit .env with your configuration
```

### 2. Local Development

```bash
# Start PostgreSQL
docker-compose up -d postgres

# Run database migrations
npm run db:migrate

# Start development server
npm run dev
```

Visit [http://localhost:3000](http://localhost:3000) to see the application.

### 3. Production Deployment

```bash
# 1. Set up AWS infrastructure
cd infrastructure/terraform
terraform init && terraform apply

# 2. Deploy to Kubernetes
./scripts/deploy.sh production

# 3. Verify deployment
./scripts/health-check.sh production
```

## Development Setup

### Environment Configuration

Create `.env` file with the following variables:

```bash
# Database
DATABASE_URL="postgresql://postgres:password@localhost:5432/nextjs_auth"

# JWT Configuration
JWT_SECRET="your-super-secret-jwt-key-change-this-in-production"
JWT_EXPIRES_IN="7d"

# Application
NODE_ENV="development"
NEXT_PUBLIC_API_URL="http://localhost:3000/api"

# AWS (for production)
AWS_REGION="us-west-2"
AWS_ACCESS_KEY_ID="your-access-key"
AWS_SECRET_ACCESS_KEY="your-secret-key"
```

### Development Commands

```bash
# Development
npm run dev              # Start development server
npm run build            # Build for production
npm run start            # Start production server

# Database
npm run db:migrate       # Run migrations
npm run db:generate      # Generate Prisma client
npm run db:seed          # Seed database

# Quality Assurance
npm run lint             # Run ESLint
npm run lint:fix         # Fix ESLint issues
npm run prettier         # Format code
npm run type-check       # TypeScript check
npm test                 # Run all tests
npm run test:coverage    # Coverage report

# Docker
npm run docker:build     # Build Docker image
npm run docker:run       # Start containers
npm run docker:stop      # Stop containers
```

## Docker Development

### Local Development with Docker

```bash
# Start all services (app + database + monitoring)
docker-compose up -d

# View logs
docker-compose logs -f app

# Access application
open http://localhost:3000

# Stop all services
docker-compose down
```

### Production Docker Setup

```bash
# Build production image
docker build -t nextjs-auth-app:latest .

# Run with production compose
docker-compose -f docker-compose.prod.yml up -d

# Monitor resources
docker stats
```

## Infrastructure Setup

### AWS Infrastructure with Terraform

```bash
cd infrastructure/terraform

# 1. Initialize Terraform
terraform init

# 2. Create terraform.tfvars
cp terraform.tfvars.example terraform.tfvars
# Edit with your AWS configuration

# 3. Plan deployment
terraform plan -var-file="terraform.tfvars"

# 4. Apply infrastructure
terraform apply -var-file="terraform.tfvars"

# 5. Get cluster credentials
aws eks update-kubeconfig --name nextjs-auth-production --region us-west-2
```

### Server Configuration with Ansible

```bash
cd infrastructure/ansible

# 1. Update inventory with your servers
vim inventory/production

# 2. Install and configure servers
ansible-playbook -i inventory/production playbooks/site.yml

# 3. Setup monitoring stack
ansible-playbook -i inventory/production playbooks/monitoring.yml
```

### Kubernetes Configuration

```bash
# Create namespace
kubectl apply -f infrastructure/kubernetes/namespace.yml

# Deploy application
kubectl apply -f infrastructure/kubernetes/

# Check deployment status
kubectl get pods -n nextjs-auth
kubectl get services -n nextjs-auth
kubectl get ingress -n nextjs-auth
```

## Deployment

### Automated Deployment (Recommended)

The CI/CD pipeline automatically deploys when you push to specific branches:

- **Staging**: Push to `develop` branch
- **Production**: Push to `main` branch (requires approval)

```bash
# Deploy to staging
git checkout develop
git push origin develop

# Deploy to production
git checkout main
git merge develop
git push origin main
```

### Manual Deployment

```bash
# Deploy to staging
./scripts/deploy.sh staging latest

# Deploy to production
./scripts/deploy.sh production v1.0.0

# Verify deployment
./scripts/health-check.sh production

# Rollback if needed
./scripts/rollback.sh production
```

### Blue-Green Deployment

```bash
# Create new deployment
kubectl apply -f infrastructure/kubernetes/deployment-blue.yml

# Wait for readiness
kubectl rollout status deployment/nextjs-auth-app-blue -n nextjs-auth

# Switch traffic
kubectl patch service nextjs-auth-service -n nextjs-auth -p '{"spec":{"selector":{"version":"blue"}}}'

# Clean up old deployment
kubectl delete deployment nextjs-auth-app-green -n nextjs-auth
```

## Monitoring & Observability

### Accessing Monitoring Services

```bash
# Prometheus (metrics)
kubectl port-forward -n monitoring svc/prometheus 9090:9090
open http://localhost:9090

# Grafana (dashboards)
kubectl port-forward -n monitoring svc/grafana 3000:80
open http://localhost:3000
# Login: admin / admin123

# Alertmanager (alerts)
kubectl port-forward -n monitoring svc/alertmanager 9093:9093
open http://localhost:9093
```

### Available Dashboards

1. **Application Performance Dashboard**
   - HTTP request rates and response times
   - Error rates by endpoint
   - Active user sessions
   - Memory and CPU usage

2. **Infrastructure Dashboard**
   - Kubernetes cluster health
   - Node resource utilization
   - Pod status and restart counts
   - Network and storage I/O

3. **Business Metrics Dashboard**
   - User registration rates
   - Login success/failure rates
   - API usage patterns
   - Geographic distribution

### Key Metrics

| Metric | Description | Threshold |
|--------|-------------|-----------|
| Response Time (95th percentile) | API response time | < 500ms |
| Error Rate | HTTP 5xx errors | < 1% |
| CPU Usage | Pod CPU utilization | < 70% |
| Memory Usage | Pod memory utilization | < 80% |
| Database Connections | Active DB connections | < 80% of max |

### Alerting Rules

- **Critical**: Application down, database unavailable
- **Warning**: High error rate, slow response times
- **Info**: High resource usage, scaling events

## Testing

### Test Suites

```bash
# Run all tests
npm test

# Unit tests only
npm run test:unit

# Integration tests only
npm run test:integration

# End-to-end tests
npm run test:e2e

# Watch mode for development
npm run test:watch

# Coverage report
npm run test:coverage
```

### Testing Strategy

| Test Type | Coverage | Tools | Purpose |
|-----------|----------|--------|---------|
| **Unit Tests** | Components, utilities | Jest, RTL | Isolated component logic |
| **Integration Tests** | API endpoints | Jest, Supertest | API functionality |
| **E2E Tests** | User workflows | Playwright | Complete user journeys |
| **Performance Tests** | Load testing | k6, Artillery | Scalability validation |
| **Security Tests** | Vulnerabilities | OWASP ZAP, Snyk | Security validation |

### Quality Gates

- **Minimum Coverage**: 80% line coverage
- **Build Success**: All tests must pass
- **Security**: No critical vulnerabilities
- **Performance**: Response time < 500ms (95th percentile)

## Security

### Authentication & Authorization

- **JWT Tokens**: Secure token-based authentication
- **Password Security**: bcrypt hashing with salt
- **Session Management**: Database-backed sessions
- **Rate Limiting**: Prevent brute force attacks
- **CORS Protection**: Configured for specific origins

### Infrastructure Security

- **Network Security**: Private subnets, security groups
- **TLS Encryption**: HTTPS with modern ciphers
- **Secret Management**: Kubernetes secrets, encrypted storage
- **Container Security**: Regular vulnerability scanning
- **Access Control**: RBAC for Kubernetes resources

### Security Headers

```http
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000
Content-Security-Policy: default-src 'self'
```

## Scaling & Performance

### Horizontal Pod Autoscaler

```yaml
# Automatic scaling configuration
minReplicas: 3
maxReplicas: 20
targetCPUUtilization: 70%
targetMemoryUtilization: 80%
```

### Backup & Recovery

```bash
# Create backup
./scripts/backup.sh

# Restore from backup
./scripts/restore.sh backup-20231225_120000.tar.gz

# Test backup integrity
./scripts/test-backup.sh
```

### Database Maintenance

```sql
-- Analyze database performance
ANALYZE;

-- Update statistics
VACUUM ANALYZE;

-- Check index usage
SELECT schemaname, tablename, indexname, idx_scan 
FROM pg_stat_user_indexes 
ORDER BY idx_scan;
```

### Log Management

```bash
# View application logs
kubectl logs -f deployment/nextjs-auth-app -n nextjs-auth

# View aggregated logs
kubectl logs -l app=nextjs-auth-app -n nextjs-auth --tail=100

# Export logs for analysis
kubectl logs deployment/nextjs-auth-app -n nextjs-auth > app-logs.txt
```

## Troubleshooting

### Common Issues & Solutions

#### Application Won't Start

```bash
# Check pod status
kubectl get pods -n nextjs-auth

# Describe pod for events
kubectl describe pod <pod-name> -n nextjs-auth

# Check logs
kubectl logs <pod-name> -n nextjs-auth
```

**Common Causes:**
- Database connection issues
- Missing environment variables
- Insufficient resources
- Image pull errors

#### Database Connection Errors

```bash
# Test database connectivity
kubectl exec -it <pod-name> -n nextjs-auth -- npm run db:migrate

# Check database status
kubectl get pods -n nextjs-auth -l app=postgres
```

**Solutions:**
- Verify DATABASE_URL format
- Check security group rules
- Ensure database is running
- Validate credentials

#### High Memory Usage

```bash
# Check resource usage
kubectl top pods -n nextjs-auth

# Get detailed metrics
kubectl describe pod <pod-name> -n nextjs-auth
```

**Solutions:**
- Increase memory limits
- Optimize application code
- Check for memory leaks
- Scale horizontally

#### SSL Certificate Issues

```bash
# Check certificate status
kubectl describe ingress nextjs-auth-ingress -n nextjs-auth

# Verify certificate in browser
openssl s_client -connect yourapp.com:443
```

**Solutions:**
- Renew SSL certificates
- Update certificate references
- Check DNS configuration
- Verify domain ownership

### Debug Commands

```bash
# Get cluster information
kubectl cluster-info

# Check node status
kubectl get nodes

# Describe problematic resources
kubectl describe <resource-type> <resource-name> -n nextjs-auth

# Access pod shell
kubectl exec -it <pod-name> -n nextjs-auth -- /bin/bash

# Port forward for local debugging
kubectl port-forward service/nextjs-auth-service 3000:80 -n nextjs-auth
```

### Performance Debugging

```bash
# Check resource utilization
kubectl top nodes
kubectl top pods -n nextjs-auth

# Analyze slow queries
kubectl exec -it <postgres-pod> -- psql -U postgres -d nextjs_auth
\x
SELECT query, mean_time, calls FROM pg_stat_statements ORDER BY mean_time DESC LIMIT 10;
```