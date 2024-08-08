<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Book;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_books()
    {
        $books = Book::factory()->count(3)->create();

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('products');
        $response->assertViewHas('books', $books);
    }

    public function test_bookCart_method_returns_cart_view()
    {
        $response = $this->get('/shopping-cart');

        $response->assertStatus(200);
        $response->assertViewIs('cart');
    }

    public function test_addBooktoCart_method_adds_book_to_cart()
    {
        /** @var Book $book */
        $book = Book::factory()->create();

        $response = $this->get('/book/' . $book->id);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Book has been added to cart!');

        $cart = session('cart');
        $this->assertArrayHasKey($book->id, $cart);
        $this->assertEquals($book->name, $cart[$book->id]['name']);
        $this->assertEquals(1, $cart[$book->id]['quantity']);
        $this->assertEquals($book->price, $cart[$book->id]['price']);
        $this->assertEquals($book->image, $cart[$book->id]['image']);
    }

}
