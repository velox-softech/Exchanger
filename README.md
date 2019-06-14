# Exchanger

This project lists all the exchange rates of given currency. Default base currency is `USD` for now which can be made dynamic based on user prefrence.

Project allows user to refresh all or particular exchange rates.

## Setup

_Setup process assumes you've access to the command line._

1. Clone this repository on your machine.
2. Go to the root directory of the project.
3. Create Symfony `.env` file. (You may copy .env.sample from the repository and change appropriate values.)
4. Execute `composer install` to install all vendor dependencies.
5. Execute `php bin/console doctrine:database:create` to generated the configured DB.
6. Excute `php bin/console doctrine:migrations:migrate` to generate the schema in DB.

After performing above steps you should be able to view the app in browser. 

## App Features
* `rates/` route will display all the today's exchange rates. If the table is empty, Click on `Refresh rates` in right corner.
* Click on `Refresh Rates` in top right corner to refresh today's exchange rates.
* Click on `Create New` in top right corner to add new exchange rate manually.
* User click refresh, edit or delete any particular exchange rate manually.

## Symfony Commands
Following two symfony commands are available.
1. `velox:exchanger:rates`\
This command returns JSON dump of today's exchange rates.

2. `velox:exchanger:refresh-rates`\
This command refresh today's exchange rates. 

Both the commands accept one argument (target_currencies). It is comma separated currency value paris for which particular command will be executed.

e.g.
```
php bin/console velox:exchanger:rates INR
php bin/console velox:exchanger:refresh-rates INR,EUR
```

As mentioned above, for this project base currency is `USD` which can be made dynamic based on user prefrences.

## Exchange Rates Provider
For this project we've used https://exchangeratesapi.io/ API to fetch the latest exchange rates.

You can implement any other provider by creating a service which implements `App\Services\Interfaces\ExchangeRateProviderImpl` and changing alias of `ExchangeRateProviderImpl` in `config\services.yaml` file. 

## Authors
Exchanger Symfony App has been developd by the team of [Velox Softech](https://veloxsoftech.com)