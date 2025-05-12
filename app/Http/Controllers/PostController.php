<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        // $posts = Post::all();
        $posts = Post::paginate(2);
        return view('post.allposts')->with(compact('posts'));
    }

    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'postname' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);
        
        if($validator->passes()){
            $img = $request->image;
            $ext = $img->getClientOriginalExtension();
            $imageName = time().".".$ext;
            $img->storeAs('images',$imageName,'public');


            $post = Post::create([
                'postname' => $request->postname,
                'description' => $request->description,
                'image' => $imageName,
            ]);

            if($post){
                return redirect()->route('posts.index')->with('post-success','Post created successifully');
            } 
        }else{
            return redirect()->route('posts.create')->withInput()->withErrors($validator);
        }
        
    }

    public function show(string $id)
    {
        $post = Post::find($id);
        return view('post.show')->with(compact('post'));
    }

    public function edit(string $id)
    {
        $post = Post::find($id);
        return view('post.update')->with(compact('post'));
        
    }

    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(),[
            'postname' => 'required',
            'description' => 'required',
        ]);
        
        $post = Post::find($id);

        if($validator->passes()){
            if($request->hasFile('image')){
                $image_path = public_path('/storage/images/').$post->image;
                if(file_exists($image_path)){
                    unlink($image_path);
                }


                $img = $request->image;
                $ext = $img->getClientOriginalExtension();
                $imageName = time().".".$ext;
                $img->storeAs('images',$imageName,'public');
                $post = Post::where('id',$id)->update([
                    'postname' => $request->postname,
                    'description' => $request->description,
                    'image' => $imageName,
                ]);

            }
            else{
                $post = Post::where('id',$id)->update([
                    'postname' => $request->postname,
                    'description' => $request->description,
                ]);
            }


            if($post){
                return redirect()->route('posts.index')->with('post-success','Post updated successifully');
            } 
        }else{
            return redirect()->route('posts.update')->withInput()->withErrors($validator);
        }
        
    }

    public function destroy(string $id)
    {
        $post = Post::find($id);
        $image_path = public_path('/storage/images/').$post->image;
        if(file_exists($image_path)){
            unlink($image_path);
        }

        $post = Post::find($id)->delete();
        return redirect()->route('posts.index');
    }
}
