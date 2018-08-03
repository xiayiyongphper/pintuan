#!/bin/bash
php /home/xia/pintuan/api/http_server.php restart
php /home/xia/pintuan/crontab/server.php restart
php /home/xia/pintuan/order/server.php restart
php /home/xia/pintuan/pay/server.php restart
php /home/xia/pintuan/product/server.php restart
php /home/xia/pintuan/store/server.php restart
php /home/xia/pintuan/user/server.php restart

