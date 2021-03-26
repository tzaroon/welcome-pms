<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Booker;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;

class BookersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request, $keyword)
    {
        $user = auth()->user();
        $bookers = Booker::where(['company_id' => $user->company_id])
            ->with('user')
            ->whereHas(
                'user', function($query) use($keyword){
                    $query->where('first_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('last_name', 'LIKE', "%{$keyword}%");
                }
            )
            ->get();
        return response()->json($bookers);
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