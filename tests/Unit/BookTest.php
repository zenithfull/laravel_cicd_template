<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the fillable attributes of the Book model.
     */
    public function test_book_fillable_attributes(): void
    {
        $fillable = ['name', 'author', 'image', 'price'];
        $book = new Book();

        $this->assertEquals($fillable, $book->getFillable());
    }

    public function test_book_fillable_attributes_error_case(): void
    {
        $fillable = ['name', 'author', 'image', 'price', 'seller', 'note'];
        $book = new Book();

        $this->assertEquals($fillable, $book->getFillable());
    }
}
