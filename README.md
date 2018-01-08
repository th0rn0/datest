# Skybet Test

Skybet JS TECH TEST. 

## Requirements

Docker-compose
Makefile

## Install the App

Install the App:
```
make install
```

To install for development:
```
make install-dev
```

## Run Tests

```
make test
```

## Run the app

Then to run the app:
```
make
```

Stop app:
```
make stop
```

The API has been included within the local docker-compose file.

## Reasons for use

#### PHP

While the obvious choice for this would've been something like React and Node I wanted to stick to my strengths. While I know some JS I know more PHP and felt more confident using PHP for this test. My strengths are more backend and infrastructure than front end. I specifically went with a FPM varient for that extra performance.

#### Bootsrap

Simple and easy to use with plenty of extensions

#### Silex

I wanted to use a micro framework that is extremely light. Laravel could be considered too heavy for such a small app so Silex was a good choice. Silex also comes bundled with Twig template engine for rendering the views.

#### Docker

As mentioned above I am more focuse on backend and infrastructure. I wanted to show my skills in Docker and Docker-Compose. I also used a composer docker image to run compose install in the root. I split out all all the services in the docker-compose for single responsibilty and to allow for easy transfer to other tech stacks if nessecary in the future (As in extending the app hypothetically).

#### NGINX

Extremely light weight and easy to work with. Apache would've worked just fine though.

## Tasks

The only tasks I didnt manage to complete were the Lazy loading on the API, the websocket and the overview grouping. The websocket and lazy loading I struggled with, while I think it is possible using the tech I used (using AJAX), this is not something I am too familiar with. The overview grouping I just ran out of time.

The unit tests I also failed on. I couldn't find a way to mock the API and ran out of time. I have added placeholders for all the tests I would've implemented to allow phpunit to run.