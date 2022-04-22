## Kostie

API for "kos-kos'an" management build in Laravel using OAuth with Laravel passport

## Setup & Install

1. Clone this repository
2. Go to directory of downloaded repository
3. Run `composer install` on terminal/cmd
4. Run `cp .env.example .env`
5. Run `php artisan key:generate`
6. Configure `.env` file such as db_name, db_username, etc.
7. Run `php artisan passport:install`
8. Now it can running with command `php artisan serve`

## Scheduled

Scheduling can be done with creating a crontab, open terminal and run `crontab -e` then write this `* * * * * cd path_to_project_folder && php artisan schedule:run >> /dev/null 2>&1` on the last line of the crontab. Configure .env file of `QUEUE_CONNECTION` from `sync` into `database`. Create jobs table migration with `php artisan queue:table` then run `php artisan migrate`. For monitoring the queue and keep the worker running, I am use PM2 from https://pm2.io/, install pm2 with `npm install pm2 -g` after installation complete then run `pm2 start worker/user-recharge.yml` command to monitor and keep the worker keep alive.

## Testing

1. Test via postman with postman collection and environment https://github.com/awgst/kostie/blob/master/kostie.postman_collection.json
2. Test via phpunit || via `php artisan test` command
