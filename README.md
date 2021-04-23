# reev - ZSSN (Zombie Survival Social Network)

## Apresentação do problema: https://bitbucket.org/outbound-marketing/backend-challenge/src/master/

### Solução:
* API REST
* Framework Lumen (8.2.3)
* * Após clonar o projeto executar:
* * * composer install
* * * php artisan cache:clear


### Documentação:
Routes:
```
- POST: survivors/{id}
- POST: survivors/{id}/inventory
- GET: survivors/{id}
- PATCH: survivors/{id}
- GET: survivors/reports/{id}
- POST: survivors/{id}/infected
- POST: survivors/{id}/trade
```

Coleção de chamadas a API no Postman:

[Teste Reev.postman_collection.json.zip](https://github.com/guiimarques18/reev/files/6365452/Teste.Reev.postman_collection.json.zip)


Esquema do Banco de Dados - Postgres:

[script_bd.sql.zip](https://github.com/guiimarques18/reev/files/6367099/script_bd.sql.zip)

### Melhorias
* Utilizar o https://editor.swagger.io/ para melhorar a documentação da API, com os campos, models, etc.
* Implementação dos Caso de Testes Automatizados
* No próprio código há expressões *TODO* que podem ser melhorias de código a serem implementadas
