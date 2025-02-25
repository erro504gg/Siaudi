**Bem-vindo ao Siaudi-Sistema-de-Auditoria!**

Foi desenvolvido pela https://softwarepublico.gov.br/social/siaudi/ apenas fiz uma alterações para rodar nas aplicações de hoje em dia, pois a última data de atualização deles foi em 2016.

Instalação do Ubuntu
Instale o Ubuntu (recomenda-se a versão 22.04 LTS, foi a que eu usei). Atualize o sistema rodando o comando:

sudo apt update && sudo apt upgrade -y

Configuração do Ambiente

**1. Instalação do Apache no Ubuntu**
Para instalar o Apache, execute:

```sudo apt instalar apache2 -y```

**2. Ativação do Módulo rewrite do Apache**
Ative o módulo necessário para URLs amigáveis com o comando:

```sudo a2enmod```

**3. Configuração do arquivo apache2.conf**
Edite o arquivo de configuração do Apache:

```sudo nano /etc/apache2/apache2.conf```
*Encontre a linha 
AllowOverride None

*Mude para 
AllowOverride All

**4. Configuração do diretório padrão de carga do Apache**
Edite o arquivo de configuração do site padrão:

```sudo nano /etc/apache2/sites-available/000-default.conf```

Altere a linha: DocumentRoot /var/www/html
Para: DocumentRoot /var/www/

**5. Instalação do PHP e Pacotes Necessários**
Instale o PHP 8.1 (ou versão mais recente):

```sudo apt install php libapache2-mod-php php-pgsql php-gd php-mbstring php-curl php-xml php-zip php-cli -y```

**6. Configuração do PHP**
Edite o arquivo php.ini para ajustes de desempenho:

```sudo nano /etc/php/8.1/apache2/php.ini```

Substitua ou adicione as seguintes linhas:

session.auto_start = 1
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE
memory_limit = 1024M
post_max_size = 128M
display_errors = On
short_open_tag = On

**7. Reiniciar o Apache**
Após as mudanças, reinicie o Apache para aplicar as configurações:

```sudo systemctl restart apache2```

**Instalação do Banco de Dados**

**8. Instalação do PostgreSQL e PGAdmin**
Instale o PostgreSQL e o PGAdmin:

```sudo apt install postgresql postgresql-contrib -y```
```sudo apt install pgadmin4 -y```

**9. Instalação do Projeto SIAUDI**
Copie o arquivo siaudi2 para o diretório /var/www:

```sudo cp siaudi2 /var/www/```
```sudo chown -R www-data:www-data /var/www/siaudi2```
```sudo chmod -R 755 /var/www/siaudi2```

**10. Criação da Base de Dados**
Altere a localidade do sistema:

```sudo nano /etc/profile```

Adicione a linha:
export LANG=pt_BR.UTF-8

Em seguida, execute:
```sudo locale-gen pt_BR.UTF-8```
```source /etc/profile```
```sudo systemctl restart postgresql```

**Crie a base de dados usando o comando:**

```sudo -u postgres psql -f /var/www/siaudi2/script_Bd/siaudispb.sql```



**Configuração e Acesso ao SIAUDI**

**1. Configuração da Conexão com o Banco de Dados**
Edite o arquivo de configuração do SIAUDI:

```sudo nano /var/www/siaudi2/protected/config/main.php```
Atualize a configuração de conexão com o banco:

'db' => array(
    'class' => 'application.components.MyDbConnection',
    'connectionString' => 'pgsql:host=localhost;port=5432;dbname=bd_siaudi',
    'username' => 'usrsiaudi',
    'password' => '!@#-usr-siaudi',
    'charset' => 'UTF-8',
),

**2. Verificação da Porta do PostgreSQL**
Para verificar a porta do PostgreSQL, execute:
```sudo nano /etc/postgresql/12/main/postgresql.conf```

Procure por port e verifique se é a 5432.
Caso contrário, atualize o arquivo de configuração do SIAUDI conforme necessário.

**3. Configuração do Sistema no SIAUDI**
Edite novamente o arquivo main.php e adicione os e-mails de auditoria:

'emailGrupoAuditoria' => array(
    'email1@dominio.com',
    'email2@dominio.com',
),

**4. Acesso ao Sistema**
Acesse o sistema via navegador:

```http://localhost/siaudi2```

Credenciais de acesso:

Usuário: siaudi.gerente
Senha: 123456
