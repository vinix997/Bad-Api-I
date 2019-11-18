<?php

namespace App\Http\Controllers;

use App\User;
use App\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profile;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function dataUsers()
    {
        $users = User::with('profile')->latest()->paginate(20);

        $data = [
            'code' => 1,
            'message' => 'Wow',
            'data' => $users
        ];
        return response()->json($data, 200);
    }
    
    public function addUser(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        $profile = new UserProfile();
        $profile->user_id = $user->id;
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->gender = $request->gender;
        $profile->save();

        $data = [
            'code' => 1,
            'message' => 'User berhasil ditambahkan',
            'data' => []
        ];
        
        return response()->json($data, 200);
    }

    public function profileUser($userId)
    {
        $user = User::with('profile')->find($userId);

        $data = [
            'code' => 1,
            'message' => 'User found',
            'data' => $user
        ];

        return response()->json($data, 200);

    }

    public function updateProfileUser(Request $request, $userId)
    {
        $profile = UserProfile::where('user_id',$userId)->first();
        $user = User::where('id',$userId)->first();
        if($request->username)
        {
            $user->username = $request->username;
        }
        if($request->email)
        {
            $user->email = $request->email;
        }
        if($request->first_name)
        {
            $profile->first_name = $request->first_name;
        }
        if($request->last_name)
        {
            $profile->last_name = $request->last_name;
        }
        if($request->gender)
        {
            $profile->gender = $request->gender;
        }
        $user->save();
        $profile->save();

        $data = [
            'code' => 1,
            'message' => 'Updated',
            'data' => []
        ];

        return response()->json($data, 200);
        
    }

    public function changeGenderToMale(Request $request, $userId)
    {
        try{
            DB::beginTransaction();
            $user = UserProfile::where('user_id',$userId)->first();
            $user->update(['gender'=>config('gender.male')]);
            $data = [
                'code' => 1,
                'message' => 'Transgender Succeded',
                'data' => []
            ];
            DB::commit();

        }catch (Exception $e)
        {
            Log::error($e);
            DB::rollback();

            $data = [
                'code' => 2,
                'message' => Profile::FEMALE,
                'data' => []
            ];
            
        }
        return response()->json($data, 200);
    }
    public function changeGenderToFemale(Request $request, $userId)
    {
        try{
            DB::beginTransaction();
            $profile = UserProfile::where('user_id',$userId)->first();
            $profile->update(['gender'=>config('gender.female')]);
            $data = [
                'code' => 1,
                'message' => 'Transgender Succeded',
                'data' => []
            ];
            DB::commit();

        }catch(Exception $e)
        {
            Log::error($e);
            DB::rollBack();
            $data = [
                'code' => 2,
                'message' => Profile::MALE,
                'data' => []
            ];

        }
        return response()->json($data, 200);
    }

    public function deleteUser(Request $request, $userId)
    {
        try{
            DB::beginTransaction();
            $profile = UserProfile::where('user_id',$userId)->first();
            $user = User::where('id',$userId)->first();
            $profile->delete();
            $user->delete();
            
            $data =[
                'code' => 1,
                'message' => 'Delete Succeded',
                'data' => []
            ];
            DB::commit();
        }catch(Exception $e)
        {
            Log::error($e);
            DB::rollback();
            $data = [
                'code' => 1,
                'message' => 'Zannen',
                'data' => []
            ];
        }
        return response()->json($data, 200);

    }
    //
}
