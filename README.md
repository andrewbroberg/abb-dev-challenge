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

You will need to edit your `.env` and replace `DB_DATABASE=/Users/andrew.broberg/code/dev-challenge/database/database.sqlite` with the location of your database

Once you've done the above, run the following command. This will output an API key, make sure you copy this down somewhere as you will need this as part of the challenge.

```shell
php artisan migrate:fresh --seed
```

Now let's install our frontend assets. We have set you up with Vue2 and Tailwindcss. You just need to run the following commands to get going

```shell
npm install
```

Now run the following, which will watch for any changes to your frontend assets and rebuild for you automatically.

```shell
npm run watch
```

## The Requirements

The requirements of the challenge are as follows. You want to have a functioning game by the end of the challenge, so don't spend too much time focussing on making it look pretty. Feel free to pretty it up once you have the game functioning.

You are to build a frontend using VueJS, this is installed for you along with TailwindCSS. The API should be built in Laravel which has been installed for you.

1. All request to the API are authenticated. You should provide the API token as the `Authorization: Bearer <token>` header.
2. The game board should load the game from the API when loading the page. If the user was to come back to the page, it should load the current game status from the API. This can be found in the API Documentation under the `GET game` endpoint
3. When a user types a 5 letter word, and presses `ENTER` on their keyboard, it should be submitted to the API. The is found under `POST guesses` endpoint.
   - The guess must be 5 capital alpha characters
4. The submitted guess must be stored in the database and be linked to the user that submitted it.
5. The API will return back a `201` response with the guess and the status of each letter.
6. You should display each guess in a grid and colour the letters as follows:
   - Correct (Green)
   - Incorrect (Gray)
   - Wrong Location (Yellow)
7. The user can only make a maximum of 6 guesses. If the user has used all their guesses, the API will return a `409 Conflict` response.
8. If the user has used all their guesses, they should be shown what the correct answer was. This will be available in the response once the user reaches all their guesses.
9. If the user has gotten the word correct, they shouldn't be able to submit anymore guesses.
