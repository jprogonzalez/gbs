<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends ApiController
{
    public function summary()
    {
        $totalUsers = DB::table('users')->count();

        $usersByRole = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('roles.name as role', DB::raw('COUNT(users.id) as count'))
            ->groupBy('roles.name')
            ->get();

        return $this->responseWithData([
            'total_users' => $totalUsers,
            'users_by_role' => $usersByRole,
        ], 'dashboard.summary');
    }
}
