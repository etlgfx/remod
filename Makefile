autoload:
	grep --exclude=Autoload.class.php -or "class \w\+" src/classes/ src/controllers/ | sed 's/\(.*\):class \(.*\)/\2=\1/'
