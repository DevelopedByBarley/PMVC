

<?php

use Core\Language;
use Core\Navigator;
use Core\Request;

$router->view('/', 'components/layout', 'welcome');
$router->post('/lang', function() {
  Language::switch(Request::key('lang'));
  Navigator::redirectBack();
});

require base_path('routes/admin.php');
require base_path('routes/user.php');
require base_path('routes/auth/user_auth.php');
require base_path('routes/auth/admin_auth.php');


