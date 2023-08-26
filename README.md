# Simple Restaurant System


## Project Structure

Just basic laravel component and in addition to the essential component for any clean project like:

- Service Class.
- Repository.

## Installation

Just clone the project and use the docker to start. as I used (Sail) so its easy to install and run the project.

```./vendor/bin/sail up```

and the open any REST API client and hit the api

`http://localhost:7777/api/orders/place-order`

*you can change the port 7777 by add `APP_PORT=7777` to you `.env` file or use the default one `80`*

To run the application test cases

```./vendor/bin/sail test```


## What I did ?

- Api request to create a new order.
- validate the request payload.
- check the product stock if available.
- persist the order in the database with the items.
- dispatch a background job to update the stock
  - Fire an event to check the stock quantity.
  - Send a notification from the event listener if the stock quantity reached the limit (50%)
- 8 Test cases to test all the above.

## DB ERP

<img src="https://raw.githubusercontent.com/AhmedHdeawy/foodics/develop/public/images/DB%20ERP.png" width="400" alt="DB ERP">

## Test

<img src="https://raw.githubusercontent.com/AhmedHdeawy/foodics/develop/public/images/Tests.png" width="400" alt="DB ERP">

## Enjoy
