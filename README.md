# COMP3210 Network Demonstrator Web Application
This web application allows you to use Bluetooth Low Energy beacons built using a Raspberry Pi Pico for indoor navigation in your browser.

## Setup
Run `npx mix watch` followed by `sudo ./vendor/bin/sail up` in a separate terminal to start development environment

Run `php artisan key:generate` to generate keys

Run `sudo ./vendor/bin/sail artisan migrate` to migrate database

Run `php artisan make:migration create_users_table` to create migration

Run `php artisan make:controller VesselController --resource` to make a new controller

Run `php artisan make:view settings` to make a new view

Run `php artisan make:component Head` to make a new component (use with `<x-head/>`)