<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Activity;
use Core\Navigator;
use Core\Response;

class ActivityController extends AdminController
{
  private Activity $activity;

  public function __construct()
  {
    parent::__construct();
    $this->activity = new Activity();
  }
  public function index()
  {
    $activities = $this->activity->all(true, [], [], 'created_at', 'DESC') ?? [];
    // reorganize activities by date 
    
  
    return Response::view('admin/activities/index', 'admin-layout', [
      'title' => 'Tevékenységnapló',
      'activities' => $activities,
    ]);
  }

  public function destroy($id)
  {
    try {
      $activity = $this->activity->find($id);
      if ($activity) {
        $this->activity->destroy($id);
      }

      $this->toast->success('Tevékenység napló bejegyzés sikeresen törölve.')->back();
    } catch (\Exception $e) {
      $this->toast->danger('Hiba történt a tevékenység napló bejegyzés törlése során.' . $e->getMessage())->back();
    }
  }

  public function destroyAll()
  {
    try {
      $this->activity->destroyAll();

      $this->toast->success('Tevékenység napló bejegyzés sikeresen törölve.')->back();
    } catch (\Throwable $th) {
      $this->toast->danger('Hiba történt a tevékenység napló bejegyzés törlése során.' . $th->getMessage())->back();
    }
  }
}
