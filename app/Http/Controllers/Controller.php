<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private $user_id = 0;
    private $role_id = 0;

    protected function get_user_id()
    {
        return $this->user_id = auth()->id();
    }

    protected function get_role_id()
    {
        return $this->role_id = auth()->user()->role_id;
    }

    public function getTeacherIdByRole()
    {
        return $this->get_role_id() == 3 ? $this->get_user_id() : 0;
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
