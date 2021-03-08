# Aplicação Financeira



## Executar projeto
 Projeto deve ser executado em ambiente docker

1º Variáveis de ambiente

	Faça uma cópia do arquivo .env.exemple com nome .env 

2º Execução dos containers

	docker-compose up --build -d

3º Instalação das dependências do projeto

	docker-compose exec app composer install

4º Rodando as migrações na base de dados

	docker-compose exec app php artisan migrate --seed
  
5° Rodando testes

	docker-compose exec app php artisan test

## Documentações
Documentação Swagger
- Ambiente online (AWS): http://18.191.39.15:8000/api/documentation
- Ambiente local: http://localhost:8000/api/documentation


![alt text](https://i.ibb.co/h8z0ymY/Screenshot.png)

## Diagrama Entidade Relacionamento (DER)
Diagrama do modelo da base de dados da aplicação

![alt text](https://i.ibb.co/YyzL5KW/tese.png)
