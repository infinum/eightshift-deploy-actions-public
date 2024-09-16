# Eightshift deploy actions

This repository contains the GitHub actions for deploying Eightshift projects.

## Usage

To use these actions, you need to create a new workflow in your project. You can do that by creating a new YAML (`.yml`) file in the `.github/workflows` directory.

Following examples are included in the `examples` directory:

- _Bundle Artifact_ - bundles the project into a zip file and attaches it to the artifacts.
- _Bundle Release_ - bundles the project into a zip file and attaches it to the release.
- _Deploy_ - deploys the project on the server based on the selected environment and branch.
- _CI_ - runs checks on the project (e.g. linters, unit tests, ...)
- _Rollback_ - rollback the project to the previous release.

## Bundle Artifact

Bundles the project into a zip file and attaches it to the artifacts. The zip file can be downloaded from the GitHub Actions page.

Following examples are included in the `examples/bundle-artifact` directory:

- `plugin.yml` - bundles the plugin artifact.

## Bundle Release

Bundles the project into a zip file and attaches it to the release. The zip file can be downloaded from the GitHub Actions page.

Following examples are included in the `examples/bundle-release` directory:

- `plugin.yml` - bundles the plugin artifact.

## Deploy

Deploys the project to the selected environment, from the selected branch. Dropdowns are set up for picking branches and environments for ease of use.

Following examples are included in the `examples/deploy` directory:

- `infinum-project-with-submodule-theme.yml` - deploys the project with the submodule theme (used for internal projects like Infinum Alliance, Academy, Learn, ... that use the submodule theme from the Infinum theme repository).
- `plugin-project.yml` - deploys a plugin project.
- `theme-project.yml` - deploys a theme project.

## Continuous Integration

Continuous integration (CI) workflows will run tests on the project, and are triggered on every push to the repository.

Following examples are included in the `examples/ci` directory:

- `plugin-project.yml` - runs the tests on plugin projects.
- `theme-project.yml` - runs the tests on theme projects.

## Rollback

Rollback workflow is used to rollback the project to the previous version. It is triggered manually by setting the number of releases to rollback.

Current release is number 1, previous release is number 2, and so on.

Following examples are included in the `examples/rollback` directory:

- `rollback.yml` - runs the release action.

## Actions

Actions located in the `examples/actions` directory can be used in all workflows.

## Secrets

In order to use the deploy action, a few secrets must be added to the repository.

### Organization secrets

The following secrets are added to the Infinum organization and are shared across all repositories in the organization. They do not need to be added to projects.

- `WORDPRESS_GH_ACTIONS` - used for authenticating to GitHub repositories.

### Server secrets

**DevOps will provide these secrets**

- `SERVER_SSH_KEY` - used for the authentication to the server.
- `SERVER_USER` - username to connect to the server.
- `SERVER_HOST` - hostname to connect to the server.
- `SERVER_PORT` - port to connect to the server.
- `SERVER_ROOT` - path to the project on the server where the project will be deployed. (relative path from the location you are connecting to using the provided key, without leading and trailing slashes, eg. `www/infinum.com`)

### Database secrets

**DevOps will provide these secrets**

- `DB_NAME` - database name.
- `DB_USER` - database username.
- `DB_PASSWORD` - database password.
- `DB_HOST` - database host (usually `localhost:3306`).

### Salt secrets

You can generate the salts on the [WordPress Salt Generator](https://api.wordpress.org/secret-key/1.1/s) and add them as secrets.

- `AUTH_KEY`
- `SECURE_AUTH_KEY`
- `LOGGED_IN_KEY`
- `NONCE_KEY`
- `AUTH_SALT`
- `SECURE_AUTH_SALT`
- `LOGGED_IN_SALT`
- `NONCE_SALT`
- `WP_CACHE_KEY_SALT` - used for caching. This salt is not in the default generator output. Make sure you generate it manually.

### Other secrets

- `SLACK_WEBHOOK` - webhook for the Slack notifications (ask your TL for this secret).
- `BUGSNAG_API_KEY` - API key for the Bugsnag notifications (ask your TL for this secret).

### Adding custom secrets in the deploy process

To add custom secrets in the deploy process add a custom step after the `Setup WordPress` step in the deploy action.

```yaml
	- name: Setup custom secrets as environment variables
		shell: bash
		run: |
			php wp-cli.phar config set S3_UPLOADS_BUCKET_URL '${{ secrets.S3_UPLOADS_BUCKET_URL }}'
			php wp-cli.phar config set S3_UPLOADS_BUCKET '${{ secrets.S3_UPLOADS_BUCKET }}'
			...
```

## Server setup

After the deployment the server will switch the current folder with the new symlink from the release folder. This is done to avoid downtime during the deployment but you need to make sure that Nginx is configured properly.

Update the `fastcgi_param` to use realpath root:

```nginx
# Default
fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

# Change to
fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
```

Point the website root to the `current` folder:

```nginx
root <path>/current;
```

## Server structure after the deploy:

- releases
    - 1
    - 2
    - 3
    - ...
- current -> releases/3
- shared
    - uploads
    - ...
