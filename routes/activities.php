<?php

use App\Http\Controllers\Admin\ActivityController;

  $router->resources('/admin/activities', ActivityController::class, 'admin');
  $router->delete('/admin/activities/delete-all', [ActivityController::class, 'destroyAll'])->middleware(['admin']);
?>