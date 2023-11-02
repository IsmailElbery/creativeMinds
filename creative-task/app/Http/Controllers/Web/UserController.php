<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\AddUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    //get users
    public function users()
    {
        $users = User::all();
        return view('users.users', compact('users'));
    }

    //get add user
    public function addUserForm()
    {
        return view('users.add-user');
    }

    //add user
    public function addUser(AddUserRequest $request)
    {
        //create the user
        $user = new User();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        $user->save();

        //redirect to the users page
        return redirect()->route('users')->with('success', 'User added successfully');
    }

    //get edit user
    public function editUserForm($id)
    {
        $user = User::find($id);
        return view('users.edit-user', compact('user'));
    }

    //edit user
    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        if($request->mobile != $user->mobile){
            $user->mobile = $request->mobile;
        }
        if ($request->password){
            $user->password = bcrypt($request->password);
        }
        $user->save();

        //redirect to the users page
        return redirect()->route('users')->with('success', 'User updated successfully');
    }

    //activate user
    public function activateUser($id)
    {
        $user = User::find($id);
        $user->active = true;
        $user->save();
        return redirect()->back()->with('success', 'User activated successfully');
    }

    //delete user
    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }

}
