<?php
require_once dirname(__FILE__) . '/../init.php';

print_r(ORM::for_table('user')->find_many());
