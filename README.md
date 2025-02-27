[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F70347a29-983b-48c8-bc18-5084f86e241c&style=plastic)](https://forge.laravel.com/servers/886744/sites/2628407)

# Capital Cities Quiz

Application to test users knowledge of capital cities, built with Laravel & React.js.

a live example of the quiz can be found here [cc-quiz.johnhalsey.co.uk](cc-quiz.johnhalsey.co.uk) 

## To install locally

You will need Laravel Valet (or a similar php environment installed to run this application)

Run these commands
- `git clone {git url}`
- `cd` to new directory
- `composer install`
- `npm install`
- copy the `.env.example` to `.env`
- `php artisan key:generate`
- Create a local database, and update the .env database keys
- `php artisan migrate`
- `npm run dev`

You should now be able to run this application locally.

## How does it work?

The application makes a request to https://countriesnow.space/api/v0.1/countries/capital to obtain countries and capital cities data.

It then provides the user with a country and 3 possible options for them to select.

Once the countries data has been retrieved once for each quiz starting, the dataset is cached for an hour so as not to keep hitting the countriesnow url.  This also speeds up the experience for the user.

This was part of a coding test, the full instructions can be found [here](software-engineer-php-v4.pdf). 
