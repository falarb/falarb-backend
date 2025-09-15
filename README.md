## RODANDO O BACKEND
Passo 1 - Baixe o projeto falarb-backend na sua máquina

Passo 2 - Acesse a pasta "falarb-backend"

Passo 3 - No terminal, execute os seguintes comandos em sequência:
```bash
  npm install
```
```bash
  composer install
```
```bash
  cp .env.example .env
```

Passo 4 - Gere a chave laravel digitando o seguinte comando no terminal:
```bash
    php artisan key:generate
```
	
Passo 5 - Crie o banco de dados rodando o script presente no documento do projeto (pág. 30)

Passo 6 - Dentro do arquivo .env, altere as informações de DB_USERNAME e DB_PASSWORD para o usuário e senha (respectivamente) do seu banco de dados

Passo 7 - Rode as migrations e os seeders com os comandos em sequência:
```bash
  php artisan migrate:fresh
```
```bash
  php artisan db:seed
```
	
Passo 8 - Agora rode o servidor digitando o comando no terminal:
```bash
  php artisan serve
```
	
Nesse momento o back-end do projeto já deverá estar rodando na URL http://127.0.0.1:8000