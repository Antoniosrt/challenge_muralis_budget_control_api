
# API de Orçamento - Despesas


--- 

# Instruções para Rodar o Projeto


## Pré-requisitos

Antes de rodar o projeto, certifique-se de ter as seguintes ferramentas instaladas:

- [PHP 8.3](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)

---

## Passos para Rodar a Aplicação

### 1. **Clonar o Repositório**

Primeiro, clone o repositório para a sua máquina local. Execute o seguinte comando:

```bash
git clone git@github.com:Antoniosrt/challenge_muralis_budget_control_api.git
```

Substitua `<URL_do_repositório>` pela URL do repositório desejado.

### 2. **Instalar Dependências com Composer**

Após clonar o repositório, navegue até o diretório do projeto:

```bash
cd challenge_muralis_budget_control_api
```

Em seguida, instale as dependências do projeto utilizando o Composer:

```bash
composer install
```

Isso irá baixar todas as dependências necessárias para rodar a aplicação.

### 3. **Configurar o Arquivo `.env`**

Crie um arquivo `.env` na raiz do projeto. Esse arquivo é onde você configurará as variáveis de ambiente, como a conexão com o banco de dados.


Dentro do arquivo `.env`, adicione as informações de configuração do banco de dados:

```env
DATABASE_DSN="mysql:host=127.0.0.1;port=3306;dbname=muralis_challenge"
DATABASE_USER=usuario
DATABASE_PASSWORD=senha
```

Substitua os valores de `usuario` e `senha` pelos dados de acesso ao seu banco de dados MySQL.

Execute a query SQL que está incluido neste projeto para criar o banco de dados e as tabelas necessárias.

### 4. **Rodar o Servidor Local**

Agora que as dependências estão instaladas e o arquivo `.env` está configurado, você pode iniciar o servidor PHP embutido para rodar a aplicação localmente.

Execute o seguinte comando:

```bash
php -S localhost:8000 -t public
```

Isso irá iniciar o servidor local, e você poderá acessar a aplicação através do endereço [http://localhost:8000](http://localhost:8000).

---

## Observações

- **Banco de Dados:** Certifique-se de que o banco de dados MySQL esteja rodando em sua máquina local. Caso o banco de dados não exista, será necessário criá-lo ou rodar as migrações para criá-lo automaticamente.

- **Erro de Permissão:** Se você encontrar problemas de permissão, tente rodar os comandos com `sudo` (no caso de sistemas Unix) ou garanta que você tem as permissões corretas para escrever no diretório.

- **PHP Version:** Verifique se está utilizando a versão correta do PHP. Para verificar a versão do PHP instalada, use o comando:



# Informações sobre a API


### Base URL

```
/api
```

## Endpoints

### 1. **GET /despesas**

Recupera todas as despesas.

- **Método:** `GET`
- **Parâmetros de consulta:**
    - Nenhum parâmetro obrigatório.
    - Pode aceitar parâmetros de consulta via URL (query parameters), dependendo de como a função `get_all_budgets` foi implementada na classe `BudgetService`.

- **Resposta:**
    - Retorna uma lista com todas as despesas.
    - **Status 200**: Em caso de sucesso.
    - **Status 400**: Se ocorrer algum erro no processo.

#### Exemplo de Resposta

```json
  {
    "success": true,
    "data": {
      "items": [
        {
          "id": 4,
          "amount": "123.00",
          "purchase_date": "2025-03-19 00:00:00",
          "description": "Teste 123",
          "payment_type_id": 1,
          "category_id": 30,
          "address_id": 2
        }
      ],
      "total_pages": 5,
      "current_page": 5
    }
  }
```

---

### 2. **GET /despesas/{id}**

Recupera uma despesa específica pelo ID.

- **Método:** `GET`
- **Parâmetros de URL:**
    - `id`: O ID da despesa a ser recuperada (exemplo: `1`).

- **Resposta:**
    - **Status 200**: Retorna a despesa com o ID fornecido.
    - **Status 400**: Se ocorrer algum erro.

#### Exemplo de Resposta

```json

"success": true,
"data": {
  "id": 1,
  "state": "RS",
  "city": "Santa Maria",
  "neighborhood": "Centro",
  "street": "Rua dos Andradas",
  "number": "123",
  "complement": "Apartamento 202"
}
```

---

### 3. **PUT /despesas/{id}**

Atualiza uma despesa específica pelo ID.

- **Método:** `PUT`
- **Parâmetros de URL:**
    - `id`: O ID da despesa a ser atualizada (exemplo: `1`).

- **Parâmetros no corpo da requisição (JSON):**
    - **name** (string): Nome da despesa.
    - **amount** (float): Valor do orçamento.

#### Exemplo de Corpo da Requisição

```json

{
  "amount": 1220.00,
  "purchase_date": "2024-12-18",
  "description": "Ajuste de fincanceiro #campotrocado",
  "payment_type_id": 3,
  "address_id": 2,
  "category_id": 27
}
}
```

- **Resposta:**
    - **Status 200**: Retorna a despesa atualizada.
    - **Status 400**: Se ocorrer algum erro.

#### Exemplo de Resposta

```json
{
  "success": true,
  "data": {
    "id": 1,
    "amount": "1220.00",
    "purchase_date": "2024-12-18 00:00:00",
    "description": "Ajuste de fincanceiro #campotrocado",
    "payment_type_id": 3,
    "category_id": 27,
    "address_id": 2
  }
}
```

---

### 4. **POST /despesas**

Cria uma nova despesa.

- **Método:** `POST`
- **Parâmetros no corpo da requisição (JSON):**
    - **name** (string): Nome da despesa.
    - **amount** (float): Valor do orçamento.

#### Exemplo de Corpo da Requisição

```json
{
  "amount": 120.00,
  "purchase_date": "2024-12-10",
  "description": "Ajuste de contas",
  "address_id": 1,
  "payment_type_id":4,
  "category_id": 25
}
```

- **Resposta:**
    - **Status 200**: Retorna a nova despesa criada.
    - **Status 400**: Se ocorrer algum erro.

#### Exemplo de Resposta

```json
{
  "success": true,
  "data": {
    "id": 18,
    "amount": 120,
    "description": "Ajuste de contas",
    "addressId": 1,
    "categoryId": 25,
    "paymentTypeId": 4,
    "purchaseDate": "2024-12-10"
  }
}
```

---

### 5. **DELETE /despesas/{id}**

Deleta uma despesa específica pelo ID.

- **Método:** `DELETE`
- **Parâmetros de URL:**
    - `id`: O ID da despesa a ser deletada (exemplo: `1`).

- **Resposta:**
    - **Status 200**: Retorna uma confirmação de que a despesa foi deletada.
    - **Status 400**: Se ocorrer algum erro.

#### Exemplo de Resposta

```json
{
    "success": true,
    "message": "Despesa deletada com sucesso."
}
```

---

## Erros Comuns

- **Status 400:** Quando ocorre um erro durante o processamento da requisição, como dados inválidos ou falta de parâmetros obrigatórios.
    - Exemplo de resposta:

  ```json
  {
      "error": "Mensagem de erro",
      "success": false
  }
  ```

---

## Como Testar

1. **GET /api/despesas:**
    - Envie uma requisição GET para obter todas as despesas.

2. **GET /api/despesas/{id}:**
    - Envie uma requisição GET com o ID de uma despesa específica para visualizá-la.

3. **POST /api/despesas:**
    - Envie uma requisição POST com o corpo para criar uma nova despesa.

4. **PUT /api/despesas/{id}:**
    - Envie uma requisição PUT com o corpo para atualizar uma despesa existente.

5. **DELETE /api/despesas/{id}:**
    - Envie uma requisição DELETE com o ID de uma despesa para removê-la.

--- 
### Para o teste unitario foi utilizado o PHPUnit

```bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/BudgetRepositoryTest.php

```

---

