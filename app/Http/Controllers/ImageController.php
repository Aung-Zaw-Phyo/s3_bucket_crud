<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    
    public function index () {
        return view('index', [
            'images' => Image::all()
        ]);
    }

    // "https://my-file-upload-test.s3.ap-northeast-1.amazonaws.com/images/QgOK0P7gpJ0fo8apmi9Y47hJctGU9sbtuVCS9B5j.png"
    public function upload (Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif']
        ]);

        if($validator->fails()){
            return back()->with('info', $validator->messages()->first());
        }

        try {
            $image_name = time().'.'.$request->image->extension();  
            $product_id =  Image::all()->count() == 0 ?  1 : Image::all()->last()->id + 1;
            $path = Storage::disk('s3')->put("images/$product_id", $request->image);
            $path = Storage::disk('s3')->url($path);
            $arr = explode('images/', $path);
            $path = $arr[1];
            $image = new Image();
            $image->product_id = $product_id;
            $image->images = $path;
            $image->save();

            return back()->with('success', $path);

        } catch (\Throwable $th) {
            return back()->with('info', 'Server Error!');
        }
    }

    public function delete (Request $request, $id) {
        try {
            $path = $request->path;
            Storage::disk('s3')->delete("images/".$path);
            $image = Image::find($id);
            $image->delete();
            return back()->with('info', 'Exit');
        } catch (\Throwable $th) {
            return back()->with('info', 'not found');
        }
    }

    public function update (Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'oldImg' => ['required'],
            'newImg' => ['required'],
            'product_id' => ['required']
        ]);

        if($validator->fails()){
            return back()->with('info', $validator->messages()->first());
        }

        // delete from s3 
        $oldImg = $request->oldImg;
        Storage::disk('s3')->delete("images/".$oldImg);

        // update from database 
        

        try {
            $image_name = time().'.'.$request->newImg->extension();  
     
            $path = Storage::disk('s3')->put("images/$request->product_id", $request->newImg);
            $path = Storage::disk('s3')->url($path);
            $arr = explode('images/', $path);
            $path = $arr[1];
            $image = Image::find($id);
            $image->images = $path;
            $image->update();
            return back()->with('info', 'Update successsfully!');
        } catch (\Throwable $th) {
            return back()->with('info', 'Server Error!');
        }
    }


    public function images () {
        $images = Image::all();
        return view('images', [
            'images' => $images
        ]);
    }

}
