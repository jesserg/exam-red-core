<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;

   
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::with('role')->get();

        //$users = DB::table('users')
        //    ->join('roles', 'users.role', '=', 'roles.id')
        //    ->select('users.*', 'roles.role_name')
        //    ->get();

        return response()->json($users);
    
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error: ' . $validator->errors()
            ]);       
        }
        
        $check_email = User::where('email',$request->email)->first();
        if(!empty($check_email)){ 
            return response()->json([
                'status' => 'error',
                'message' => 'Duplicate email.'
            ]);   
        }

        $check_name = User::where('name',$request->name)->first();
        if(!empty($check_name)){ 
            return response()->json([
                'status' => 'error',
                'message' => 'Duplicate name.'
            ]);      
        }

        $input['password'] = bcrypt($request->password);
        $user = User::create($input);
   
        return response()->json([
            'status' => 'success',
            'message' => 'The Role successfully added'
        ]);
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
  
        if (is_null($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ]);
        } 
   
        return response()->json($user);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error: ' . $validator->errors()
            ]);   
        }
        /* $user = User::findOrFail($request->id); */
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if(!empty($request->password) && !empty($request->password)){
            if($request->password == $request->confirm_password && strlen($request->password) >= 8){
                //update password
                $user->password = bcrypt($request->password);
            }else if(strlen($request->password) < 8){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password must be 8 characters.'
                ]); 
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password does not match.'
                ]); 
            }
        }

        if($user->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'User successfully updated.'
            ]);
        }
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //$user = User::findOrFail($id);
   
        if($user->delete()){
            return response()->json([
                'status' => 'success',
                'message' => 'User successfully deleted'
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ]);
        }
    }
}