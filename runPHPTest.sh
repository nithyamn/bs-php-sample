python -m SimpleHTTPServer 8888 &
pid=$!
php php_single.php
kill $pid
