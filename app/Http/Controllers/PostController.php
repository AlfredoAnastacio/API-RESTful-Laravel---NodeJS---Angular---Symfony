<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Helpers\JwtAuth;
use App\Post;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'getImage']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all()->load('category');

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'post' => $posts
        ], 200);
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
        if (!empty($request->all())) {

            $user = $this->getIdentity($request);

            $validate = Validator::make($request->all(),[
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required',
                'image' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardado el post, faltan datos'
                ];
            } else {
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $request->category_id;
                $post->title = $request->title;
                $post->content = $request->content;
                $post->image = $request->image;
                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'post' => $post
                ];
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Envía los datos correctamente'
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (is_object($post)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La entrada no existe'
            ];
        }

        return response()->json($data, $data['code']);
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
    public function update(Request $request, $id)
    {

        $user = $this->getIdentity($request);

        $post = Post::where('id', $id)
                        ->where('user_id', $user->sub)
                        ->first();


        if (!empty($request->all())) {
            $validate = Validator::make($request->all(),[
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Imposible actualizar, faltan datos.'
                ];
            } else {

                unset($request->id);
                unset($request->user_id);
                unset($request->created_at);

                $user = $this->getIdentity($request);

                $post = Post::where('id', $id)
                        ->where('user_id', $user->sub)
                        ->first();

                if (!empty($post) && is_object($post)) {

                    $post->update($request->all());

                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'post' => $post,
                        'changes' => $request->all(),
                    ];
                }
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Debe enviar los datos correctamente.'
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $user = $this->getIdentity($request);

        $post = Post::where('id', $id)
                        ->where('user_id', $user->sub)
                        ->first();

        if (!empty($post)) {

            $post->delete();

            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'success',
                'message' => 'El post no existe.'
            ];
        }

        return response()->json($data, $data['code']);
    }

    private function getIdentity($request){

        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }

    public function upload(Request $request) {

        $image = $request->file('file0');

        $validate = Validator::make($request->all(),[
            'file0' => 'required|image|mimes:png,jpg,jpeg,gif'
        ]);

        if (!$image || $validate->fails()) {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen.'
            ];
        } else {
            $image_name = time().$image->getClientOriginalName();

            \Storage::disk('images')->put($image_name, \File::get($image));

            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function getImage($filename) {

        $isset = \Storage::disk('images')->exists($filename);

        if ($isset) {
            $file = \Storage::disk('images')->get($filename);

            return new Response($file, 200);
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'La imagen no existe.'
            ];
        }
        return response()->json($data, $data['code']);
    }
}
