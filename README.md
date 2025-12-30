# WordPress deploy actions

This repository contains the GitHub actions for deploying WordPress projects.

To use these actions, you need to create a new workflow in your project. You can do that by creating a new YAML (`.yml`) file in the `.github/workflows` directory.

Depending on the type of the project and infrastructure, you can choose to deploy using SSH or Docker.

Standard projects contains workflows that can be find in the `examples` directory:

- ci.yml - runs checks on the project (e.g. linters, unit tests, ...)
- deploy.yml - deploys the project on the server based on the selected environment and branch.
- rollback.yml - rollback the project to the previous release.

## Documentation

Documentation SSH:

- [Server setup - SSH](docs/server-setup/README-SSH.md)
- [Secrets - SSH](docs/secrets/README-SSH.md)

Documentation Docker:

- [Docker setup](docker)
- [Server setup - Docker](docs/server-setup/README-DOCKER.md)
- [Secrets - Docker](docs/secrets/README-DOCKER.md)

## Workflow examples

- [Bundle artifact](workflow-examples/bundle-artifact)
- [Bundle release](workflow-examples/bundle-release)
- [CI](workflow-examples/ci)
- [Deploy - Docker](workflow-examples/deploy/docker)
- [Deploy - SSH](workflow-examples/deploy/ssh)
- [Rollback - Docker](workflow-examples/rollback/docker)
- [Rollback - SSH](workflow-examples/rollback/ssh)

## GitHub Actions

- [Clean up - project](.github/actions/cleanup/project)
- [Clean up - root](.github/actions/cleanup/root)
- [Docker - build and push](.github/actions/docker/build-push)
- [Lint - Assets](.github/actions/lint/assets)
- [Lint - PHP - CS](.github/actions/lint/php-cs)
- [Lint - PHP](.github/actions/lint/php-stan)
- [Plugins - Install](.github/actions/plugins/install)
- [Plugins - Core plugins](.github/actions/plugins/install-core)
- [Plugins - Eightshift plugins](.github/actions/plugins/install-eightshift)
- [Plugins - Paid plugins](.github/actions/plugins/install-paid)
- [Set cache](.github/actions/set/cache)
- [Set correct folder/file permissions](.github/actions/set/permissions)
- [Set special constants](.github/actions/set/special-constants)
- [Set version - plugin](.github/actions/set/version-plugin)
- [Set version - theme](.github/actions/set/version-theme)
- [Setup theme from submodule](.github/actions/setup/theme-from-submodule)
- [Setup theme or plugin](.github/actions/setup/theme-or-plugin)
- [Setup WordPress](.github/actions/setup/wordpress)
- [Setup WordPress - plugins](.github/actions/setup/wordpress-plugins)
- [Slack - notification](.github/actions/slack-notification)
- [SSH - release cleanup](.github/actions/ssh/releases-cleanup)
- [SSH - rollback](.github/actions/ssh/rollback)
- [SSH - server deploy](.github/actions/ssh/server-deploy)
- [SSH - server deploy - self-hosted runner](.github/actions/ssh/server-deploy-self-hosted-runner)
