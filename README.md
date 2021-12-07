# community-voices

[![Build Status](https://travis-ci.org/EnvironmentalDashboard/community-voices.svg?branch=master)](https://travis-ci.org/EnvironmentalDashboard/community-voices)

The Community Voices component of Environmental Dashboard is designed to celebrate and promote thought and action that build stronger, more sustainable and more resilient communities. Community members representing the diversity of this community are being interviewed to share their perspectives. Click below to view recent interviews or search by subject name, interviewer or any keyword or topic to view associated stories. Many of the quotes used for Community Voices slides are taken from these interviews.

## Building / Running

First, ensure that you have Docker installed.
One way of installing it is `brew cask install docker`.
Reference [this link](https://stackoverflow.com/a/43365425/2397924) for debugging purposes.
Once Docker is installed, you are ready to build the Community Voices container.
For this, simply run `./build.sh`.

Next, for development, you would then run `./run-db.sh`.
This creates a local database to use in development.
If you would like to seed this database with production data,
run `./download-prod.sh` and then `./upload-prod.sh`. Note that before running './download-prod.sh' you must create a hidden file 'db.config' that configures the mysql server properly. //explain this step

If you prefer to use a custom dump (or utilize the Cleveland dump),
provide the filename as an argument to `./upload-prod.sh`.

You can also sync your local photo directory with that of the server.
To do this, run `./sync-photos-from-server.sh`.
This will simply populate your `CV_Media` folder - it will never modify the server's.

To run Community Voices, simply run `./run.sh`.
If you provide any command-line arguments, it will run it in our special live server case rather than for the local machine.
The server will then be running on `localhost:3001`.
If you want to stop the server, run `docker stop LOCAL_CV`.
In general, Docker will start the server as soon as you start Docker.

To delete this container later, you can run `docker stop LOCAL_CV` and then
`docker rm LOCAL_CV`.

## Migrations

Because Community Voices comes without an ORM, a custom migration system has been built.
To run a migration, you must run the migration script `migrate/migrate.php` from the context
of the Docker container.
For example, `php migrate/migrate.php scriptName`.

## Programming Guide

When creating new methods in the view and controller of the api, ensure that all function modifiers are `protected` instead of `public`.
This must be done to allow for proper authentication on the api side.
