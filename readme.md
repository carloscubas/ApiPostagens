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

##  Incluir Transações

** Method POST **

http://localhost:8000/api/transaction?token=<token login>

body

{
	"valor": "200.50",
	"tipo": "C",
	"data": "2017-10-04 08:44:00"
}

##  Incluir Comentários

** Method POST **

http://localhost:8000/api/comments?token=<token login>
{
    "titulo": "Kafta, não conheço",
    "conteudo": "mas parece ser legal, quem jea usou isso ?",
    "postagem_id ": 2,
    "valor": 300
}

OBS, caso não esteja comprando creditos, deixar o valor 0

// Lista Comentarios

** Method GET **
http://localhost:8000/api/comments?limit=5&offset=2&idpost=0

OBS: as variaveis não são obrigatorias

##  Lista notificação dos posts do usuários
** Method GET **
http://localhost:8000/api/notification?token=<token>

##  Mostra uma notificação
** Method GET **
http://localhost:8000/api/notification/17?token=<token>