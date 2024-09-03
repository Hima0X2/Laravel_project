<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['post'] = Post::all();
        return response()->json([
            'status' => true,
            'message' => 'All post data',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $imageName = time() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('/uploads/'), $imageName);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Image upload failed',
            ], 500);
        }

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post Created Successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Your Single Post',
            'data' => $post,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'nullable|mimes:png,jpg,jpeg,gif',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
            ], 404);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = public_path('/uploads/');
            $oldImagePath = $path . $post->image;

            // Delete old image if it exists
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $img = $request->file('image');
            $imageName = time() . '.' . $img->getClientOriginalExtension();
            $img->move($path, $imageName);
        } else {
            $imageName = $post->image;
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post Updated Successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
            ], 404);
        }

        $imagePath = public_path('/uploads/' . $post->image);

        // Delete image if it exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post Deleted Successfully',
        ], 200);
    }
}
