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

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Book has been added to cart!');

        $cart = session('cart');
        $this->assertArrayHasKey($book->id, $cart);
        $this->assertEquals($book->name, $cart[$book->id]['name']);
        $this->assertEquals(1, $cart[$book->id]['quantity']);
        $this->assertEquals($book->price, $cart[$book->id]['price']);
        $this->assertEquals($book->image, $cart[$book->id]['image']);
    }

    public function test_updateCart_method_updates_cart_quantity()
    {

        /** @var Book $book */
        $book = Book::factory()->create();
        $cart = [$book->id => [
            'name' => $book->name,
            'quantity' => 1,
            'price' => $book->price,
            'image' => $book->image,
        ]];
        session(['cart' => $cart]);

        $response = $this->patch('/update-shopping-cart', [
            'id' => $book->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertSessionHas('success', 'Book added to cart.');

        $updatedCart = session('cart');
        $this->assertEquals(2, $updatedCart[$book->id]['quantity']);
    }

    public function test_deleteProduct_method_deletes_book_from_cart()
    {
        /** @var Book $book */
        $book = Book::factory()->create();
        $cart = [$book->id => [
            'name' => $book->name,
            'quantity' => 1,
            'price' => $book->price,
            'image' => $book->image,
        ]];
        session(['cart' => $cart]);

        $response = $this->delete('/delete-cart-product', [
            'id' => $book->id,
        ]);

        $response->assertStatus(200);
        $response->assertSessionHas('success', 'Book successfully deleted.');

        $updatedCart = session('cart');
        $this->assertArrayNotHasKey($book->id, $updatedCart);
    }
}
