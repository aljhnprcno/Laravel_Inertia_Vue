<?php

namespace App\Observers;

use App\Models\Book;

class BookObserver
{
    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {

    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
      if($book->image){
        if(file_exists(public_path($book->image))){
          unlink(public_path($book->image));
        }
      }
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}
