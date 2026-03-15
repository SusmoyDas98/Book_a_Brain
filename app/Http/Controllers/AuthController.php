<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        // dd($request->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());

        $action = $request->input('action_type');
        if ($action === "login"){
            $credentials = $request->validate([
                'email' => "required|email",
                'password' =>'required|min:2'
            ]);
            if (Auth::attempt($credentials)){
                $request->session()->regenerate();
                // return redirect()->to('select_role_redirect');
                return "Login Successful !!! Welcome back !!!";
            }
            return back()->withErrors(["error_login"=>"Login Failed !!! Invalid Credentials !!!"])->withInput();
        }
        elseif ($action === "signup"){
            $credentials = $request->validate([
                'name' => "required",
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:2|confirmed'
            ]);
            // dd($credentials);
            $user = User::create([
                'name' =>$credentials['name'],
                'email' => $credentials['email'],
                'password' =>  bcrypt($credentials['password']) 
            ]);
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('select_role_redirect');

        return back()->withErrors(["error_signup"=>"Signup Failed !!! Invalid Credentials !!!"])->withInput();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
