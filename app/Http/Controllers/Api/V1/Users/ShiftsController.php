<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\RoleShift;
use App\Models\Shift;
use App\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class ShiftsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $authUser = auth()->user();

        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'from_date' => 'required',
            'to_date' => 'required',
            'role_id' => 'required',
            'shift' => 'required',
            'user_id' => 'required',
        ], [], [
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'role_id' => 'Role',
            'shift' => 'Shift',
            'user_id' => 'User'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $startDate = Carbon::parse($postData['from_date']);
        $endDate = Carbon::parse($postData['to_date']);

        $days = $endDate->diffInDays($startDate);

        $calendarStartDate = Carbon::parse($postData['from_date']);

        for ($i = 0; $i < $days; $i++) {
            $shiftDate = $calendarStartDate->format('Y-m-d');

            $shift = Shift::create([
                'role_id' => array_key_exists('role_id', $postData) ? $postData['role_id'] : null,
                'shift' => array_key_exists('shift', $postData) ? $postData['shift'] : null,
                'date' => $shiftDate,
                'user_id' => array_key_exists('user_id', $postData) ? $postData['user_id'] : null
            ]);

            $calendarStartDate = $calendarStartDate->addDay();
        }

        return response()->json(['message' => 'Shift Saved Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showShifts(Request $request)
    {
        $userShifts = Shift::all();
        $roles = Role::all();
        $shifts = Shift::$__shift_types;


        $postData = $request->getContent();

        $postData = $postData ? json_decode($postData, true) : [];


        $validator = Validator::make($postData, [
            'start_date' => 'required',
            'end_date' => 'required'
        ], [], [
            'start_date' => 'From Date',
            'end_date' => 'To Date'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $startDate = Carbon::parse($postData['start_date']);

        $endDate = Carbon::parse($postData['end_date']);

        $days = $endDate->diffInDays($startDate);

        $calendarStartDate = Carbon::parse($postData['start_date']);

        $bodyRows = [];
        $j = 0;
        $bodyRows[$j] = [
            'row_type' => 'heading',
            'heading_one' => 'Roles',
            'heading_two' => 'Shifts',
        ];
        $dates = [];
        for ($i = 0; $i < $days; $i++) {
            $dates[] = [
                'date' => $calendarStartDate->format('Y-m-d')
            ];
            $calendarStartDate->addDay();
        }
        $bodyRows[$j]['dates'] = $dates;
        $j++;

        foreach ($roles as $role) {
            foreach ($shifts as $shift) {
                $bodyRows[$j] = [
                    'row_type' => 'body',
                    'role_name' => $role->name,
                    'shift' => $shift
                ];
                $users = [];

                $calendarStartDate = Carbon::parse($postData['start_date']);

                for ($i = 0; $i < $days; $i++) {

                    $userShift = Shift::where('shift', $shift)
                        ->where('date', $calendarStartDate)
                        ->where('role_id', $role->id)
                        ->get()->first();

                    $users[] = [
                        'date' => $calendarStartDate->format('Y-m-d'),
                        'user_id' => $userShift ? $userShift->user_id : null,
                        'user_name' => $userShift ? $userShift->user->first_name : null
                    ];

                    $calendarStartDate->addDay();
                }

                $bodyRows[$j]['users'] = $users;
                $j++;
            }
        }

        return response()->json($bodyRows);
    }

    public function addRoleShifts(Request $request)
    {
        $role = new Role();
        $roleHasShift = new RoleShift();
        $postData = $request->getContent();
        $postData =  $postData ? json_decode($postData, true) : [];


        $validator = Validator::make($postData, [
            'role_name' => 'required',
            'shifts.*.name' => 'required',
            'shifts.*.from_time' => 'required',
            'shifts.*.to_time' => 'required'

        ], [], [
            'role_name' => 'Role Name',
            'shifts.*.name' => 'Shift Name',
            'shifts.*.from_time' => 'From Time',
            'shifts.*.to_time' => 'To Time'
        ]);


        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }
        $user = auth()->user();

        $role = Role::create([
            'company_id' => $user->company_id,
            'name' => $postData['role_name']
        ]);

        $shifts = array_key_exists('shifts', $postData) ? $postData['shifts'] : null;

        foreach ($shifts as $shift) {

            $roleHasShift = RoleShift::create([
                'role_id' => $role->id,
                'name' => $shift['name'],
                'from_time' => $shift['from_time'],
                'to_time' => $shift['to_time']
            ]);
        }

        $permissions = array_key_exists('permissions', $postData) ? $postData['permissions'] : null;

        if ($permissions) {

            for ($i = 0; $i < sizeof($permissions); $i++) {

                $roleHasPermission = RoleHasPermission::create([
                    'permission_id' => $permissions[$i],
                    'role_id' => $role->id
                ]);
            }
        }

        return response()->json(['message' => 'Role added sucessfully.']);
    }

    public function loadPermissions(Request $request)
    {
        $permission = Permission::all();
        return response()->json($permission);
    }

    public function roleShifts(Request $request)
    {
        $permission = Permission::all();
        return response()->json($permission);
    }
}
