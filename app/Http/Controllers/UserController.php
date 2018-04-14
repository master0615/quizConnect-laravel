<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;
use App\ProfilePhotoController;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $totalUsers = User::count();

        $totalFiltered = $totalUsers; 

        $page_number = empty($request->page_number) ? 0: $request->page_number;
        $page_size = empty($request->page_size) ? 5: $request->page_size;
        $order = empty($request->order) ? 'created_at' : $request->order;
        $dir = empty($request->dir) ? 'desc': $request->dir;
        $offset = $page_number * $page_size;

        if(empty($request->filter)) {            
            $users = User::offset($offset)
                         ->limit($page_size)
                         ->orderBy($order,$dir)
                         ->get();
        } else {
            $filter = $request->filter; 

            $users =  User::where(function ($query){
                                $query->where('active', 'NOT LIKE', 'delete');
                            })
                            ->where(function($query) use($filter) {
                                $query->where('id','LIKE',"%{$filter}%")
                                ->orWhere('first_name', 'LIKE',"%{$filter}%")
                                ->orWhere('last_name', 'LIKE',"%{$filter}%")
                                ->orWhere('email', 'LIKE',"%{$filter}%")
                                ->orWhere('role', 'LIKE',"%{$filter}%")
                                ->orWhere('active', 'LIKE',"%{$filter}%");    
                            })                                                
                            ->offset($offset)
                            ->limit($page_size)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = User::where(function ($query){
                                        $query->where('active', 'NOT LIKE', 'delete');
                                    })
                                    ->where(function($query) use($filter) {
                                        $query->where('id','LIKE',"%{$filter}%")
                                        ->orWhere('first_name', 'LIKE',"%{$filter}%")
                                        ->orWhere('last_name', 'LIKE',"%{$filter}%")
                                        ->orWhere('email', 'LIKE',"%{$filter}%")
                                        ->orWhere('role', 'LIKE',"%{$filter}%")
                                        ->orWhere('active', 'LIKE',"%{$filter}%");    
                                    })
                                    ->count();
        }


        if(!empty($users))
        {
            foreach ($users as $user)
            {
                if (isset($user->profile_photo) ) {
                    $user->path = $user->profile_photo->path();
                    $user->thumb = $user->profile_photo->thumb();
                }
            }
        }

        $response['data'] = $users;
        $response['page_number'] = $page_number;
        $response['page_size'] = $page_size;
        $response['total_counts'] = $totalFiltered;

        return response()->json($response, 201);
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
        $request->validate([
            'first_name' => 'required|max:20',
            'last_name' => 'required|max:20',
            'email' => 'required|email|unique:users|min:5',
            'password' => 'required|min:8|same:confirm_password',
            'confirm_password' => 'required|min:8|same:password'
        ], [
            'first_name.required' => "Please enter the user's first name.",
            'last_name.required' => "Please enter the user's last name.",
            'email.unique' => "The email address is already registered on the system",
            'password.required' => 'Please enter password',
            'confirm_password.required' => 'Please enter confirm password'
        ]);

        $user = new User();
        $user->first_name = title_case($request->first_name);
        $user->last_name = title_case($request->last_name);
        $user->email = strtolower($request->email);

        $user->role = $request->input('role', 'staff');
        $user->active = $request->input('active', 'active');
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
                'data' => $user,
                'message' => "User created.",
            ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (isset($user->profile_photo) ) {
            $user->path = $user->profile_photo->path();
            $user->thumb = $user->profile_photo->thumb();
        }

        return response()->json($user, 201);
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
        $user = User::findOrFail($id);
        if ($request->is_set_password) {
            $validator = $this->admin_credential_rules($request->all());

            if ($validator->fails()){
                return response()->json(array('error' => $validator->getMessageBag()->toArray()), 400);
            }

            $current_password = Auth::User()->password;
            //return response()->json([Hash::check($request->password, $current_password)], 400);

            if (!Hash::check($request->password, $current_password)) {
                return response()->json([
                    'message' => "The current password is incorrect!"
                ], 400);
            }
                
            $user->password = Hash::make($request->new_password);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        
        if ( isset($request->role) ) $user->role = $request->role;
        if ( isset($request->active) ) $user->active = $request->active;

        $user->save();

        return response()->json($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->active = "delete";

        $user->save();

        return response()->json([
                'message' => "User deleted"
            ], 204);
    }

    public function admin_credential_rules(array $data)
    {
        $messages = [
            'password.required' => 'Please enter current password',
            'new_password.required' => 'Please enter password',
        ];

        $validator = Validator::make($data, [
            'password' => 'required',
            'new_password' => 'required|same:confirm_password',
            'confirm_password' => 'required|same:new_password',     
        ], $messages);

        return $validator;
    }  
}
