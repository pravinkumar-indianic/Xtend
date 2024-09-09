### Installation
### 1. Docker Setup:
1. Open command line in the project root folder and run:
   `git submodule update --init --recursive`

2. Go to laradock folder and copy `.env.example to` `.env`.

3. Update .env file
    - Change the php version `PHP_VERSION=8.1` 8+ is recommended
    - Change the node version `WORKSPACE_NODE_VERSION=v14.0.0` 14+ recommended
    - Update laradock/nginx/sites/default.conf `root=/var/www`

   Note: If you need to change mysql port update the `MYSQL_PORT` parameter

4. Run containers:
   (in the laradock folder )
   `docker-compose up -d nginx mysql phpmyadmin`

   Note: use `docker-compose up -d nginx mysql phpmyadmin --build` if you need to rebuild the containers (e.g. if you've changed the .env)

5. Enter the workspace (docker container) via command:
   `docker-compose exec --user=laradock workspace bash`

### 2. Install Depedencies

Run the following commands:

`composer install`

`npm install`

### 3. Create public folder
You need to spawn a public folder using the october:mirror command:

`php artisan october:mirror`

**In Windows operating systems, the october:mirror command can only be executed in a console running as administrator.**

It will create a new directory called public in the project's root directory. Inside the directory, the command creates symbolic links to assets and resources directories for all plugins, modules and themes existing in the project.

### 4. Setup Database

- Set up a local MYSQL Database
- Import `staging_dump_05042023.sql`

### 5. Test Backend

- Reset admin password php artisan `october:passwd admin <your-password>`
- Use the `BACKEND_URI` value from your .env file to access the backend. Recommend URI is `\backend`

`http://localhost/backend/backend/auth/signin`

### 6. Test Frontend

You can either impersonate any user via backend (Users -> select user -> Impersonate User)
(Recommended user : Anthony Saunders, ID: 2824)

or

you can access it via `/login/default`

`http://localhost/login/default`
