# Cat-App

## Description

This app exposes an artisan command that allows retrieval of
cat pictures from [Cataas](cataas.com). General command functionality is defined in `GetCat.php` . All business logic / network calls have been relegated to a service class called
`ImageService.php` . Images are stored in user defined folders within Laravel's public root folder (default storage for this framework).

## Prerequisites

`laravel/framework": ^9.19`

## Dependencies Consumed

`guzzlehttp/guzzle: ^7.5`

## Run via Command Line

`php artisan cat:get {dir} {lim} {tags?}`

## Params

`dir` - Output Directory

`lim` - API return limit

`tags` - OPTIONAL comma delim filtering

## Test via Command Line

`php artisan test --testsuite=Feature`
