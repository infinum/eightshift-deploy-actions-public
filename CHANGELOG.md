# Change Log for the Eightshift Deploy Actions public repository

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](https://semver.org/) and [Keep a CHANGELOG](https://keepachangelog.com/).

## [2.1.0]

### Added

- Added `COMPOSER_OPTIONS` input to `set/composer-install` and `setup/theme-or-plugin`, forwarded to `ramsey/composer-install`'s `composer-options`, so consumers can pass flags such as `--no-dev --optimize-autoloader` without dropping out of the `WORDPRESS_GH_ACTIONS`-authenticated install path. Defaults to `""` — existing consumers are unaffected.
- Added `BUN_INSTALL_ARGS` input to `setup/theme-or-plugin`, defaulting to the previously hardcoded `--ignore-scripts --frozen-lockfile --production`, so consumers whose build step needs devDependencies (e.g. their bundler is a devDependency) can override it.

## [2.0.1]

### Fixed

- Fixed `plugins/install`'s "Install plugins core" step not forwarding `SETUP_FILE` and `OUTPUT_PATH` to `install-core` (the `paid` and `eightshift` sibling steps already forwarded both), which made consumers whose `setup.json`/plugins directory isn't at the repo root silently fail or install into the wrong location.

## [2.0.0]

### Added

- Added `lint/node` action that installs Bun and runs `bun install`, `bun run build`, and `bun run test` in a single step.
- Added `lint/composer` action that bundles PHP setup, Composer install, and `composer run test`.
- Added `set/composer-install` reusable action that handles authenticated and unauthenticated installs through a single `WORDPRESS_GH_ACTIONS` input.
- Added `WORDPRESS_GH_ACTIONS` input to `lint/php-cs`, `lint/php-stan`, `setup/theme-or-plugin`, `setup/theme-or-plugin-legacy`, `setup/theme-from-submodule`, and `setup/theme-from-submodule-legacy` so private Composer packages can be resolved without separate `COMPOSER_AUTH` plumbing.
- Added "Copy config files" step in deploy workflow examples that promotes files from `./config/` to the project root and removes `./config` and `./docs` before deploy.

### Changed

- Updated deploy workflow examples to use `set/env@main` with `WORDPRESS_*`-prefixed env vars instead of inline `wp config set` calls for salts, keys, and database credentials.
- Updated `lint/php-cs`, `lint/php-stan`, and all `setup/*` actions to install Composer dependencies through `set/composer-install@main` instead of calling `ramsey/composer-install` directly.
- Updated CI workflow examples to use the new combined `lint/node` and `lint/composer` actions; PHP and Node matrix configuration is no longer required for the standard pipeline.
- Updated SSH deploy workflows to fall back to the `staging` environment when the `ENVIRONMENT` input is empty.

### Removed

- Removed `NODE_VERSION` input from `lint/assets`, `setup/theme-or-plugin`, `setup/theme-or-plugin-legacy`, `setup/theme-from-submodule`, and `setup/theme-from-submodule-legacy`. Bun is always used at `latest`.
- Removed the `set/special-constants@main` step from deploy workflow examples — those constants are now provided as env vars through `set/env@main`.
- Removed inline `wp config set` blocks for `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`, `WP_CACHE_KEY_SALT`, `WP_ENVIRONMENT_TYPE`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, and `DB_HOST` from deploy workflow examples.
- Removed the Bugsnag sourcemap upload step from the Docker deploy workflow examples.
- Removed the `PHP_VERSION` input from deploy workflow examples that call `setup/wordpress@main` — the action default is used.

### Fixed

- Fixed leftover `id: login-ecr` reference in `docker/build-push` that was no longer used.

---

### Migration guide (1.x → 2.0)

#### 1. Drop `NODE_VERSION` from all action calls

Any workflow passing `NODE_VERSION` to `lint/assets`, `setup/theme-or-plugin`, `setup/theme-from-submodule`, or their `-legacy` variants must remove it — Bun is always used at `latest`.

```yaml
# before
- uses: infinum/eightshift-deploy-actions-public/.github/actions/setup/theme-or-plugin@main
  with:
    PROJECT_PATH: wp-content/plugins/<plugin_name>
    NODE_VERSION: latest

# after
- uses: infinum/eightshift-deploy-actions-public/.github/actions/setup/theme-or-plugin@main
  with:
    PROJECT_PATH: wp-content/plugins/<plugin_name>
    WORDPRESS_GH_ACTIONS: ${{ secrets.WORDPRESS_GH_ACTIONS }}
```

#### 2. Pass `WORDPRESS_GH_ACTIONS` to setup and lint actions

If `composer.json` resolves any private Infinum/Eightshift packages, add `WORDPRESS_GH_ACTIONS: ${{ secrets.WORDPRESS_GH_ACTIONS }}` to every `setup/*`, `lint/php-cs`, `lint/php-stan`, and `lint/composer` call. Public-only projects can omit it.

#### 3. Replace `wp config set` + `set/special-constants` with `set/env@main`

```yaml
# before
- name: Setup custom secrets as environment variables
  shell: bash
  run: |
    wp config set AUTH_KEY '${{ secrets.AUTH_KEY }}'
    wp config set DB_NAME '${{ secrets.DB_NAME }}'
    # ...

- name: Set special constants
  uses: infinum/eightshift-deploy-actions-public/.github/actions/set/special-constants@main

# after
- name: Setup custom secrets as environment variables
  uses: infinum/eightshift-deploy-actions-public/.github/actions/set/env@main
  with:
    VARS: |
      WORDPRESS_DB_NAME=${{ secrets.DB_NAME }}
      WORDPRESS_DB_USER=${{ secrets.DB_USER }}
      WORDPRESS_DB_PASSWORD=${{ secrets.DB_PASSWORD }}
      WORDPRESS_DB_HOST=${{ secrets.DB_HOST }}
      WORDPRESS_AUTH_KEY=${{ secrets.AUTH_KEY }}
      WORDPRESS_SECURE_AUTH_KEY=${{ secrets.SECURE_AUTH_KEY }}
      WORDPRESS_LOGGED_IN_KEY=${{ secrets.LOGGED_IN_KEY }}
      WORDPRESS_NONCE_KEY=${{ secrets.NONCE_KEY }}
      WORDPRESS_AUTH_SALT=${{ secrets.AUTH_SALT }}
      WORDPRESS_SECURE_AUTH_SALT=${{ secrets.SECURE_AUTH_SALT }}
      WORDPRESS_LOGGED_IN_SALT=${{ secrets.LOGGED_IN_SALT }}
      WORDPRESS_NONCE_SALT=${{ secrets.NONCE_SALT }}
      WORDPRESS_CACHE_KEY_SALT=${{ secrets.WP_CACHE_KEY_SALT }}
      WP_ENVIRONMENT_TYPE=${{ needs.context.outputs.environment }}
```

Every variable now needs the `WORDPRESS_` prefix based on you project setup. Your `wp-config.php` (or `./config/wp-config.php`) must read these env vars instead of relying on `wp config set`-managed constants.

#### 4. Replace separate lint jobs with the combined actions

```yaml
# before
jobs:
  lint:
    strategy:
      matrix:
        node: ["latest"]
    steps:
      - uses: infinum/eightshift-deploy-actions-public/.github/actions/lint/assets@main
        with:
          NODE_VERSION: ${{ matrix.node }}
          PROJECT_PATH: wp-content/plugins/<plugin_name>

  phpstan:
    strategy:
      matrix:
        php: ["8.4"]
    steps:
      - uses: infinum/eightshift-deploy-actions-public/.github/actions/lint/php-stan@main
        with:
          PHP_VERSION: ${{ matrix.php }}
          PROJECT_PATH: wp-content/plugins/<plugin_name>

  phpcs:
    strategy:
      matrix:
        php: ["8.4"]
    steps:
      - uses: infinum/eightshift-deploy-actions-public/.github/actions/lint/php-cs@main
        with:
          PHP_VERSION: ${{ matrix.php }}
          PROJECT_PATH: wp-content/plugins/<plugin_name>

# after
jobs:
  node:
    runs-on: ubuntu-latest
    steps:
      - uses: infinum/eightshift-deploy-actions-public/.github/actions/lint/node@main
        with:
          PROJECT_PATH: wp-content/plugins/<plugin_name>

  composer:
    runs-on: ubuntu-latest
    steps:
      - uses: infinum/eightshift-deploy-actions-public/.github/actions/lint/composer@main
        with:
          PROJECT_PATH: wp-content/plugins/<plugin_name>
          WORDPRESS_GH_ACTIONS: ${{ secrets.WORDPRESS_GH_ACTIONS }}
```

Your project must expose `composer run test`, `bun run build`, and `bun run test` scripts.

#### 5. Add the "Copy config files" step to deploy workflows

If you keep config files in `./config/`, add the copy step before the deploy step:

```yaml
- name: Copy config files
  run: |
    cp ./config/wp-config-helpers.php ./wp-config-helpers.php
    cp ./config/wp-constants.php ./wp-constants.php
    cp ./config/wp-constants-server.php ./wp-constants-server.php
    cp ./config/wp-constants-project.php ./wp-constants-project.php
    cp ./config/wp-config.php ./wp-config.php
    cp ./config/wordfence-waf.php ./wordfence-waf.php
    rm -rf ./config
    rm -rf ./docs
```

#### 6. Re-add Bugsnag sourcemaps if you still use them

The Docker deploy workflow examples no longer ship the Bugsnag sourcemap upload step. If you use Bugsnag, capture the version output and add the step back manually:

```yaml
- name: Update version number
  id: update-version
  uses: infinum/eightshift-deploy-actions-public/.github/actions/set/version-theme@main
  with:
    PROJECT_PATH: wp-content/themes/<theme_name>

- name: Upload sourcemaps to Bugsnag
  if: ${{ needs.context.outputs.environment == 'production' && inputs.SKIP_SOURCEMAPS != true }}
  uses: infinum/eightshift-deploy-actions-public/.github/actions/set/bugsnag-sourcemaps@main
  with:
    API_KEY: ${{ secrets.BUGSNAG_API_KEY }}
    APP_VERSION: ${{ steps.update-version.outputs.version }}
    ENVIRONMENT: ${{ needs.context.outputs.environment }}
    PATHS: |
      wp-content/themes/<theme_name>/public
      wp-content/plugins/<plugin_name>/public
```

## [1.0.0]

- Initial production release.

[2.0.1]: https://github.com/infinum/eightshift-deploy-actions-public/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/infinum/eightshift-deploy-actions-public/compare/1.0.0...2.0.0
[1.0.0]: https://github.com/infinum/eightshift-deploy-actions-public/releases/tag/1.0.0
