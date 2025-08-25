<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use DB;
use Hash;


class UserController extends Controller
{
/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/
public function index(Request $request)
    {
    $data = User::orderBy('id','DESC')->paginate(5);
    return view('users.show_users',compact('data'))
    ->with('i', ($request->input('page', 1) - 1) * 5);
    }
/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
public function create()
    {
    $roles = Role::pluck('name','name')->all();
    return view('users.Add_user',compact('roles'));
    }
/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|same:confirm-password',
        'roles_name' => 'required'
        ]);


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
        ->with('success','User created successfully');
    }
/**
* Display the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function show($id)
{
    $user = User::with(['subscription.plan', 'payments.plan'])->findOrFail($id);
    return view('users.show', compact('user'));
}

/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function edit($id)
    {
    $user = User::find($id);
    $roles = Role::pluck('name','name')->all();
    $userRole = $user->roles->pluck('name','name')->all();
    return view('users.edit',compact('user','roles','userRole'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        
        // إذا كانت كلمة المرور غير فارغة، نقوم بتشفيرها
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            // نستخدم Arr::except لإزالة 'password' من المصفوفة إذا كانت فارغة
            $input = Arr::except($input, ['password']);
        }

        $user = User::find($id);
        $user->update($input);
        
        // تحديث أدوار المستخدم
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }
/**
* Remove the specified resource from storage.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function destroy($id)
    {
    User::find($id)->delete();
    return redirect()->route('users.index')
    ->with('success','User deleted successfully');
    }

    public function changePassword(Request $request)
{
    // التحقق من صحة المدخلات
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:8|confirmed', // تأكد من تطابق كلمة المرور الجديدة مع تأكيد كلمة المرور
        'password_confirmation' => 'required', // التأكد من وجود تأكيد كلمة المرور
    ]);

    $user = auth()->user(); // الحصول على المستخدم الحالي من خلال التوثيق

    // التحقق من كلمة المرور الحالية
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withInput()->with('error', 'كلمة المرور الحالية غير صحيحة');
    }

    // التأكد من أن كلمة المرور الجديدة مختلفة عن الحالية
    if (Hash::check($request->password, $user->password)) {
        return back()->withInput()->with('error', 'كلمة المرور الجديدة لا يمكن أن تكون نفسها الحالية');
    }

    // تحديث كلمة المرور
    $user->password = Hash::make($request->password);
    $user->save();

    return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
}


}