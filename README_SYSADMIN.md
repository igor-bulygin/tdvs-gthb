Todevise 2.0
================================

REQUIREMENTS
------------

The minimum requirements are the following:

* PHP 5.5.0
* MongoDB 3.0
* PHP-MongoDB driver 1.6
* NGINX

CONFIGURATION
-------------

#### Database

In order to run this application you must run a MongoDB 3.0 database and create
a database called `todevise`. You also must create an user `utodevise` and a
password `3],+UyY}=2KpBA^V`.

#### Web Server

A modification in the `/etc/hosts` (or equivalent, if you're on Windows) is
required:

```
127.0.0.1       ddbb.todevise.com
```

This is how the `server` section in NGINX's config should look like:

	server {
		charset utf-8;
		client_max_body_size 128M;

		listen 80;

		server_name todevise.com;
		root		/path/to/basic/web;
		index	   index.php;

		access_log  /path/to/basic/log/access.log;
		error_log   /path/to/basic/log/error.log;

		location / {
			try_files $uri $uri/ /index.php?$args;
		}

		# uncomment to avoid processing of calls to non-existing static files by Yii
		#location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
		#	try_files $uri =404;
		#}
		#error_page 404 /404.html;

		location ~ \.php$ {
			include fastcgi_params;
			fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
			fastcgi_pass   127.0.0.1:9000;
			#fastcgi_pass unix:/var/run/php5-fpm.sock;
			try_files $uri =404;
		}

		location ~ /\.(ht|svn|git) {
			deny all;
		}
	}
