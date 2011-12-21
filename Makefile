autoload:
	grep --exclude=Autoload.class.php -or "class \w\+" src/classes/ src/controllers/ | cut -c 5- | sed 's/\(.*\):class \(.*\)/\2=\1/' | php src/config/generator.php > /tmp/config.ini
	cp /tmp/config.ini src/config/config.ini

