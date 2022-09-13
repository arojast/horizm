<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use App\Models\Post;

class UsersController extends Controller
{
    public function import(){
        //hacer petición de usuarios
        $users = Http::get('https://jsonplaceholder.typicode.com/users');
        
        foreach($users->json() as $user){
            //validar si el id de usuario existe
            $userId = User::find($user['id']);
            
            if($userId === NULL){
                //validar si hay post asociados
                $userPost = Post::where('user_id',$user['id'])->first();
                
                if($userPost !== NULL){
                    //guardar usuario
                    $row = new User;
                    $row->id = $user['id'];
                    $row->nombre = $user['name'];
                    $row->email = $user['email'];
                    $row->ciudad = $user['address']['city'];
                    $row->save();
                }
            }
        }

        return response()->json([
            'message' => '¡Usuarios Importados correctamente!',
        ]);
    }

    public function index(){
        $users = User::all();
        $usersOrder = [];

        foreach($users as $user){
            $posts = $user->posts;
            
            $rating = 0;
            
            foreach($posts as $post){
                $rating += (int)$post->rating;
            }
            
            $media = $rating/$posts->count();
            $usersOrder[$user->id] = $media;
        }

        //ordernar arreglo de mayor a menor rating
        arsort($usersOrder);

        //preparar salida de datos
        $data = [];
        foreach($usersOrder as $key => $u){
            $user = User::find($key);
            $array = [
                'id' => $key,
                'nombre' => $user->nombre,
                'email' => $user->email,
                'ciudad' => $user->ciudad,
            ];

            $posts = [];
            foreach($user->posts as $post){
                $posts[] = [
                    'id' => $post->id,
                    'user_id' => $post->user_id,
                    'body' => $post->body,
                    'title' => $post->title,
                ];
            }

            $array['posts'] = $posts;
            $data[] = $array;
        }

        return response()->json([
            'users' => $data,
        ]);
    }
}
