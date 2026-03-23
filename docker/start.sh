#!/bin/bash
envsubst '$PORT' < /etc/nginx/sites-available/default > /etc/nginx/sites-enabled/default
php-fpm -D
nginx -g 'daemon off;'
```

---

## Folder Structure:
```
your-project/
├── docker/
│   ├── nginx.conf
│   ├── supervisord.conf
│   └── start.sh        ← nai file
├── Dockerfile
└── ...