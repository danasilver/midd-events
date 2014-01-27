PHP_OPTS = -S
PHP_PORT = 8000

.PHONY: serve

serve:
	php $(PHP_OPTS) localhost:$(PHP_PORT)