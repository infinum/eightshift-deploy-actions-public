# Secrets - SSH

In order to use the deploy action using SSH, a few secrets must be added to the repository using GitHub repository secrets.

## Env secrets/variables vs Repository secrets/variables

GitHub offers two types of secrets: variables and secrets. The difference between them is that variables are visible in the logs and in the GUI, while secrets are not. Once set secret can't be viewed again.

Also GitHub offers two types of usage for secrets: repository secrets and environment secrets. The difference between them is that repository secrets are available to all workflows in the repository, while environment secrets are available only to the workflows in the environment and this environment is defined in the workflow file.

When setting up secrets, make sure to use environment secrets and make sure you add them to the correct location and correct type based on the sensitivity of the data and the usage.

### Organization secrets

_Responsible person: DevOps or TL_

The following secrets are added to the Infinum organization and are shared across all repositories in the organization. You do not need to be added to projects but you must ask your TL for this secret to allow it on your project.

- `WORDPRESS_GH_ACTIONS` - used for authenticating to GitHub repositories.

### Server secrets

_Responsible person: DevOps or TL + Developer_
Secret type: `Environment secret`.

- `SERVER_SSH_KEY` - used for the authentication to the server (private key).
- `SERVER_USER` - username to connect to the server.
- `SERVER_HOST` - hostname to connect to the server.
- `SERVER_PORT` - port to connect to the server.
- `SERVER_ROOT` - path to the project on the server where the project will be deployed. (relative path from the location you are connecting to using the provided key, without leading and trailing slashes, eg. `www/test.com`)

### Database secrets

_Responsible person: DevOps or TL + Developer_
Secret type: `Environment secret`.

- `DB_NAME` - database name.
- `DB_USER` - database username.
- `DB_PASSWORD` - database password.
- `DB_HOST` - database host (usually `localhost:3306`).

### Salt secrets

_Responsible person: DevOps or TL + Developer_
Secret type: `Repository secret`.

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

_Responsible person: DevOps or TL + Developer_
Secret type: `Repository/Environment secret` based on the usage.

- `SLACK_WEBHOOK` - webhook for the Slack notifications (ask your TL for this secret).
- `BUGSNAG_API_KEY` - API key for the Bugsnag notifications (ask your TL for this secret).

### Adding custom secrets in the deploy process

_Responsible person: DevOps or TL + Developer_
Secret type: `Repository/Environment secret` based on the usage.

To add custom secrets in the deploy process add a custom step after the `Setup WordPress` step in the deploy action. Keep in mind that the value is always wrapped in single quotes.

```yaml
	- name: Setup custom secrets as environment variables
		shell: bash
		run: |
			php wp-cli.phar config set S3_UPLOADS_BUCKET_URL '${{ secrets.S3_UPLOADS_BUCKET_URL }}'
			php wp-cli.phar config set S3_UPLOADS_BUCKET '${{ secrets.S3_UPLOADS_BUCKET }}'

			# if you need boolean value use --raw flag
			php wp-cli.phar config set QM_DB_SYMLINK false --raw
			...
```
