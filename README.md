




# Project setup
1. clone the repository:
```
https://github.com/bristimaharjan/Eventify.git
```
2. Install Dependencies:
   ```
   cd blog
   composer install
   npm install
   ```
3. Database Setup:
    - Copy the .env.example file to .env and configure your database settings.
    - Run database migrations:
   ```
   php artisan migrate
   ```
4. Generate Application Key:
   ```
   php artisan key:generate
   ```
5. Serve the Application:
   ```
   composer run dev
   ```
6. Open in web browser:
   ```
   http://127.0.0.1:8000/
   ```
**Note:** Vite and Tailwind CSS are auto configured with every Laravel projects. No need to manually configure them.
