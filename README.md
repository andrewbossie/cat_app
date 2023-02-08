# Cat-App

## Description

This app exposes an artisan command that allows retrieval of
cat pictures from [Cataas](cataas.com). All business logic / network calls have been relegated to a service class called
`ImageService.php` . Images are stored in user defined folders within Laravel's public root folder (default storage for this framework).

## Prerequisites

`laravel/framework": ^9.19`

## Dependencies Consumed

`guzzlehttp/guzzle: ^7.5`

## Run

`php artisan cat:get {dir} {lim} {tags?}`

## Params

`dir` - Output Directory

`lim` - API return limit

`tags` - OPTIONAL comma delim filtering

## Test

`php artisan test`
