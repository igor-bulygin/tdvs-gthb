Todevise 2.0
================================

### TOOLS


You are free to use the tools you most like, but here is a list of the
tools I'm using in this project:

* MongoChef
* PHPStorm

###CODE

I'm using TABs for indent, with 4 spaces width, so please follow this style.
Also, please follow the style of indent.

### BROWSERS SUPPORT

Todevise won't support any older browsers than the following:

* Safari 7+
* IE 11+ (Spartan?)
* Chrome (evergreen)
* Firefox (evergreen)

### DIRECTORY STRUCTURE

	assets/			contains assets definition
	commands/		  contains console commands (controllers)
	config/			contains application configurations
	controllers/	   contains Web controller classes
	mail/			  contains view files for e-mails
	models/			contains model classes
	runtime/		   contains files generated during runtime
	tests/			 contains various tests for the basic application
	vendor/			contains dependent 3rd-party packages
	views/			 contains view files for the Web application
	web/			   contains the entry script and Web resources

### INSTALLATION

This application (both server and client side code) is managed by `composer`.
That means that you need both `composer` and `composer-asset-plugin` installed.
First install `composer` (the process depends on your OS, so use Google to
figure out how to install it, then install the `composer-asset-plugin` with the
following command:

~~~
php composer.phar global require "fxp/composer-asset-plugin:1.0.0"
~~~

You should check all the required PHP extensions in the `README_SYSADMIN.md` file.

Finally, run

~~~
php composer.phar install
~~~

### UPDATES

Keep in mind that you should run

~~~
php composer.phar update
~~~

only if you already have ran

~~~
php composer.phar install
~~~

and you have made changes to the `composer.json` file.
If this is not the case, you should always  run

~~~
php composer.phar install
~~~

### CONFIGURATION

#### Database

Check the `Database` section in the `README_SYSADMIN.md` file.

#### Web server

Check the `Web Server` section in the `README_SYSADMIN.md` file.

### RUNNING

If you're lazy, you can use PHP's built in server. Just run

```
DEV=1 php -S 127.0.0.1:8080
```

inside the `web/` folder and you're ready! Note the environment variable `DEV`.
If it's value is not `1`, you'll be running the application in production mode.

If you want to use NGINX instead, have a look at the `README_SYSADMIN.md` file.
