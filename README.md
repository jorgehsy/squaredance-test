
## Squaredance Challenge Solution

The following is my solution to the backend challenge requested by Squaredance. For experience that reviewing code can be a little tedious and for the sake of simplicity, I avoided using some PHP tricks to write less code. This was with the purpose of maximizing the code readability and understanding my proposed solution, while respecting good design patterns and the single responsibility principle.


Laravel framework and its ecosystem were used to build the solution.

# Tasks

The main task was to create a few endpoints fed by a proposed schema. Around those involved some other requirements and were added to build a more complete solution. Those are the following:
- Authentication using Laravel Sanctum
- Endpoints to make sales and deduct product inventory
- Endpoints to approve or reject pending products
- A very simple (and ugly) frontpage just to play around with the solution

# Demo environment
> **Don't want to read? below you will find a TL;DR with all the instructions**

This solution can be launched using Docker. This is the easiest way to get the application up and running. 

For this to work you only need two requirements:
- Docker Desktop installed [Docker Installation](https://www.docker.com/products/docker-desktop/)

Next, follow the next instructions (assuming linux/macos system):
```
git clone https://github.com/jorgehsy/squaredance-test.git
cd squaredance-test
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
This could take minutes to finish, once done, run the next command to start the docker container as a daemon

```        
./vendor/bin/sail up -d
```

Then we need to setup the enviroment file and some migrations. Run the following commands:
```
cp .env.example .env
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

That's it! the API is ready to use. If you like to use some API test software like Postman, you can use the file `squaredance-test.har` located in this repo and import it.

Available methods: 
```
  POST            api/auth/login 
  POST            api/auth/register 
  POST            api/product/{product}/manage/{status} 
  POST            api/product/{product}/sell 
  GET|HEAD        api/products 
  POST            api/products 
  GET|HEAD        api/products/create
  GET|HEAD        api/products/status/pending
  GET|HEAD        api/products/{product}
  PUT|PATCH       api/products/{product} 
  DELETE          api/products/{product}
  GET|HEAD        api/products/{product}/edit
  GET|HEAD        api/users/{user}/notifications 
  PUT             api/users/{user}/notifications/{id
````

If you like to try the ugly front, follow the next commands:
```
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```
This will build the files needed to get the login and dashboard ready. 

Visit the site on the browser (should be http://localhost). 

You can now login (use the random log in button) and enter the dashboard. This button will create an user with some data ready to try.

---
## TL;DR
```
git clone https://github.com/jorgehsy/squaredance-test.git

cd squaredance-test

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up -d

cp .env.example .env

./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan migrate

./vendor/bin/sail npm install

./vendor/bin/sail npm run build
```

---
### ¿Don't want to use docker?

You should satisfy the requirements to install Laravel 9 and the standart instrucctions to run on a local enviroment [Installation instructions](https://laravel.com/docs/9.x#initial-configuration)


# Solution Explanation

The first thing that comes to my mind is an event-driven application, where you subscribe to channels to consume the notifications as soon as the status changes. Tools like Kafka or RabbitMQ are popular to solve this in a more reliable way, but for this challenge, they will surely be overkill.


We need to create some models to construct the business logic:
- **User**: resource to identify the owner of the products and the buyer of the products.
- **Product**:foundational resource where the functional logic is built around it.
- **Transaction**: basic model to record the sales and calculate the user profit
- **UserProduct**: pivot model to improve the usability of the relationship between user and product


Using the Laravel Observers we catch when a product status changes on update and save the specific notification using the Laravel Notification solution. I repeat, this type of notification should not be saved on a database like MySQL or PostgreSQL for multiple reasons.
