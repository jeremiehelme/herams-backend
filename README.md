# who-herams
WHO - WHE - HeRAMS Nigeria
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/WorldHealthOrganization/herams-backend/badges/quality-score.png?b=poc)](https://scrutinizer-ci.com/g/WorldHealthOrganization/herams-backend/?branch=poc)
[![Code Coverage](https://scrutinizer-ci.com/g/WorldHealthOrganization/herams-backend/badges/coverage.png?b=poc)](https://scrutinizer-ci.com/g/WorldHealthOrganization/herams-backend/?branch=poc)
# Set up developer environment

## Requirements
- Git must be available

## Steps
1. Get [Docker](https://docs.docker.com/install/)
2. Get [Docker Compose](https://docs.docker.com/compose/install/)
3. Run the following commands:
 ```
 git clone git@github.com:WorldHealthOrganization/herams-backend.git
 cd herams-backend
 cp .env.default .env
 ```
5. Optionally alter `.env` to suit your preferences, it is recommended to set UID and GID to prevent permission issues
6. Run `docker-compose up serve`

## Result
After taking the above steps you will have everything up and running:
- A database with username `root` and password `secret`, and a user / password / database combo from your `.env` file.
- An application with (invalid) email: `root` and password: `secret`
- A mailcatcher allowing you to inspect mails sent by the system

### Commands available
We expose a number of commands via docker-compose:
- `docker-compose run --rm composer` will run composer, use this to install / update dependencies.
- `docker-composer run --rm codeception` will run the test suite

