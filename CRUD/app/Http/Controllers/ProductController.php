<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // This method will show product page
    public function index() {
        $products = Product::orderBy('created_at', 'DESC')->get(); 
        return view('list', [
            'products' => $products
        ]);
    }

    // This method will show create product page
    public function create() {
        return view('create');
    }

    // This method will store a product
    public function store(Request $request) {
        // Convert Bengali numerals to Arabic numerals for the price
        $request->merge(['price' => $this->convertBengaliToArabic($request->price)]);

        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric'
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('create')->withInput()->withErrors($validator);
        }

        // Store in DB
        $products = new Product();
        $products->name = $request->name;
        $products->sku = $request->sku;
        $products->price = $request->price; // Arabic numeral
        $products->description = $request->description;
        $products->save();

        if ($request->image != "") {
            // Store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            // Save image in product directory
            $image->move(public_path('uploads/products'), $imageName);
            // Save image in DB
            $products->image = $imageName;
            $products->save();
        }

        return redirect()->route('index')->with('success', 'Product added successfully');
    }

    // This method will show edit product page
    public function edit($id) {
        $products = Product::findOrFail($id);
        return view('edit', [
            'product' => $products
        ]);
    }

    // This method will update product
    public function update($id, Request $request) {
        $products = Product::findOrFail($id);

        // Convert Bengali numerals to Arabic numerals for the price
        $request->merge(['price' => $this->convertBengaliToArabic($request->price)]);

        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric'
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('edit', $products->id)->withInput()->withErrors($validator);
        }

        // Store in DB
        $products->name = $request->name;
        $products->sku = $request->sku;
        $products->price = $request->price; // Arabic numeral
        $products->description = $request->description;
        $products->save();

        if ($request->image != "") {
            // Delete previous image
            File::delete(public_path('uploads/products/' . $products->image));
            // Store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            // Save image in product directory
            $image->move(public_path('uploads/products'), $imageName);
            // Save image in DB
            $products->image = $imageName;
            $products->save();
        }

        return redirect()->route('index')->with('success', 'Product updated successfully');
    }

    // This method will delete product
    public function destroy($id) {
        $products = Product::findOrFail($id);
        // Delete previous image
        File::delete(public_path('uploads/products/' . $products->image));
        // Delete product from DB
        $products->delete();
        return redirect()->route('index')->with('success', 'Product deleted successfully');
    }

    // Function to convert Bengali numerals to Arabic numerals
    private function convertBengaliToArabic($str) {
        $arabicNumbers = [
            '০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4',
            '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9'
        ];
        return str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $str);
    }
}
