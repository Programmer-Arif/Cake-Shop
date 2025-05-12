<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PostControllerHTTP extends Controller
{
    public function index(){
        $response = Http::withToken('37|RClPRb5pgFr3WIeAi9nZhZrtgGfJjA35VsCgbbzS47cbaf45')->get('http://127.0.0.1:8000/api/posts');
        if($response->successful()){
            $data = $response->json();
            dd($data);
        }
        return 'API request failed';
    }

    
    public function store(Request $request){
        // $response = Http::withToken('37|RClPRb5pgFr3WIeAi9nZhZrtgGfJjA35VsCgbbzS47cbaf45')->asMultipart()
        // ->attach(
        //     'image',
        //     fopen(storage_path('app/public/sample.jpg'), 'r'),
        //     'sample.jpg'
        // )
        // ->post('http://127.0.0.1:8000/api/posts', [
        //     ['name' => 'title', 'contents' => 'My Post Title'],
        //     ['name' => 'description', 'contents' => 'This is the description'],
        // ]);

        $image = $request->file('image');

        $response = Http::withToken('37|RClPRb5pgFr3WIeAi9nZhZrtgGfJjA35VsCgbbzS47cbaf45')->asMultipart()
            ->attach(
                'image', 
                fopen($image->getRealPath(), 'r'), 
                $image->getClientOriginalName()
            )
            ->post('http://127.0.0.1:8000/api/posts', [
                ['name' => 'title', 'contents' => $request->input('title')],
                ['name' => 'description', 'contents' => $request->input('description')],
            ]);

        if ($response->successful()) {
            $data = $response->json();
            dd($data);
        }

        return 'API request failed';
        }


        public function singlepost(string $id){
            $response = Http::withToken('37|RClPRb5pgFr3WIeAi9nZhZrtgGfJjA35VsCgbbzS47cbaf45')->get('http://127.0.0.1:8000/api/posts/'.$id);
            if($response->successful()){
                $data = $response->json();
                dd($data);
            }
            return 'API request failed';
        }



        public function updatepost(string $id){
            $response = Http::withToken('37|RClPRb5pgFr3WIeAi9nZhZrtgGfJjA35VsCgbbzS47cbaf45')->get('http://127.0.0.1:8000/api/posts/'.$id);
            if($response->successful()){
                $data = $response->json();
                $post=$data['data'];
                return view('api.updatepost')->with(compact('post'));
            }
            else{
                return 'API request failed';
            }
            
        }
        public function update(Request $request, string $id){
            $image = $request->file('image');

            $response = Http::withToken('37|RClPRb5pgFr3WIeAi9nZhZrtgGfJjA35VsCgbbzS47cbaf45')->asMultipart()
                ->attach(
                    'image', 
                    fopen($image->getRealPath(), 'r'), 
                    $image->getClientOriginalName()
                )
                ->post('http://127.0.0.1:8000/api/posts/'.$id, [
                    ['name' => '_method', 'contents' => 'PUT'],
                    ['name' => 'title', 'contents' => $request->input('title')],
                    ['name' => 'description', 'contents' => $request->input('description')],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                dd($data);
            }

            return 'API request failed';
        }


        public function deletepost(string $id){
            $response = Http::withToken('37|RClPRb5pgFr3WIeAi9nZhZrtgGfJjA35VsCgbbzS47cbaf45')->get('http://127.0.0.1:8000/api/posts/'.$id);
            if($response->successful()){
                $data = $response->json();
                $post=$data['data'];
                return view('api.deletepost')->with(compact('post'));
            }
            else{
                return 'API request failed';
            }
            
        }

        public function delete(string $id){

            $response = Http::withToken('37|RClPRb5pgFr3WIeAi9nZhZrtgGfJjA35VsCgbbzS47cbaf45')->delete('http://127.0.0.1:8000/api/posts/'.$id);

            if ($response->successful()) {
                $data = $response->json();
                dd($data);
            }

            return 'API request failed';
        }




}
