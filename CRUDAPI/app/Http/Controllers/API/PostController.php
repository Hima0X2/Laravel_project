<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function indexForWeb() {
        $posts = Post::all();
        return view('allposts', ['posts' => $posts]);
    }    
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all(); // Fetch all posts
        return response()->json([
            'status' => true,
            'message' => 'All post data',
            'data' => $posts,
        ], 200);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow nullable image field
        ]);
    
        // Handle the image upload, if an image was provided
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $validatedData['image'] = $imageName;
        }
    
        // Create a new post with the validated data
        Post::create($validatedData);
    
        // Redirect to the allposts page with a success message
        return redirect()->route('allposts')->with('success', 'Post added successfully.');
    }
    public function edit(string $id)
    {
        $post = Post::find($id);
    
        if (!$post) {
            return redirect()->route('allposts')->with('error', 'Post not found.');
        }
        return view('post.edit', ['post' => $post]); // Assuming you have a view at resources/views/post/edit.blade.php
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

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Post Updated Successfully',
        //     'post' => $post,
        // ], 200);
        return redirect()->route('allposts')->with('success', 'Post updated successfully.');
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
