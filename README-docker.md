## Tecnologias
- PHP 8.3
- Apache
- MariaDB 11.4
- phpMyAdmin
- Docker e Docker Compose

## Configuração

**1. Clone o repositório:**

```bash
git clone https://github.com/bluecatcom/flamecharm-sharko
cd flamecharm-sharko
```

**2. Configure as variáveis de ambiente:**

Crie um arquivo `.env` na raiz do projeto, utilizando o `.env.example` como referência.

```env
DB_PASSWORD=sua_senha
DB_ROOT_PASSWORD=sua_senha_root
```

**3. Inicie os containers:**

```bash
docker compose up -d --build
```

Na primeira vez, o Docker vai criar os containers e iniciar o banco de dados utilizando o arquivo banco.sql.

## Acessando a aplicação

Após iniciar os containers, acesse:

| Serviço | Endereço |
|---|---|
| Aplicação PHP | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |

Para acessar o phpMyAdmin, utilize:

- **Usuário:** `app_user`
- **Senha:** valor configurado em `DB_PASSWORD`
