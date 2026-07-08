<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DataBazeController extends Controller
{


    public function store()
    {
        for ($i = 1; $i <= 10; $i++) {
            Post::create([
                'title' => "Пост $i",
                'description' => "Описание $i",
                'user_id' => 1,
            ]);
        }
        return 'Пост добавлен';
   }
   public function update(){
        $post = Post::find(3);

        $post -> update([
            'title' => "rsk",
            'description' => "srk ment",
        ]);
       dd('Метод вызвался');
   }
}
