<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function update(Request $request) {
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

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array,[
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
            $user->name = $params_array['name'];
            $user->surname = $params_array['surname'];
            $user->email = $params_array['email'];
            $user->password = hash('sha256', $params_array['password']);
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

         // Recibir datos por post
         $json = $request->input('json', null);
         $params = json_decode($json);
         $params_array = json_decode($json, true);

         $validate = Validator::make($params_array,[
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
            $pwd = hash('sha256', $params->password);
            $signup = $jwtAuth->signup($params->email, $pwd);

            if (!empty($params->getToken)) {
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }

        return response()->json(['Token' => $signup, 'data' => $jwtAuth->signup($params->email, $pwd, true)], 200);
    }

    public function upload(Request $request) {

        $image = $request->file('file0');

        $validate =  \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:png,jpg'
        ]);

        // return response()->json($image);

        if (!$image || $validate->fails()) {
            $data = array(
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Error al subir imagen.'
            );

        } else {

            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));

            $data = array(
                'code'    => 200,
                'status' => 'success',
                'image'  => $image_name
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getImage($filename) {

        $isset = \Storage::disk('users')->exists($filename);

        if ($isset) {
            $file = \Storage::disk('users')->get($filename);

            return new response($file, 200);

        } else {
            $data = array(
                'code'    => 404,
                'status' => 'error',
                'image'  => 'La imagen no existe.'
            );

            return response()->json($data, $data['code']);
        }

    }

    public function detail($id) {

        $user = User::find($id);

        if (is_object($user)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        } else {
            $data = array(
                'code'    => 400,
                'status' => 'eror',
                'image'  => 'El usuario no existe.'
            );
        }

        return response()->json($data, $data['code']);
    }

}
