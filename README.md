## Scraper App

### Overview

This application allows you to scrape an example website, provided by Wireless Logic, using the `app:scrape` command.
By default, it will output the result in JSON.

### Pre-requisites

This application uses Docker containers, to ensure that the application will run on any operating system. Therefore,
you will need to install [Docker Desktop](https://www.docker.com/products/docker-desktop/) on your computer to run this
application.

### How to start the application

To run the application, once you have Docker Desktop installed and running and have cloned the repository into the
directory of your choice, you can simply `cd` into the repository and run `make up` to bring up the application
docker container.

*NOTE: The first time you bring the container up, the Docker Image being used will have to build. However, the next time
you bring up the containers, you will not have to build the docker image again. If you do want to build the image again,
you can simply run `make build`.*

### How do I run the scrape command?

Once you have the Docker container up and running, to run the scrape command, you can simply run `make scrape`.
The command will run and output the relevant data.

The Make command is simply an alias that runs an alternative command. Therefore, if you want more control over what
is passed to the command when you run it, you can run the following:

```
docker exec -it scrape_app bash -c "php bin/console app:scrape"
```

Using this method, allows you to append different options to the command, which you can read more about by running the
command with the `--help` option appended
(e.g. `docker exec -it scrape_app bash -c "php bin/console app:scrape --help"`).

### How do I run the tests for this application?

Just like running the command, there are several `make` commands that have been provided for convenience.

**To run all tests**: `make run-tests`

**To run only Unit tests**: `make run-unit-tests`

**To run only Functional tests**: `make run-functional-tests`

Ultimately, the underlying command that the `make` commands initially run for tests is
`docker exec -it scrape_app bash -c "./vendor/bin/simple-phpunit"`, so you can use this as a base command which you
can add additional options and arguments to in your terminal.

Please see the `Makefile` in the root directory, for examples of how we are targeting Unit and Functional tests only.