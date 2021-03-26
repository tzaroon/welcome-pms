<?php

namespace App\Http\Controllers\Api\V1\Extras;

use App\Http\Controllers\Controller;
use App\Models\Extra;
use Illuminate\Http\Request;
use Validator;

class ExtrasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {

        $user = auth()->user();
        $extras = Extra::where(['company_id' => $user->company_id])->get();

        $data = $this->paginate($extras);

        return response()->json($data);
    }

    /**
     * Store a new resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $authUser = auth()->user();
        
        $postData = $request->all();

        $validator = Validator::make($postData, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'gender' => 'required|string',
            'email' => 'required|string',
            'doc' => 'mimes:jpeg,png,pdf|max:4096',
        ], [], [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'email' => 'Email',
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

       
        $user = new User();
        $user->fill($postData);
        $user->company_id = $authUser->company_id;
        $user->save();
        
        $booker = new Booker();
        $booker->fill($postData);
        $booker->company_id = $authUser->company_id;
        $booker->user_id = $user->id;
        $booker->save();

        if ($request->hasFile('doc')) {
            
            $docPath = $request->file('doc')->hashName();
            $request->file('doc')->store('booker_docs');
            $booker->doc = $docPath;
            $booker->save();
        }

        return response()->json(['message' => 'Booker added successfully']);
    }
}