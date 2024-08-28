<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //this method will show product page
    public function index(){
        $products = Product::orderBy('created_at','DESC')->get(); 
        return view('list',[
            'products'=>$products
        ]);
    }
    //this method will show create product page
    public function create(){
        return view('create');
    }
    //this method will store a product page
    public function store(Request $request){
        $rules=[
            'name'=> 'required|min:5',
            'sku'=> 'required|min:3',
            'price'=> 'required|numeric'
        ];
        if($request->image!=""){
            $rules['image']='image';
        }
       $validator = Validator::make($request->all(),$rules);
       if($validator->fails()){
        return redirect()->route('create')->withInput()->withErrors($validator);
       }
       //store in dp
       $products=new Product();
       $products->name= $request->name;
       $products->sku= $request->sku;
       $products->price= $request->price;
       $products->description= $request->description;
       $products->save();
       if ($request->image != "") {
       //store image
       $image=$request->image;
       $ext=$image->getClientOriginalExtension();
       $imageName=time().'.'.$ext;
       //save image product directory
       $image->move(public_path('uploads/products'),$imageName);
       //save image in db
       $products->image=$imageName;
       $products->save();
       }
       return redirect()->route('index')->with('success','Product added successfully');
    }
    //this method will show edit product page
    public function edit($id){
        $products=Product::findOrFail($id);
        return view('edit',[
            'product'=> $products
        ]);
    }
    //this method will update product page
    public function update($id,Request $request){
        
        $products=Product::findOrFail($id);
        $rules=[
            'name'=> 'required|min:5',
            'sku'=> 'required|min:3',
            'price'=> 'required|numeric'
        ];
        if($request->image!=""){
            $rules['image']='image';
        }
       $validator = Validator::make($request->all(),$rules);
       if($validator->fails()){
        return redirect()->route('edit',$products->id)->withInput()->withErrors($validator);
       }
       //store in dp
       $products->name= $request->name;
       $products->sku= $request->sku;
       $products->price= $request->price;
       $products->description= $request->description;
       $products->save();
       if ($request->image != "") {
        //delete prev image
        File::delete(public_path('uploads/products/'.$products->image));
       //store image
       $image=$request->image;
       $ext=$image->getClientOriginalExtension();
       $imageName=time().'.'.$ext;
       //save image product directory
       $image->move(public_path('uploads/products'),$imageName);
       //save image in db
       $products->image=$imageName;
       $products->save();
    }
       return redirect()->route('index')->with('success','Product updated successfully');
    }
    //this method will delete product page
    public function destroy($id){
        $products=Product::findOrFail($id);
        //delete prev image
        File::delete(public_path('uploads/products/'.$products->image));
      //delete product from db
      $products->delete();
      return redirect()->route('index')->with('success','Product deleted successfully');
  
    }
}
