# Aussie Broadband Dev Challenge
For this dev challenge we are going to create a Wordle clone (https://www.nytimes.com/games/wordle/index.html)

You will be creating a frontend and API to create a functioning Wordle clone. We have provided a Laravel installation for you with a blank Vue component you can use for your game board. 

## Setup

To get going, you'll need to run the following commands.

Firstly, clone the following repo

```shell
git clone git@gitlab.aussiebb.com.au:dev-challenge/wordle.git
```

Then create a new branch for your team

```shell
git branch <team-name>
```

Now we need to install the composer dependencies

```shell
composer install
```


```shell
cp .env.example .env
```

Now let's generate our default Laravel App Key

```shell
php artisan key:generate
```
We have set you up with sqlite as the default for your database. You can use MySQL or Postgres if you like, but isn't required for this challenge.

You will need to edit your `.env` and replace `DB_DATABASE=DB_DATABASE=/Users/andrew.broberg/code/dev-challenge/database/database.sqlite` with the location of your database

Once you've done the above, run the following command. This will output an API key, make sure you copy this down somewhere as you will need this as part of the challenge.

```shell
php artisan migrate:fresh --seed
```
