# API de Despesas Pessoais

## Visão Geral

Esta API RESTful permite o gerenciamento de despesas pessoais, com funcionalidades de autenticação de usuários, cadastro, edição, exclusão e listagem de despesas.

## Autenticação

A API utiliza autenticação baseada em tokens JWT (JSON Web Token). Para acessar os endpoints protegidos, é necessário incluir o token no cabeçalho da requisição:


### Registro de Usuário

**Endpoint:** `POST /auth/signup`

**Descrição:** Cria uma nova conta de usuário.

**Request Body:**
```json
{
    "username": "usuario_exemplo",
    "password": "senha123"
}
Response:
{
    "status": "success",
    "message": "User created successfully."
}

Login
Endpoint: POST /auth/login

Descrição: Autentica um usuário e retorna um token JWT.

Request Body:
{
    "username": "usuario_exemplo",
    "password": "senha123"
}

Response:
{
    "status": "success",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}

Despesas
Listar Despesas
Endpoint: GET /despesas

Descrição: Lista todas as despesas do usuário autenticado.

Parâmetros de Query:

categoria: Filtra por categoria (alimentação, transporte, lazer) - opcional
data_inicio: Data de início no formato YYYY-MM-DD - opcional
data_fim: Data de fim no formato YYYY-MM-DD - opcional

Response:

{
    "items": [
        {
            "id": 1,
            "descricao": "Compra no supermercado",
            "valor": "150.00",
            "data": "2023-05-15",
            "categoria": "alimentação",
            "user_id": 1,
            "created_at": "2023-05-15 10:30:00",
            "updated_at": "2023-05-15 10:30:00"
        },
        // ...
    ],
    "_links": {
        "self": {
            "href": "http://example.com/despesas?page=1"
        }
    },
    "_meta": {
        "totalCount": 10,
        "pageCount": 1,
        "currentPage": 1,
        "perPage": 20
    }
}

Visualizar Despesa
Endpoint: GET /despesas/view/{id}

Descrição: Retorna os detalhes de uma despesa específica.

Response:
{
    "id": 1,
    "descricao": "Compra no supermercado",
    "valor": "150.00",
    "data": "2023-05-15",
    "categoria": "alimentação",
    "user_id": 1,
    "created_at": "2023-05-15 10:30:00",
    "updated_at": "2023-05-15 10:30:00"
}

Criar Despesa
Endpoint: POST /despesas/create

Descrição: Cria uma nova despesa.

Request Body:
{
    "descricao": "Compra no supermercado",
    "valor": "150.00",
    "data": "2023-05-15",
    "categoria": "alimentação"
}
Response:
{
    "id": 1,
    "descricao": "Compra no supermercado",
    "valor": "150.00",
    "data": "2023-05-15",
    "categoria": "alimentação",
    "user_id": 1,
    "created_at": "2023-05-15 10:30:00",
    "updated_at": "2023-05-15 10:30:00"
}

Atualizar Despesa
Endpoint: PUT /despesas/update/{id}

Descrição: Atualiza uma despesa existente.

Request Body:
{
    "descricao": "Compra no supermercado - atualizado",
    "valor": "160.00",
    "data": "2023-05-15",
    "categoria": "alimentação"
}
Response:
{
    "id": 1,
    "descricao": "Compra no supermercado - atualizado",
    "valor": "160.00",
    "data": "2023-05-15",
    "categoria": "alimentação",
    "user_id": 1,
    "created_at": "2023-05-15 10:30:00",
    "updated_at": "2023-05-15 11:45:00"
}

Excluir Despesa
Endpoint: DELETE /despesas/delete/{id}

Descrição: Exclui uma despesa.

Response: Status 204 No Content

Códigos de Status
200 OK: Requisição bem-sucedida
201 Created: Recurso criado com sucesso
204 No Content: Recurso excluído com sucesso
400 Bad Request: Requisição inválida
401 Unauthorized: Não autorizado (token inválido ou ausente)
403 Forbidden: Acesso negado
404 Not Found: Recurso não encontrado
422 Unprocessable Entity: Erro de validação
500 Internal Server Error: Erro interno do servidor

Exemplos de Uso

Criar um usuário

curl -X POST http://localhost:8080/auth/signup \
  -H "Content-Type: application/json" \
  -d '{"username": "usuario_exemplo", "password": "senha123"}'
