# Installation de Todo App

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/8ae6895b010d4555a2de703e21aee698)](https://app.codacy.com/app/Mat40100/todo?utm_source=github.com&utm_medium=referral&utm_content=Mat40100/todo&utm_campaign=Badge_Grade_Dashboard)

### Clone the repository via terminal in your web folder:
  > git clone https://github.com/mat40100/todo.git
  
### Create .env variables (exemple) : 
  - APP_ENV=dev
  - APP_DEBUG=0
  - APP_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
  - DATABASE_URL=mysql://xxx:XXX@localhost:3306/db_todo
  - MAILER_URL=null://localhost
  
 ### In terminal : 
  > bin/console doctrine:database:create && doctrine:schema:create
  
 ### set APP_END in .env to "prod"
 ### TO be the first Admin:
 - create a user ;
 - go in your database & modify ROLE_USER to ROLE_ADMIN
 - your are now the first admin, you can upgrade user from the app !
