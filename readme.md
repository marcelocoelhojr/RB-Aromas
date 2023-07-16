# E-commerce de Produtos Aromáticos

O projeto tem como temática um e-commerce de produtos aromáticos, desenvolvido em PHP puro. O objetivo principal deste projeto é aplicar os conhecimentos de PHP puro. O sistema foi dividido em 4 camadas, sendo elas: controller, service, model e view. Além disso, foi implementado um sistema de rotas com PHP puro.

## Pré-requisitos

Antes de executar o sistema, certifique-se de ter os seguintes requisitos instalados em seu ambiente:

- PHP versão 7.0 ou superior
- Servidor web (como Apache, Nginx, etc.)
- MySQL (se aplicável)

## Instalação

Para rodar o sistema, siga os seguintes passos:

1. Clone o projeto em seu servidor web com a versão do PHP >= 7. (No caso do XAMPP, clone o projeto na pasta `htdocs`).
2. Crie o arquivo `.env` no projeto a partir do arquivo `.env-example`.
3. Configure as variáveis de ambiente no arquivo `.env` de acordo com as configurações do seu ambiente.
4. Com o MySQL, crie a base de dados com o nome "rbaromas" e importe o arquivo "rbaromas.sql".
5. Inicie o servidor na pasta "public" do projeto. Nesse caso, pode-se utilizar o Servidor web embutido do PHP. Por exemplo, `php -S localhost:8085`.

## Uso

Para utilizar o sistema, siga as instruções abaixo:

1. Acesse o sistema no seu navegador utilizando a URL: `http://localhost:PORTA`.
2. Explore as funcionalidades do e-commerce para visualizar e comprar produtos aromáticos.

Para fins de teste, existem os seguintes usuários predefinidos:

- Usuário Administrador:
  - Login: admin
  - Senha: 123456

- Usuário Normal:
  - CPF: 000.000.000-00
  - Senha: 123456



