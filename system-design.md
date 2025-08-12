# System Design - Desafio técnico

# 1. Arquitetura Geral
Para atender aos requisitos de alta performance e tolerância a falhas, propõe-se uma arquitetura em microsserviços e orientada a eventos (Event-Driven).

Componentes:

API Gateway: Ponto de entrada único para todas as requisições. Responsável pela autenticação, roteamento e segurança.


Serviço de Ingestão de Transações: Recebe as requisições POST /transactions, valida os dados, salva a transação com um status inicial e publica um evento em uma fila de mensagens. Este serviço é otimizado para uma resposta rápida.

Mensageria: Um sistema de filas que garante a comunicação assíncrona e desacopla os serviços, atuando como um buffer para picos de tráfego.


Serviço de Análise de Risco: Consome os eventos da fila em segundo plano, aplica a lógica de risco e realiza validações externas, como consultar uma blacklist de CPF/CNPJ.

Serviço de Persistência: Lida com a interação direta com o banco de dados.


Serviço de Consulta: Gerencia os endpoints GET  para consultar e listar transações, utilizando um cache para otimizar o tempo de resposta.

Fluxo de Dados:

Cliente envia uma transação via POST para o API Gateway.

O Serviço de Ingestão valida os dados, cria a transação com status "analisando" e envia um evento para a Mensageria.

O Serviço de Análise de Risco consome o evento e realiza a análise.

O Serviço de Persistência atualiza o status final da transação no banco de dados.

O cliente consulta o status da transação via GET no Serviço de Consulta.

# 2. Escolha de Tecnologias
Linguagem de Programação: PHP com Laravel e Kotlin com Spring Boot.

Justificativa: PHP/Laravel oferece agilidade no desenvolvimento para a camada de ingestão. Kotlin/Spring Boot é ideal para o Serviço de Análise de Risco, que exige alta performance para processamentos complexos.

Banco de Dados: PostgreSQL.

Justificativa: Banco de dados relacional robusto para garantir a integridade dos dados financeiros. Suporta leitura massiva  e otimizações como réplicas para a camada de consulta.

Mensageria: RabbitMQ ou Amazon SQS.


Justificativa: Sistemas de filas duráveis que garantem que as mensagens não sejam perdidas, essenciais para um sistema "tolerante a falhas".

Observabilidade: ELK Stack e Prometheus com Grafana.


Justificativa: Ferramentas cruciais para "logs e rastreabilidade" e para monitorar métricas do sistema.


# 3. Estratégia de Escalabilidade e Tolerância a Falhas
Escalabilidade Horizontal: A arquitetura de microsserviços permite escalar cada serviço independentemente. Em um cenário de alto volume, podemos adicionar mais instâncias do Serviço de Análise de Risco para consumir a fila de forma paralela.

Tolerância a Falhas: A mensageria atua como um buffer. Se um serviço falhar, a mensagem pode ser reprocessada.


Rastreabilidade: Com a observabilidade, cada transação pode ser rastreada do início ao fim, atendendo ao requisito de "rastreabilidade".

# 4. Justificativa das Decisões Técnicas
As decisões foram tomadas para alinhar a arquitetura aos requisitos do desafio. A arquitetura de microsserviços e a mensageria garantem que o sistema seja "escalável" e "tolerante a falhas" , e que a análise ocorra em "até 2 segundos". As escolhas de tecnologia visam o equilíbrio entre agilidade de desenvolvimento e alta performance.

Uma breve descrição sobre o que esse projeto faz e para quem ele é

# 1. Arquitetura Geral
Para atender aos requisitos de alta performance e tolerância a falhas, propõe-se uma arquitetura em microsserviços e orientada a eventos (Event-Driven).

Componentes:

API Gateway: Ponto de entrada único para todas as requisições. Responsável pela autenticação, roteamento e segurança.


Serviço de Ingestão de Transações: Recebe as requisições POST /transactions, valida os dados, salva a transação com um status inicial e publica um evento em uma fila de mensagens. Este serviço é otimizado para uma resposta rápida.

Mensageria: Um sistema de filas que garante a comunicação assíncrona e desacopla os serviços, atuando como um buffer para picos de tráfego.


Serviço de Análise de Risco: Consome os eventos da fila em segundo plano, aplica a lógica de risco e realiza validações externas, como consultar uma blacklist de CPF/CNPJ.

Serviço de Persistência: Lida com a interação direta com o banco de dados.


Serviço de Consulta: Gerencia os endpoints GET  para consultar e listar transações, utilizando um cache para otimizar o tempo de resposta.

Fluxo de Dados:

Cliente envia uma transação via POST para o API Gateway.

O Serviço de Ingestão valida os dados, cria a transação com status "analisando" e envia um evento para a Mensageria.

O Serviço de Análise de Risco consome o evento e realiza a análise.

O Serviço de Persistência atualiza o status final da transação no banco de dados.

O cliente consulta o status da transação via GET no Serviço de Consulta.

# 2. Escolha de Tecnologias
Linguagem de Programação: PHP com Laravel e Kotlin com Spring Boot.

Justificativa: PHP/Laravel oferece agilidade no desenvolvimento para a camada de ingestão. Kotlin/Spring Boot é ideal para o Serviço de Análise de Risco, que exige alta performance para processamentos complexos.

Banco de Dados: PostgreSQL.

Justificativa: Banco de dados relacional robusto para garantir a integridade dos dados financeiros. Suporta leitura massiva  e otimizações como réplicas para a camada de consulta.

Mensageria: RabbitMQ ou Amazon SQS.


Justificativa: Sistemas de filas duráveis que garantem que as mensagens não sejam perdidas, essenciais para um sistema "tolerante a falhas".

Observabilidade: ELK Stack e Prometheus com Grafana.


Justificativa: Ferramentas cruciais para "logs e rastreabilidade" e para monitorar métricas do sistema.


# 3. Estratégia de Escalabilidade e Tolerância a Falhas
Escalabilidade Horizontal: A arquitetura de microsserviços permite escalar cada serviço independentemente. Em um cenário de alto volume, podemos adicionar mais instâncias do Serviço de Análise de Risco para consumir a fila de forma paralela.

Tolerância a Falhas: A mensageria atua como um buffer. Se um serviço falhar, a mensagem pode ser reprocessada.


Rastreabilidade: Com a observabilidade, cada transação pode ser rastreada do início ao fim, atendendo ao requisito de "rastreabilidade".

# 4. Justificativa das Decisões Técnicas
As decisões foram tomadas para alinhar a arquitetura aos requisitos do desafio. A arquitetura de microsserviços e a mensageria garantem que o sistema seja "escalável" e "tolerante a falhas" , e que a análise ocorra em "até 2 segundos". As escolhas de tecnologia visam o equilíbrio entre agilidade de desenvolvimento e alta performance.
