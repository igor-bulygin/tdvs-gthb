Todevise 2.0
================================

### REQUIREMENTS

The minimum requirements are the following:

* PHP 5.6.0
* MongoDB 3.0
* PHP-MongoDB driver 1.6
* NGINX
* Node
* The following PHP extensions:
  * dom
  * curl
  * gd
  * iconv
  * intl
  * json
  * libxml
  * mbstring
  * mcrypt
  * mongo
  * openssl
  * pcre
  * redis
  * Reflection
  * SimpleXML

If running on Windows:
  * fileinfo

* rsync

### CONFIGURATION

#### Database

In order to run this application you must run a MongoDB 3.0 database and create
a database called `todevise`. You also must create an user `utodevise` and a
password `3],+UyY}=2KpBA^V`.

#### Web Server

A modification in the `/etc/hosts` (or equivalent, if you're on Windows) is
required:

```
127.0.0.1	   ddbb.todevise.com
127.0.0.1	   redis.todevise.com
127.0.0.1	   redis-session.todevise.com
127.0.0.1	   redis-cache.todevise.com
```

This is done to ensure future migrations/splits can be done easily.

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

Also, if behind `HTTPS`, add `fastcgi_param HTTPS on;`

#### PHP

Add

	cgi.fix_pathinfo=0
	always_populate_raw_post_data=-1
	upload_max_filesize = 100M
	post_max_size = 100M

to the `php.ini` file.

Make sure that the folder `web/uploads` exists and that it has proper permissions for RW.

#### Deploying

This project will follow the `semver` versioning, thus you'll never deploy from
a branch itself, but instead you'll always deploy the latest tag and then
install dependencies with `composer`.

That means that you must sync the latest stable tab from this repository and
then run `php composer.phar install` on the server.
