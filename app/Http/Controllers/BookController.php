<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;
use Spatie\Backtrace\File;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $data = Book::query()->paginate(20);

      return Inertia::render('books', [
        'data' => $data
      ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
      Validator::make($request->all(), [
        'title' => 'required',
        'author' => 'required'
      ])->validate();

      Book::create($request->all());

      $this->processImage($request);

      return redirect()->back()
        ->with('message', 'Book created');
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
      Validator::make($request->all(), [
        'title' => 'required',
        'author' => 'required'
      ])->validate();

      $book->update($request->only(['title', 'author']));

      $this->processImage($request, $book);

      return redirect()->back()
        ->with('message', 'Book Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
      $book->delete();
      return redirect()->back()
      ->with('message', 'Book Deleted');
    }

    public function upload(Request $request)
    {
        if($request->hasFile('imageFilepond'))
        {
            return $request->file('imageFilepond')->store('uploads/books', 'public');
        }
        return '';
    }

    public function processImage(Request $request, Book $book = null)
    {
      if($image = $request->get('image')){
        $path = storage_path('app/public/' . $image);
        if(file_exists($path)){
          copy($path, public_path($image));
          unlink($path);
        }
      }

      if($book){
        if(!$request->get('image'))
        {
          if($book->image)
          {
            if(file_exists(public_path($book->image))){
              unlink(public_path($book->image));
            }
          }
        }
        $book->update([
          'image' => $request->get('image')
        ]);
      }
    }

    public function uploadRevert(Request $request){
      if($image = $request->get('image')){
        $path = storage_path('app/public/' . $image);
        if(file_exists($path)){
          unlink($path);
        }
      }
    }

}
