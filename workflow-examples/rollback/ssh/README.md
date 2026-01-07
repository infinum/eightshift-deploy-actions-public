### Rollback using SSH

Rollback workflow is used to rollback the project to the previous version. It is triggered manually by setting the number of releases to rollback.

Current release is number 1, previous release is number 2, and so on.

This rollback method uses SSH to connect to the server and rollback the project.

- `rollback.yml` - runs the release action.
