<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RoleShift;
use Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) { 
        
        $user = auth()->user();

        $roles = Role::where(['company_id' => $user->company_id])
                            ->get();
        
        if(0 == $roles->count()) {
            return response()->json(['message' => 'no data found'], 201);
        }
        return response()->json($roles);
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
    public function store(Request $request) {        

        $authUser = auth()->user();
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

        $keyedPermissions = [];
        if ($permissions) {

            foreach($permissions as $permission) {
                $keyedPermissions[] = [
                    'permission_id' => $permission
                ];
            }
        }

        $role->permissions()->sync($keyedPermissions);

        return response()->json(['message' => 'Role added sucessfully.']);    
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
    public function edit(Request $request, Role $role) {
        
        $arrResponseArray = [
            'id' => $role->id,
            'role_name' => $role->name
        ];
        if($role->permissions) {
            foreach($role->permissions as $permission) {
                $arrResponseArray['permissions'][] = $permission->id;
            }
        }
        if($role->shifts) {
            foreach($role->shifts as $shift) {
                $arrResponseArray['shifts'][] = [
                    'id' => $shift->id,
                    'name' => $shift->name,
                    'from_time' => $shift->from_time,
                    'to_time' => $shift->to_time
                ];
            }
        }
        return response()->json($arrResponseArray);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role) {
        
       
        $postData = $request->getContent();       
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'role_name' => 'required',            
        ], [], [
            'role_name' => 'Name'            
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }       
        
        $role->name = $postData['role_name'];         
        $role->save();

        $permissions = array_key_exists('permissions', $postData) ? $postData['permissions'] : null;

        $keyedPermissions = [];
        if ($permissions) {

            foreach($permissions as $permission) {
                $keyedPermissions[] = [
                    'permission_id' => $permission
                ];
            }
        }

        $role->permissions()->sync($keyedPermissions);

        $shifts = array_key_exists('shifts', $postData) ? $postData['shifts'] : null;

        foreach ($shifts as $shift) {

            if(array_key_exists('id', $shift) && $shift['id']) {
               
                $roleShift = RoleShift::find($shift['id']);
                $roleShift->role_id = $role->id;
                $roleShift->name = $shift['name'];
                $roleShift->from_time = $shift['from_time'];
                $roleShift->to_time = $shift['to_time'];
                $roleShift->save();
            } else {

                $roleHasShift = RoleShift::create([
                    'role_id' => $role->id,
                    'name' => $shift['name'],
                    'from_time' => $shift['from_time'],
                    'to_time' => $shift['to_time']
                ]);
            }
        }

        $deleteShifts = array_key_exists('delete_shifts', $postData) ? $postData['delete_shifts'] : null;

        if($deleteShifts) {
            foreach($deleteShifts as $deletedShift) {
                $roleShift = RoleShift::find($deletedShift);
                $roleShift->delete();
            }
        }
        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
     {
               
        $role =  Role::where('id', $id)->first();

        if(!$role){
            
            return response()->json(['message' => 'Role not found']);
        }

        $role->delete();

        return response()->json(['message' => 'Deleted Successfully']);
    }
}