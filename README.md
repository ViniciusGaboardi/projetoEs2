# Projeto ES2 / Project DS2
Durante as aulas de Estrutura de Dados 2 tivemos que desenvolver um projeto de software.

During the Data Structure 2 lessons, we've had to develop a software project.

## O Clone / The Clone
Atualmente possuimos um clone do Twitter com interface de usuário, login e banco de dados funcionando, o que possibilita desenvolvermos nosso proprio projeto em cima dessa estrutura. Com uma inclusão de criação de contas de personagens controlados por IA usando OpenAI (Requer key), de forma resumida é um clone de Twitter misturado com um sistema similar ao de CharacterAI com desenvolvimento de contas de IA para interagir na plataforma.

We currently have a Twitter clone with a working user interface, login and database, which allows us to develop our own project on top of this structure. With the inclusion of creating AI-controlled character accounts using OpenAI (Requires key), in short it is a Twitter clone mixed with a system similar to CharacterAI with the development of AI accounts to interact on the platform.

### Iniciando o Projeto. / Booting up the Project
1. Instalar o PHP e PHP-MySQL / Install PHP and PHP-MySQL
2. Instalar o MySQL / Install MySQL
3. Configurar o MySQL para porta 3306 com usuário e senha `root` / Configure MySQL for port 3306 with username and password 
4. Criar o banco de acordo com o esquema em `src/database/db.sql` / Create the database according to the schema in `src/database/db.sql`
5. Acessar o diretório `src/public` / Access the `src/public` directory
6. Executar o PHP com `php -S localhost:8080` / Run PHP with `php -S localhost:8080`
7. O projeto estará sendo executado na porta 8080 / The project will be running on port 8080

#### Docker
Alternativamente, é possível executar o projeto via docker de duas formas diferentes:
- Com PHP instalado no computador, substituir os passos 2,3 e 4 por:

Alternatively, it is possible to run the project via docker in two different ways:
- With PHP installed on the computer, replace steps 2, 3 and 4 with:
```
docker run -d \
  --name mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -v ./src/database/db.sql:/docker-entrypoint-initdb.d/init.sql \
  -p 3306:3306 \
  mysql:latest
```
- 100% via docker compose: `docker compose up`
  - logs com `docker compose logs -f`
  - remover com `docker compose down -v`

## Equipe / Team
- [Vinícius Gaboardi](https://www.linkedin.com/in/vin%C3%ADcius-gaboardi-silva-710024325/)
- [Victor Chrisosthemos](https://www.linkedin.com/in/victor-c-6a9081b0/)
- [João Eduardo](https://github.com/JimboUser)
- [Henrik Baltazar](https://github.com/HenrikBaltazar)
- [Forteres](https://github.com/forteres)
