[![SymfonyInsight](https://insight.symfony.com/projects/b198abbb-7d2f-4234-91ae-cfda006620c7/big.svg)](https://insight.symfony.com/projects/b198abbb-7d2f-4234-91ae-cfda006620c7)

Bilemo
========================

Requirements
------------

* PHP 8.0 or higher;
* PDO-SQLite PHP extension enabled;
* and the [usual Symfony application requirements][1].

Installation
------------
You need to install :
- [Docker Engine][2]
- [Docker Compose][3]

Install the project :
```bash
$ make install
```

Generate SSH Keys :
```bash
$ make generate-ssh-keys
```

Generate Token :
```bash
$ make generate-token
```

You should have a response like this from the CLI :
```json
{
   "token" : "yourtoken"
}
```

Now, use this token to authenticate yourself in the API.

Usage
------------
Boot containers :
```bash
$ make dc-up
```

To interact with the PHP container :
```bash
$ make dc-exec
```

Create database, run migrations and load fixtures :
```bash
$ make db-reset
```

Tests
------------

Execute this command to run tests:
```bash
$ make tests
```

Reset tests
------------
```bash
$ make tests-reset
```

Diagrams
------------
[Link](diagrams.md)

[1]: https://symfony.com/doc/current/reference/requirements.html
[2]: https://docs.docker.com/installation/
[3]: https://docs.docker.com/compose/
