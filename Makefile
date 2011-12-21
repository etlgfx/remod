autoload:
	grep --exclude=Autoload.class.php -or "class \w\+" src/classes/ src/controllers/ | cut -c 5- | sed 's/\(.*\):class \(.*\)/\2=\1/' | php src/config/generator.php > /tmp/config.ini
	cp /tmp/config.ini src/config/config.ini

test: autoload output-dirs
	@phpunit --bootstrap tests/unit/bootstrap.php --coverage-html out/reports tests/

output-dirs:
	@mkdir -p out/reports
