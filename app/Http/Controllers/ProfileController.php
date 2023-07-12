<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\Role;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use DB;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $books = DB::table('user_profiles')
            ->join('roles', 'user_profiles.role_id', '=', 'roles.id')
            ->select('user_profiles.*', 'roles.name as role_name')
            ->get();

        $roles = Role::all();

        if ($request->ajax()) {
            $data = DB::table('user_profiles')
                ->join('roles', 'user_profiles.role_id', '=', 'roles.id')
                ->select('user_profiles.*', 'roles.name as role_name')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editBook">Edit</a>';
                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Profile', compact('books', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'role_id' => 'required',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = new UserProfile;
        $profile->name = $request->name;
        $profile->email = $request->email;
        $profile->phone = $request->phone;
        $profile->role_id = $request->role_id;

        if ($request->hasFile('profile_image')) {
    $image = $request->file('profile_image');
    $imageName = time() . '.' . $image->getClientOriginalExtension();
    $image->move(public_path('images'), $imageName);
    $profile->profile_image = $imageName;
}


        $profile->save();
        
        return response()->json(['success' => 'Profile created successfully.']);
    }

    public function fetch(Request $request)
    {
        $profile = UserProfile::findOrFail($request->profile_id);

        return response()->json($profile);
    }

   
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'role_id' => 'required',
        'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $profile = UserProfile::findOrFail($id);
    $profile->name = $request->name;
    $profile->email = $request->email;
    $profile->phone = $request->phone;
    $profile->role_id = $request->role_id;

    if ($request->hasFile('profile_image')) {
        $image = $request->file('profile_image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        File::delete(public_path('profiles/' . $profile->profile_image));
        $profile->profile_image = $imageName;
    }

    $profile->save();

    return response()->json(['success' => 'Profile updated successfully.']);
}
    public function deletedata(Request $request)
    {
        
        $profile = UserProfile::findOrFail($request->profile_id);
        Storage::delete('public/profiles/' . $profile->profile_image);
        $profile->delete();

        return response()->json(['success' => 'Profile deleted successfully.']);
    }
}
