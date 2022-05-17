<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Posts;

class AccountsController extends Controller
{
    public function signup(Request $request){
        if ($request->isMethod('GET')){
            return view('accounts.signup');
        }
        else if ($request->isMethod('POST')){
            $validator = $request->validate([
                'name' => 'required',
                'email' => 'required|unique:users,email|Regex:/[a-zA-Z0-9-.]+@[a-zA-Z0-9.]+/',
                'password' => 'required|min:5|confirmed'
            ]);

            $name = $request->get('name');
            $email = $request->get('email');
            $password = Hash::make($request->get('password'));

            $user = User::create(['name' => $name, 'email'=> $email, 'password' => $password]);

            Auth::login($user);
            return redirect(route('account_detail', ['id'=> Auth::user()->id]));
        }
    }


    public function login(Request $request){
        if ($request->isMethod('GET')){
            return view('accounts.login');
        }
        else if ($request->isMethod('POST')){
            $validator = $request->validate([
                'email' => 'required|exists:users,email|Regex:/[a-zA-Z0-9-.]+@[a-zA-Z0-9.]+/',
                'password' => 'required|min:5'
            ]);

            $email = $request->get('email');
            $password = $request->get('password');
            $User = new User();
            $User->email = $email;
            $User->password = $password;

            $user = $User->findUserByEmailAndPassword();
            
            if ($user){
                Auth::login($user);
                $user->last_login_at = Carbon::now();
                $user->save();

                return view('welcome');
            }
            else{
                return view('accounts.login');
            }
            
            
        }
    }


    public function logout(Request $request){
        Auth::logout();
        return view('welcome');
    }


    public function detail(Request $request){
        $user = User::where('id', $request->id)->first();
        $posts = Posts::where('user_id', $request->id)->get();
        return view('accounts.detail', ["user" => $user, "posts" => $posts]);
    }


    public function update(Request $request){
        if ($request->isMethod('GET')){
            if (Auth::user()->id == $request->id){
                $user = User::where('id', Auth::user()->id)->first();
                return view('accounts.update');
            }
            else{
                return redirect(route('account_detail', ['id'=>Auth::user()->id]));
            }
        }
        else if ($request->isMethod('POST')){
            $validator = $request->validate([
                'name' => 'required'
            ]);

            $name = $request->get('name');
            if ($request->get('biograph')){
                $biograph = $request->get('biograph');
            }
            else{
                $biograph = "";
            }

            $user = User::where('id', Auth::user()->id)->first();
            $user->name = $name;
            $user->biograph = $biograph;

            $user->save();

            return redirect(route('account_detail', ['id'=>Auth::user()->id]));
        }
    }


    public function delete(Request $request){
        if (Auth::user()->id == $request->id){
            if ($request->isMethod('GET')){
                return view('accounts.delete', ['id'=>$request->id]);
            }
            else if ($request->isMethod('POST')){
                $user = User::where('id', $request->id)->delete();
                return redirect(route('welcome'));
            }
        }
        else{
            return redirect(route('account_detail', ['id' => $request->id]));
        }
        
    }
}
