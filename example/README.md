# Client/Server example

## Usage

```bash
$ docker build -t twirphpexample .
$ docker run --rm -it -p 8080:8080 twirphpexample php -S 0.0.0.0:8080 server.php
$ docker run --rm -it twirphpexample php client.php http://localhost:8080
```

In case of using Docker on Mac the client command is:

```bash
$ docker run --rm -it twirphpexample php client.php http://docker.for.mac.localhost:8080
```
