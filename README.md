# zenn-php-symfony

- PHP: 8.2
- Symfony: 6.4.0

# usage

```shell
$ cp ./backend/src/.env ./backend/src/.env.local
```

```shell
$ docker compose up --build
```

access `http://localhost:8080/api`

```shell
# in php-docker
(php) $ symfony console doctrine:migrations:migrate
```

# related articles

1. [Symfony+API Platformを動かしてみた](https://zenn.dev/gsy0911/articles/ab193f6eba39dc)
2. [DockerでMySQLのMaster/Slave構成](https://zenn.dev/gsy0911/articles/2287b8aa75d706)
3. [DoctrineでMySQLのMaster/Slaveを構築する](https://zenn.dev/gsy0911/articles/66d185f9bf80f9)
4. [API Platformで更新処理をしてみる](https://zenn.dev/gsy0911/articles/8c1eaef195c857)
