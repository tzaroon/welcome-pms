<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
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

        $postData = json_decode($postData, true);
        

        $validator = Validator::make($postData, [
            'name' => 'required'            
        ], [], [
            'name' => 'Name'           
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $role = new Role();
        $role->fill($postData);
        $role->save();

        return response()->json($role);        
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
    public function edit(Request $request, $id) {
        
        $role =  Role::find($id);
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
       
        $postData = $request->getContent();       
        
        $postData = json_decode($postData, true);

        $role =  Role::find($id);

        if (!$role) {

            return response()->json(array('errors' => ['user' => 'role not found']), 422);
        }

        $validator = Validator::make($postData, [
            'name' => 'required',            
        ], [], [
            'name' => 'Name'            
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }       
        
        $role->name = $postData['name'];         
        $role->save();
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
