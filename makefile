cmp install:
	docker run --rm -v $(pwd):/app composer install

npm install:
	./vendor/bin/sail npm install

npm run dev:
	./vendor/bin/sail npm run dev		

start:
	./vendor/bin/sail up -d

stop:
	./vendor/bin/sail down

migrate:
	./vendor/bin/sail artisan migrate --seed

restart:
	./vendor/bin/sail down
	./vendor/bin/sail up -d
