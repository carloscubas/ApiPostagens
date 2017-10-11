# Rotas do Projeto

## Criando um usuário

**Method POST**
http://localhost:8000/api/register

body
{
    "name" : "carlos julio silva",
    "email": "carlos.cubas.julio.silva@gmail.com",
    "password": "12345678"
}


## Mostra detalhe do usuário

** Method POST **
http://localhost:8000/api/get_user_details

body
{
    "token": "<token>"
}

##  Login

**Method POST**

http://localhost:8000/api/login
body

{
    "email": "jose.silva@mailinator.com",
    "password": "12345678"
}

**CONSULTA**

http://localhost:8000/api/produtos?token=<token>
