# Remind-Me-API

An application to set a list of movie to watch, album/songs to listen and book to read.

This project is for training purpose only. Don't use real infos here !

# Requirements

<ul>
    <li>Symfony > 5.0.0</li>
    <li>PHP > 7.2</li>
    <li>Apache server</li>
    <li>MySQL or PostGRESQL</li>
</ul>

# How to run
1) Clone the project on your local repository

```bash
git clone git@github.com:AlexandreRavichandran/Remind-Me-backend.git
```
2) go to .env file, remove the # and change the DATABASE_URL depending on your database software

3) Create the database with either Symfony CLI (if you have it installed ) or basic php console commands.

```bash
symfony console doctrine:database:create
```
or
```bash
php bin/console doctrine:database:create
```

4) Make all migrations to the database to create and update all fields on the database

```bash
symfony console doctrine:migrations:migrate
```

or 

```bash
php bin/console doctrine:migrations:migrate
```

5) (OPTIONNAL) Create fixtures to add some fake data so that you can explore all features of the website

```bash
symfony console doctrine:fixtures:load
```

or

```bash
php bin/console doctrine:fixtures:load
```

6) open the php server

```bash
symfony serve -d
```

or

```bash
php -S 0.0.0.0:8080 -tpublic
```

You can now try all requests routes !

If you are using an API testing software like <a href="https://www.postman.com/">Postman</a> or <a href="https://insomnia.rest/">Insomnia</a>, you can import the request collection file which can be downloaded <a href="/docs/Api-Remind-Me.Request-Collection.json">here</a>.
# Features

<ul>
    <li>Search a movie/album/song/book by its name</li>
    <li>Show details about the searched movie/album/song/book</li>
    <li>Login/register</li>
    <li>Manage a personnal list of movie/album/song/book</li>
</ul>

# Request routes

You can see all routes managed by the API on the <a href="https://remind-me-api.herokuapp.com/">API main page</a>.
 

# Origin of the project
This project is my first experience with API Platform.
I wanted to make a project with separated backend and frontend. The theme of the project, which is to set a list of movies to watch, books to read or music to listen, is a kind of project that was in my mind for several weeks.
Thanks to this project, I have seen all the advantages that API Platfome provides, and how fast an API can be made with it. I have also experimented custom providers, with external APIs such as Deezer API or Google Books API.