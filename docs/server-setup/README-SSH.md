# Server setup - SSH

_Responsible person: DevOps_

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

### Server structure after the deploy:

- releases
  - 1
  - 2
  - 3
  - ...
- current -> releases/3
- shared
  - uploads
  - ...
