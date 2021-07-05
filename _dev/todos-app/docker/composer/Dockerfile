FROM composer:2.1

# RUN addgroup -g 1000 myuser && adduser -G myuser -g myuser -s /bin/sh -D myuser

# USER root

WORKDIR /application

ENTRYPOINT [ "composer", "--ignore-platform-reqs" ]
