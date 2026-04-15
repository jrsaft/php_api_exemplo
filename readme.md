Como fazer o deploy:

1.**Clone este repositório:**
2. Certifique-se de que o Docker Desktop esteja rodando e execute: docker-compose up -d --build
3. Verifique se os containers estão ativos: docker ps 

A API está configurada para responder na porta 8080.

1. Listar Usuários (GET)
URL: http://localhost:8080/restfull_api.php?path=users

2. Cadastrar Usuário (POST)
URL: http://localhost:8080/restfull_api.php?path=users