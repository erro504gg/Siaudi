# INSTALAÇÃO SIAUDI

### Instalação do Ubuntu

Instale o UBUNTU (versão homologada 14.04.2 LTS) e atualize-o.

### Configuração do Ambiente

##### 1. Instalação do Apache no Ubuntu

```sh
  $ sudo apt-get install apache2
```

##### 2. Ativação do Módulo a2enmod

```sh
  $ sudo a2enmod rewrite
```

##### 3. Configuração do arquivo apache2.conf

Edite o arquivo:

```sh
  $ sudo gedit /etc/apache2/apache2.conf
```

Procure pelas linhas com AllowOverride None e troque por AllowOverride All.

##### 4. Configuração do diretório default de carga do APACHE

Edite o arquivo:

```sh
  $ sudo gedit /etc/apache2/sites-available/000-default.conf
```

Procure pela linha com DocumentRoot /var/www/html  e troque por DocumentRoot /var/www/

##### 5. Instalação do PHP5 e pacotes auxiliares 

Rode o comando:

```sh
  $ sudo apt-get install php5 php5-dev php5-cli php5-gd php5-ldap php5-mcrypt php5-memcache php5-pgsql php-pear
```

##### 6. Configuração do PHP

Edite o arquivo:

```sh
  $ sudo gedit /etc/php5/apache2/php.ini
```

Procure pelas linhas abaixo e substitua pelos valores apresentados:

```apacheconf
  session.auto_start = 1
  error_reporting = E_ALL & ~E_DEPRECATED  & ~E_STRICT & ~E_NOTICE
  memory_limit = 1024M
  post_max_size = 128M
  display_errors = On
  short_open_tag = On
```

##### 7. Recarga do Servidor APACHE:

Rode o comando:

```sh
  $ sudo /etc/init.d/apache2 restart
```

### Instalação do Banco de Dados

##### 1. Instalação do PostgreSQL e PGAdmin

```sh
  $ sudo apt-get install postgresql
  $ sudo apt-get install pgadmin3
```

##### 2. Instalação do Projeto SIAUDI no Ubuntu

Copie o arquivo "siaudi2.zip" para o diretório "/var/www"

```sh
  $ sudo unzip /var/www/siaudi2.zip -d /var/www
  $ cd /var/www/siaudi2
```

Certifique-se de que as pastas assets e protected/runtime podem ser escritas pelo servidor web. Escolha um dos comandos abaixo:

```sh
  $ sudo chmod 777 assets & chmod 777 protected/runtime
  $ sudo chown www-data:www-data assets & chown www-data:www-data protected/runtime
```

PS.: o arquivo siaudi2.zip não é mais necessário e pode ser apagado

##### 3. Criação da Base de Dados

Edite o arquivo:

```sh
  $ sudo gedit /etc/profile
```

Adicione no início do arquivo a linha:

```sh
  export LANG=pt_BR.iso88591
```

```sh
  $ sudo locale-gen pt_BR
  $ source /etc/profile
  $ sudo /etc/init.d/postgresql restart
  $ sudo -u postgres psql -f /var/www/siaudi2/script_Bd/siaudispb.sql -o /tmp/resultado.txt
```

### Configuração e Acesso ao SIAUDI

##### 1. Configuração da conexão com o BD 

Edite o arquivo:

```sh
  $ sudo gedit /var/www/siaudi2/protected/config/main.php
```

Na linha 144 altere a string de conexão de acordo com o endereço do servidor de banco de dados (por padrão já está configurado para acesso via localhost):

```php
'db' => array(
    'class' => 'application.components.MyDbConnection',
    'connectionString' => 'pgsql:host=localhost;port=5432;dbname=bd_siaudi',
    'emulatePrepare' => false,
    'username' => 'usrsiaudi',
    'password' => '!@#-usr-siaudi',
    'charset' => 'latin1',
    'tablePrefix' => 'tb_',
    'enableProfiling' => YII_DEBUG,
    'enableParamLogging' => YII_DEBUG,   
),
```

Certifique-se de que a porta de conexão com o banco na configuração acima seja a porta pela qual o banco escuta conexões.

O PostgreSQL geralmente tenta ser instalado na porta 5432 por padrão. Caso ele a encontre ocupada, utilizará a próxima porta que não estiver ocupada (5433, ou 5434 e assim por diante).

Para checar qual porta ele está utilizando, execute o comando abaixo:

```sh
  $ sudo gedit /etc/postgresql/9.3/main/postgresql.conf
```

Procure por “port”

##### 2. Configuração do sistema

Edite o arquivo:

```sh
  $ sudo gedit /var/www/siaudi2/protected/config/main.php
```

Popule o campo 'emailGrupoAuditoria' da variável 'params' da linha 240 do arquivo usando um array PHP com os e-mails da auditoria que deverão receber todas as mensagens gerenciais do sistema (ex.: manifestações de auditados).

```php
'params' => array(
    // this is used in contact page
    'adminEmail' => 'email.de.contato@dominio.com.br',
    'emailGrupoAuditoria' => array(
        'email_interessado_um@dominio.gov.br',
        'email_interessado_dois@dominio.gov.br',
    ),
    //'dominioEmail' => '@dominio.com.br',
    'id_aplicacao' => 'SIAUDI2',
    … (outras definições omitidas pela legibilidade)
),
```

##### 3. Acesso ao sistema

Rode o browser no endereço: "http://localhost/siaudi2"

Usuário: siaudi.gerente

Senha: 123456