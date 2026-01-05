<?php

namespace App\Services;

use App\Models\Activity;

//id	admin_id	action	status	category	description	target_type	target_id	created_at	updated_at	
class ActivityLogger {
  public static function log( $action, $status = 'success', $category = null, $description = null, $targetType = null, $targetId = null)
  {
    $admin = auth('admin');

    if(!$admin) {
      dd('No admin authenticated');
      return;
    }

    try {
      (new Activity())->create([
        'admin_id' => auth('admin')->id ?? null,
        'adminName' => auth('admin')->name ?? null,
        'action' => $action,
        'status' => $status,
        'category' => $category,
        'description' => $description,
        'target_type' => $targetType,
        'target_id' => $targetId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ]);
    } catch (\Throwable $th) {
      // Logolási hiba kezelése (pl. naplózás egy fájlba vagy hibajelzés)
      dd($th->getMessage());
    }
  }
}
