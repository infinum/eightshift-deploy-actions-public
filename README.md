# Eightshift deploy actions - PUBLIC

This repository contains the GitHub actions for deploying Eightshift projects used on all public repositories.

## Usage

To use these actions, you need to create a new workflow in your project. You can do that by creating a new YAML (`.yml`) file in the `.github/workflows` directory.

Following examples are included in the `examples` directory:

* _CI_ - runs checks on the project (e.g. linters, unit tests, ...)

## Continuous Integration

Continuous integration (CI) workflows will run tests on the project, and are triggered on every push to the repository.

Following examples are included in the `examples/ci` directory:

* `plugin-project.yml` - runs the tests on plugin projects.
