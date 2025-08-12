# Desafio Técnico - API de Análise de Transações

# Descrição do Projeto:
Uma API RESTful desenvolvida para um sistema de análise de transações financeiras com o objetivo de sinalizar possíveis 
fraudes com base em regras de risco. A aplicação foi construída com PHP/Laravel, seguindo uma arquitetura de 
camadas (Controller, Service, Repository) e implementando diferenciais como mensageria, autenticação via JWT, cache e CI/CD.

# Tecnologias Utilizadas:

Linguagem: PHP 8.3

Framework: Laravel 11

Banco de Dados: MySQL ou PostgreSQL

Mensageria: Filas do Laravel

Autenticação: JWT (JSON Web Tokens)

Testes: PestPHP e PHPUnit

Automação: GitHub Actions para CI/CD

# Requisitos do Desafio (Mínimo)

API RESTful com endpoints para

POST /transactions, GET /transactions/{id} e GET /transactions.

Modelo de transação com campos

id, cpf_cnpj, valor, data_hora, localizacao, status, motivo_risco.

Lógica de risco simples: Transações acima de R$1000 entre 22h e 6h devem ser marcadas como "alto risco".

Banco de dados relacional (MySQL ou PostgreSQL).

Testes unitários para a lógica de risco.


README com instruções para rodar o projeto.

# Diferenciais (Plus)

Aplicação com estrutura limpa (camada de serviços e repositórios).

Uso de boas práticas de arquitetura (SOLID, DDD).

Cobertura de testes acima de 60%.

Criação de um sistema de mensageria (simulado com filas) para processar transações em background.

Implementação de autenticação básica via token (JWT).

Scripts de CI/CD simulados com GitHub Actions.

Implementação opcional de cache para a listagem de transações.

# Como Rodar o Projeto (com Docker)
Este projeto utiliza Docker para criar um ambiente de desenvolvimento isolado e fácil de reproduzir.

    ° Instalação do Docker: Certifique-se de ter o Docker e o Docker Compose instalados na sua máquina.

Configuração do Ambiente:

Crie o arquivo docker-compose.yml e a pasta docker com as configurações do Nginx e PHP.

No arquivo .env, configure o banco de dados para usar o serviço db.

Execução dos Contêineres:

No terminal, na pasta raiz do projeto, execute:

Bash

docker-compose up -d --build
Instalação das Dependências:

Acesse o terminal do contêiner da aplicação e instale as dependências:

Bash

docker-compose exec app composer install
Configuração do Projeto:

Gere a chave da aplicação:

Bash

docker-compose exec app php artisan key:generate
Execute as migrações:

Bash

docker-compose exec app php artisan migrate
Crie um usuário de teste:

Bash

docker-compose exec app php artisan db:seed --class=UserSeeder
Acesso à Aplicação:

A aplicação estará disponível em http://localhost.

Instruções para a API

POST /api/auth/token: Endpoint para fazer login e obter um token JWT.

GET|POST /api/transactions: Endpoints para listar e cadastrar transações. Protegidos por autenticação JWT.

# Decisões Técnicas Principais:

Arquitetura de Camadas: O projeto segue o padrão Controller -> Service -> Repository para garantir a separação de responsabilidades e facilitar a manutenção e os testes.


Mensageria com Filas: Para o requisito de processamento em segundo plano, a lógica de risco foi movida para um Job em fila, garantindo 
que o tempo de resposta da API seja rápido e o sistema seja mais escalável.


Autenticação JWT: O JWT foi escolhido para a autenticação da API por ser um padrão flexível e amplamente utilizado, alinhado com as boas práticas de segurança.


Cache: A listagem de transações utiliza cache para otimizar o desempenho em consultas repetidas, reduzindo a carga no banco de dados.

# Desafios e Evoluções Futuras:

A notificação do status de uma transação processada em segundo plano foi implementada via Polling. 
Uma evolução futura seria usar Server-Sent Events (SSE) para uma notificação em tempo real mais eficiente.
