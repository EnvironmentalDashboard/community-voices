# community-voices

The Community Voices component of Environmental Dashboard is designed to celebrate and promote thought and action that build stronger, more sustainable and more resilient communities. Community members representing the diversity of this community are being interviewed to share their perspectives. Click below to view recent interviews or search by subject name, interviewer or any keyword or topic to view associated stories. Many of the quotes used for Community Voices slides are taken from these interviews.

## Building / Running

First, ensure that you have Docker installed.
One way of installing it is `brew cask install docker`.
Reference [this link](https://stackoverflow.com/a/43365425/2397924) for debugging purposes.
Once Docker is installed, you are ready to build the Community Voices container.
For this, simply run `./build.sh`.

To run, simply run `./run.sh`.
If you provide any command-line arguments, it will run it in our special live server case rather than for the local machine.
The server will then be running on `localhost:3002`.
If you want to stop the server, run `docker stop PROD_CV`.
