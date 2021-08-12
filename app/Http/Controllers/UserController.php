<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Validator;
class UserController extends Controller
{
    //create user account
    public function create_account(Request $req){
        try{
            $user=new User;
            $user->name=$req->name;
            $user->role=$req->roles;
            $user->email=$req->email;
            $user->password=Hash::make($req->pass);
            $user->save();
            return $user;
        }
        catch(QueryException $e){
             return ["message"=>"Email Already Exists"];
        }
    }
    //fetch all users account
    public function all_account(){
        $user=User::all();
        return $user;
    }
    //delete user account
    public function delete_account($id){
        $user=User::find($id);
        $user->delete();
        return(['message'=>'Deleted Succesfully']);
    }
    //update user account
    public function update_account($id,Request $req){
        $user=User::find($id);
        $user->name=$req->name;
        $user->role=$req->roles;
        $user->email=$req->email;
        if(Hash::check($req->old_pass,$user->password)){
            $user->password=Hash::make($req->pass);
            $user->save();
            return $user;  
        }
        else{
            return ['message'=>'Password Error'];
        }
    }
    //search user by name or email
    public function search_account($user_name){
        $user=User::where('name','like','%'.$user_name.'%')->get();
        return $user;
    }
    
    public function check_user(Request $req){
        $user=User::where(['email'=>$req->email])->first();
        if(!$user || !Hash::check($req->pass,$user->password)){
            return ["message"=>"Email or Password error"];
        }
        else{
            $req->session()->put('user',$user);
            return (["Login Succesfully"=>$user]);
        }
    }
    public function logout(){
        if(session()->has('user')){
            session()->pull('user');
            return ['message'=>'logged out succesfully'];
        }
        else{
            return ['message'=>'login first'];
        }
    }
    public function is_user_input_valid(Request $req){
     $rules=array(
         'name'=>'required|min:4|max:50',
         'roles'=>'required',
         'pass'=>'required|min:8|max:50',
         'email'=>'required|email|min:12|max:50'
                );
     $valid=Validator::make($req->all(),$rules);
     if($valid->fails()){
        return $valid->errors();
            }
     else{
        return ["message"=>"valid"];
        }
    }
    public function upload_pic(Request $req){
        try{
        $is_image=Validator::make($req->all(),['file'=>'required|mimes:png,jpg,jpeg,gif']);
        if($is_image->fails()){
            return response()->json(['errors'=>$is_image->errors()],401);
        }
        else{
        $file=$req->file('file')->store("Pics");
        return response()->json([
            "sucess"=>true,
            "message"=>"uploaded Succesfully",
            "file"=>$file
        ]);
        }
    }
     catch(PostTooLargeException $e){
         return response()->json(
             [
                 "message"=>$e->message()
             ]
         );
     }   
    }
}