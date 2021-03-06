<?php 
namespace Classifieds\Http\Controllers;
use Classifieds\User;
use Classifieds\Role;
//use Request;
use Input;
use Redirect;
use Illuminate\Http\Request;
use Classifieds\Http\Requests\UserSignupRequest;

class UsersController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /users
	 *
	 * @return Response
	 */
    
        public function __construct(User $user) {
            $this->middleware('auth', ['except' => ['create','store']]);
            $this->middleware('role', ['only' => ['index']]);
            $this->user = $user;
        }
	public function index()
	{
//            return "USERS";
            $users = $this->user->all();
            return view('users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /users/create
	 *
	 * @return Response
	 */
	public function create()
	{
            return view('users.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /users
	 *
	 * @return Response
	 */
	public function store(UserSignupRequest $request)
	{
            $input=$request->all();
//            $this->validate($request, $this->user->rules);
            $this->user->fill($input);
            unset($this->user->password_confirmation); //Prevent the attempt of adding this field to the table.
            $this->user->save();
            
            //Add member role
            $role = Role::whereRole('user')->first();
            $this->user->assignRole($role);
            
            
//            $user=$this->user->toArray();
            
            //Send welcome email
//            Mail::queue('emails.welcome',$user,function($message) use($user) {
//                $message->to($user['email'],$user['fname'].$user['lname'])
//                        ->subject('Welcome to CTI.');
//            });
//            
            if(\Illuminate\Support\Facades\Auth::attempt($request->only('email','password'))){
                flash("Thank you, You have successfully registered.");
                return redirect("post");
            }
        
	}

	/**
	 * Display the specified resource.
	 * GET /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /users/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
        
        public function posts($id){
            $user = User::find($id);
            $posts = $user->posts;

            return view('posts.index', compact('posts'));
        }

}