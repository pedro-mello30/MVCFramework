Docker utilizando o docker-compose, arquivo de de configuração das variáveis de ambiente, criando um ambiente com 
php-apache, mysql, phpmysql, grunt e app (com framework PHP) conectandos através de links e volumes.



|- /htdocs
	 -| /www
		 -| /app 
		 -| /src 
		 -| /system 
		 -| manage.py
		 -| .htaccess
|- /mysql
	 -| /dump
|- /php-apache
	 -| Dockefile
	 -| apache2.conf
|- /grunt
|- docker-compose.yml
|- env_default
|- .env






Framework PHP com arquitetura MVC, rodando em cima de docker, utilizando bibliotecas 






Refatorar Framework
    Redirect
    Model
Trocar servidor pelo Nginx
Fazer manage.py utilizando docker
Script de instalação
Script de configuração


		    
		    
		    
		    
	GruntAdmin
	grunt
		Reoganizar GruntConfig
	Manage	
		~~Modificar manage.py para utilizar com admin e src~~
		Acrescentar funcionalidade para preparar a criação do CRUD
	Instalação do ambiente na maquina
