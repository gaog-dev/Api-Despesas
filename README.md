# API de Despesas Pessoais

## Descrição

API RESTful desenvolvida em PHP com o framework Yii2 para gerenciamento de despesas pessoais. A aplicação permite que usuários se registrem, autentiquem-se e gerenciem suas despesas através de uma interface web e endpoints de API.

## Funcionalidades

- Autenticação de usuários com JWT
- Cadastro de novos usuários
- Gerenciamento de despesas (CRUD)
- Filtros por categoria e período
- Interface web para interação com a API
- Documentação completa da API

## Tecnologias Utilizadas

- PHP 8.2
- Yii2 Framework
- MySQL 5.7
- Docker
- JWT (JSON Web Tokens)
- Bootstrap

## Pré-requisitos

- Docker
- Docker Compose

## Instalação

1. Clone o repositório:

```bash
git clone https://github.com/gaog-dev/api-despesas-pessoais.git
cd api-despesas-pessoais
```

## Estrutura do Projeto

api-despesas-pessoais/
├── commands/           # Comandos de console
├── config/             # Arquivos de configuração
├── controllers/        # Controladores da aplicação
├── docker/             # Arquivos de configuração do Docker
├── migrations/         # Migrações do banco de dados
├── models/             # Modelos da aplicação
├── services/           # Camada de serviço
├── views/              # Views da aplicação
├── web/                # Diretório raiz da web
├── .gitignore
├── API.md              # Documentação da API
├── docker-compose.yml  # Configuração do Docker Compose
├── Dockerfile          # Configuração do Docker
├── README.md           # Este arquivo
└── ...                 # Outros arquivos

Desenvolvimento

Executar testes
bash
docker exec api-despesas-app php vendor/bin/codecept run

Criar um usuário de teste
bash
docker exec api-despesas-app php yii test-user/create <username> <password>

Acessar o banco de dados
bash
docker exec -it api-despesas-db mysql -uroot -prootpass api_despesas

Suba a Aplicação no Docker para Testes
docker-compose up -d --build

Verificar logs
bash
# Logs da aplicação
docker-compose logs -f app

# Logs do banco de dados
docker-compose logs -f db

Arquitetura

# A aplicação segue a arquitetura MVC (Model-View-Controller) do Yii2, com a seguinte estrutura:

° Models: Representam as estruturas de dados e as regras de negócio.
° Views: Responsáveis pela apresentação dos dados ao usuário.
° Controllers: Gerenciam a interação entre os models e as views.
° Services: Camada adicional para separação das regras de negócio.

Funcionalidade

# A API atende a todos os requisitos funcionais especificados:

° Registro e login de usuários
° Autenticação com token JWT
° Cadastro, edição, exclusão e listagem de despesas
° Filtros por categoria e período
° Ordenação por data

Código

° Qualidade, clareza e aderência às boas práticas de programação
° Segue os princípios SOLID
° Validações nos modelos para garantir integridade dos dados
° Separação de regras de negócio em camadas de serviços

Arquitetura

° Uso correto do padrão MVC e separação das responsabilidades
° Estrutura clara e organizada do projeto
° Implementação de autenticação e autorização
° Isolamento de dados por usuário

Documentação

° Documentação clara e completa da API e do projeto
° Especificação em markdown para descrever os endpoints, parâmetros e respostas
° README completo com instruções de instalação e uso

Extras

° Implementação de Docker para facilitar a configuração e implantação
° Interface web para interação com a API
° Comandos de console para criação de usuários de teste

Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo LICENSE para detalhes.

Autor
Guilherme Oliveira