<?php

namespace App\Http\Controllers\Api;
use Validator;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        return new ProductCollection(Product::orderBy('id','desc')->paginate(10));
    }

    public function show($id)
    {
        return new ProductCollection(Product::where('id', $id)->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'required||image:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $input = $request->all();
            $input['user_id'] = auth()->id();
            $image='';
            $file = $request->file('image'); 
            if($request->hasFile('image')){ 
                    $pathImage = 'public/uploads/products';
                    $image = imagePost($pathImage,$file);      
            }
            $input['image'] = $image;
            Product::create($input);
            return response()->json([
                'success' => true,
                'message' => 'Operation Successfull'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }


    }
    public function update(Request $request,$id)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'required||image:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
           $data = Product::find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No product found'
                ], 500);
            }
            $input = $request->all();
            $input['user_id'] = auth()->id();
            $image='';
            $file = $request->file('image'); 
            if($request->hasFile('image')){ 
                $pathImage = 'public/uploads/products';
                $image = imagePost($pathImage,$file); 
                if (file_exists($data->image)) {
                    \File::delete($data->image);
                }     
            }
            $input['image'] = $image;
            $data->update($input);
            return response()->json([
                'success' => true,
                'message' => 'Operation Successfull'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }


    }
    public function destroy($id)
    {
        try {
           $data = Product::find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No product found'
                ], 500);
            } 
            if (file_exists($data->image)) {
                \File::delete($data->image);
            } 
            $data->delete();    
            return response()->json([
                'success' => true,
                'message' => 'Operation Successfull'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }


    }

}
