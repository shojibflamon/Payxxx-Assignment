# Recruitment Assignment PHP

# Installation
Clone this repo. Go to this repo directory in terminal and run this command

```shell
composer install
```

Wait for few minutes. Composer will automatically install this package for your project.

# Run (Commission Fee)
After installing go to your terminal & run this command. This command will get you the final commission fee depending on exchange rate.
There are two exchange rate implemented. One is given static and another one is live (https://developers.----.com/tasks/api/currency-exchange-rates)

To change the exchange rate static to live ot vice versa follow the instruction.

```shell
Go src/Model>Amount.php and change the value of EXCHANGE_RATE_API_SOURCE = live|static
```

```shell
php demo.php input.csv
```

# Run (Exchange Currency)
To check currency exchange rate with static or live go to terminal & run this command

```shell
php currency.php
```

# Unit Test
Go to your terminal & run this command. This command will run a sample currency conversation test.
```shell
vendor/bin/phpunit
```
