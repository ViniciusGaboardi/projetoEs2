# Projeto ES2
Durante a disciplina de ES2 teremos que desenvolver um projeto de software completo.

## O clone
Atualmente possuimos um clone do twitter com interface de usuário, login e banco de dados funcionando, o que possibilita desenvolvermos nosso proprio projeto em cima dessa estrutura.

### Progresso
- [ ] Definir qual será o projeto (não podemos apresentar simplesmente uma copia do twitter)
- [ ] Modelar o projeto para definir suas interfaces, processos, funcionalidades, etc...
- [ ] Fechar o escopo de desenvolvimento
- [ ] Finalizar o desenvolvimento
- [ ] Realizar os testes automatizados

### Iniciando o projeto.
1. Instalar o PHP e PHP-MySQL
2. Instalar o MySQL
3. Configurar o MySQL para porta 3306 com usuário e senha `root`
4. Criar o banco de acordo com o esquema em `src/database/db.sql`
5. Acessar o diretório `src/public`
6. Executar o PHP com `php -S localhost:8080`
7. O projeto estará sendo executado na porta 8080

#### Docker
Alternativamente, é possível executar o projeto via docker de duas formas diferentes:
- Com PHP instalado no computador:
substituir os passos 2,3 e 4 por:
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
