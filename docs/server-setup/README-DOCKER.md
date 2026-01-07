# Server setup - Docker

_Responsible person: DevOps_ + Developer

Deploying docker is a bit different than deploying using SSH. The Docker image is built using GitHub Actions and pushed to AWS ECR. The ECS service is then updated to use the new image.

Your project must contain the following files from the `docker` folder:

- `.github/workflows` - all Docker deployment workflows from the `workflow-examples/deploy/docker` folder.
- `.aws` - AWS task definition JSON files for the ECS service.
- `config/wp-config-container.php` - WordPress configuration file for the container that is used to store all secrets, configurations and other settings that is in the deploy process copied to the project root and becomes `wp-config.php`.
- `config/nginx.conf` - Nginx configuration file for the container.
- `config/robots.txt` - robots.txt file for the container, copied to the project root and becomes `robots.txt`.
- `config/llms.txt` - llms.txt file for the container, copied to the project root and becomes `llms.txt`.
- `config/wordfence-waf.php` - Wordfence WAF file for the container, copied to the project root and becomes `wordfence-waf.php`.
- `Dockerfile` - Dockerfile for the container with the WordPress installation and configuration. You set WordPress version and PHP version in the Dockerfile.

In order for the Docker setup to work properly you project `MUST` have Eightshift Utils plugin installed and active in the database, together with the _'Knock-knock' route_ feature activated to make sure website health checks work properly.

If you are setting up the Docker setup for the first time, you need to ask your `DevOps` to connect to the server using SSM and activate the `Eightshift Utils` plugin using the `wp-cli.phar` command.

```bash
wp plugin activate eightshift-utils
```
