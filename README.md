# Task

> Thank you for this torture! :D Yet again, this reminded me why I should stay away from old code such as S4.

## Considerations

* **No proper DTO support**: Unfortunately S4 lacks proper DTO capabilities, heavily relying on outdated `symfony/form`. I spent a little time to create a working `DataMapper` using forms, but that endeavor proved fruitless and time-consuming. With S5 or above I could do this: creating proper DTO, attaching Data-Transformer to it and hooking them to Entities... Or worst case scenario, I could've used Api-Platform. Any of these paths taken would've solved many problems...
* **No proper Validation**: ... many problems one of which is Validation. Validating against ORM POPO's (plain old PHP object) is a pain, especially when it comes to resolving reference objects. 
* **No proper Serialization**: ... many problems, another of which is Serialization. Especially when it comes to ORM references, which cause circular-reference issues. As such, I added fake denormalization to each controller action.
* **Fixtures**: Added `UserFixture` which creates 2 users to be used by tests.
* **No proper API Testing**: Yes, S4 also lacks proper API-testing utility, instead featuring `BrowserKit` for browser testing. With S5 or higher we could have better capabilities. With added API-Platform it'd be even better. Had to create `NullLogger` and use it inside `services_test.yaml` to silence API "exceptions" as well.

## Setup

* If it is desired for Docker user (inside the `php` container) to have the same UID/GID as the Host-machine user (in order to avoid permission issues):
  ```
  export MYUID=$(id -u);
  export MYGID=$(id -g);
  ```
  **Skip this if Host UID and GID both are `1000`**.
* `docker compose build`
* `docker compose up -d` spins the env.
* `docker-compose exec php bash -l` to enter the container terminal:
  * `composer install`
  * `./bin/phpunit`
