<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Validator;
   
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
    
        return response()->json($roles);
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
            'description' => 'required'
        ]);
   
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error: ' . $validator->errors()
            ]);
        }
   
        $role = new Role;
        $role->role_name = $request->name;
        $role->description = $request->description;
        if($role->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'The Role successfully added'
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ]);
        }
   
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
  
        if (is_null($role)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ]);
        }

        return response()->json($role);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'id' => 'required',
            'role_name' => 'required',
            'description' => 'required'
        ]);
   
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error: ' . $validator->errors()
            ]);  
        }
        //$role = Role::findOrFail($request->id);
        $role->role_name = $request->role_name;
        $role->description = $request->description;
        $role->save();
   
        if($role->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Role successfully updated'
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ]);
        }
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //$role = Role::findOrFail($id);
        $get_users = $role->users();
        if(!empty($get_users)){
            $role->users()->delete();
        }
        if($role->delete()){
            return response()->json([
                'status' => 'success',
                'message' => 'Role successfully deleted'
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ]);
        }
    }
}