Todevise 2.0
================================

### REQUIREMENTS

The minimum requirements are the following:

* docker
* docker-compose 1.6

### INSTALATION

This project can be setup in a few minutes using Docker. The one and only thing
you must do to achieve this is to run `docker-compose up` at the root folder of
the project.

This command will download all required Docker images, build the required
dependencies, insert demo data in the database and start a server. The server
will be available at `localhost:8080` on your machine (host).

You're supposed to run `docker-compose up` every time you want to start the server.
The Docker images will be build only once.

### NOTES

* The first time `docker-compose up` is ran the process might take a while.

* Once the process is one, you should see the message `Done` from the `php` docker
instance.

* If the process takes too long at the `Updating dependencies` or if the process
fails with some message like `file could not be downloaded: php_network_getaddresses: getaddrinfo failed: Name or service not known`, restart the process.

### TIPS

* It's highly recommended to import the latest available data from the development
server's database. (Ask @jordiv)

* It's highly recommended to sync the devisers and products photos from the
development server. (`sync_uploads.sh`)
