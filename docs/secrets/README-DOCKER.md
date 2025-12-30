# Secrets - Docker

Secrets using Docker deployment are stored in AWS SSM parameter store and are available inside the container using environment variables. As a developer you can't access these secrets directly. All secrets must be added in the AWS SSM parameter store and must be defined in the `task definition JSON` for the ECS service.

All secrets are defined in the `config/wp-config-container.php` file.
