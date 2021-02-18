<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Comprobar que el usuario este autenticado
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        if ($checkToken) {

            $user = $jwtAuth->checkToken($token, true);

            $validate = \Validator::make($request->all(), [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users,'.$user->sub
            ]);

            $user_update = User::where('id', $user->sub)->update($request->all());


            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user_update
            );

        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no está identificado.'
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function register(Request $request) {

        $validate = Validator::make($request->all(),[
            'name' =>'required|alpha',
            'surname' =>'required|alpha',
            'email' =>'required|email|unique:users',
            'password' =>'required',
        ]);

        if ($validate->fails()){
            $data = array(
                'status'  => 'error',
                'code'    => 404,
                'message' => 'El usuario no se ha creado',
                'errors' => $validate->errors()
            );
        } else {

            $user = new User();
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->email = $request->email;
            // $user->password = Hash::make($request->password);
            $user->password = hash('sha256', $request->password);
            $user->role = 'ROLE_USER';
            $user->save();

            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'message' => 'El usuario se ha creado correctamente'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {

        $jwtAuth = new \JwtAuth();

        $email = $request->email;
        $password = $request->password;

        $validate = Validator::make($request->all(),[
            'email' =>'required|email',
            'password' =>'required',
        ]);

        if ($validate->fails()){
            $signup = array(
                'status'  => 'error',
                'code'    => 404,
                'message' => 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        } else {
            $pwd = hash('sha256', $password);
            $signup = $jwtAuth->signup($email, $pwd);

            if (!empty($getToken)) {
                $signup = $jwtAuth->signup($email, $pwd, true);
            }

        }

        return response()->json($signup, 200);
    }

}
