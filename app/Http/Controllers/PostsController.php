<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Post;
use App\Models\User;

class PostsController extends Controller
{
    public function import(){
        //hacer petición de post
        $posts = Http::get('https://jsonplaceholder.typicode.com/posts');
        
        //recorrer los primero 50 posts
        for($i=0;$i<50;$i++){
            //obtener post
            $post = $posts->json()[$i]; 
            //validar si el id de post existe
            $postId = Post::find($post['id']);
            
            if($postId === NULL){
                //guardar post por que no existe
                $row = new Post;
                $row->id = $post['id'];
                $row->user_id = $post['userId'];
                $row->title = $post['title'];
                $row->body = $post['body'];
                
                $rating = (int)count(explode(' ',$row->title))*2;
                //quitar espacios
                $body = str_replace(' ','|',$row->body);
                //quitar saltos de linea
                $body = str_replace(PHP_EOL,'|',$body);
                $rating2 = (int)count(explode('|',$body));
                
                $row->rating = $rating+$rating2;
                $row->save();
            } else {
                //actualizar post existente
                //echo "modifica ".$postId->id;
                $postId->body = $post['body'];
                $postId->save();
            }
        }

        return response()->json([
            'message' => 'Post Importados correctamente!',
        ]);
    }

    public function top(){
        $data = [];
        $users = User::all();

        foreach($users as $user){
            $post = Post::where('user_id',$user->id)
                ->orderBy('rating','DESC')
                ->first();

            $data[] = [
                'id_post' => $post->id,
                'body' => $post->body,
                'title' => $post->title,
                'user_id' => $post->user_id,
                'user_nombre' => $user->nombre,
            ];
        }

        return response()->json([
            'post' => $data,
        ]);
    }

    public function show($id){
        $post = Post::find($id);

        if($post !== NULL){
            $data = [
                'id' => $post->id,
                'body' => $post->body,
                'title' => $post->title,
                'user_nombre' => $post->user->nombre,
            ];
           
            return response()->json([
                'post' => $data,
            ]);
        } else {
            return response('¡No existe el post!',404);
        }
        
    }
}
