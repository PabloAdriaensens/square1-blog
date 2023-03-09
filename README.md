SQUARE1 Technical Test
========================

Build a web blogging platform

Technological Stack
------------

1. PHP 8.1;
2. Docker - PHP 8.1 + Symfony 5.4.21
3. Docker Compose
4. Browser

Installation
------------

* Clone GitHub repository
```
git clone https://github.com/PabloAdriaensens/square1-blog
```

* Access into directory:
```
cd square1-blog
```
* Build, Run and Start Docker:
```
make initialize
```
* Tailwind ya ha sido compilado, pero si lo queremos volver a compilar:
```
make ssh-be
```
```
npx tailwindcss -i ./assets/styles/app.css -o ./public/build/app.css --watch
`````
* Project setup (new terminal):
```
make ssh-be
```
```
sf doctrine:database:create
```
```
sf doctrine:migrations:migrate
```
```
sf doctrine:fixtures:load
```
* Ready to access to browser:
```
http://localhost:1000/
```
 * Execute tests:
```
php bin/phpunit
```

Endpoints
-----

**Blog Urls**

* Default URL:
    * <http://localhost:1000>

* Login URL:
    * <http://localhost:1000/login>

* Register URL:
    * <http://localhost:1000/register>
