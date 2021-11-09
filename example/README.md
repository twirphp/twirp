# Client/Server example

## Usage

In the repository root run the following:

```shell
make generate
```

Then enter the example directory:

```shell
cd example
```

Launch the server:

```shell
php -S 127.0.0.1:8080 server.php
```

Then launch the client in a different shell:

```shell
php client.php http://localhost:8080
```

For reference implementations in Go check the [original repository](https://github.com/twitchtv/twirp/tree/master/example).
