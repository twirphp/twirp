# Client/Server example

## Usage

```bash
$ docker run --rm -it -p 8080:8080 twirphp php -S 0.0.0.0:8080 example/server.php
$ docker run --rm -it twirphp php example/client.php http://localhost:8080
```

In case of using Docker on Mac the client command is:

```bash
$ docker run --rm -it twirphp php example/client.php http://docker.for.mac.localhost:8080
```


For reference implementations in go check the [original repository](https://github.com/twitchtv/twirp/tree/master/example).
