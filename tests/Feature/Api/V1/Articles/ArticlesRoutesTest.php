<?php

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Admin;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ArticlesRoutesTest extends TestCase
{
    use RefreshDatabase; // this dosn't refresh AutoIncrement of id not.
    private Collection $articles;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->articles = Article::factory(15)->create();
        $this->token = auth('api')->fromUser(Admin::create([
            'firstname' => 'testname',
            'lastname' => 'testname',
            'email' => 'test@email.com',
            'isAdmin' => true,
            'password' => Hash::make('password123')
        ]));
    }
    /**
     * A basic feature test example.
     */

    public function test_that_index_return_articles(): void
    {
        // call the Endpoint
        $firstPage = $this->getJson('/api/v1/articles?page=1');
        $secondPage = $this->getJson('/api/v1/articles?page=2');

        //compare the results and pagination it work
        $firstPage->assertStatus(200)
            ->assertJsonCount(9, 'data') // assertJsonCount only on lot of data not single like in show
            ->assertJsonPath('pagination.current_page', 1)
            ->assertJsonPath('pagination.per_page', 9)
            ->assertJsonPath('pagination.last_page', 2)
            ->assertJsonPath('pagination.total', 15);

        $secondPage->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonPath('pagination.current_page', 2)
            ->assertJsonPath('pagination.per_page', 9)
            ->assertJsonPath('pagination.last_page', 2)
            ->assertJsonPath('pagination.total', 15);

        // asssert if data is equales
        $this->articles->take(9)->each(function (Article $article) use ($firstPage): void {
            $firstPage->assertJsonFragment(['id' => $article->articleID]);
        });

        $this->articles->skip(9)->each(function (Article $article) use ($secondPage): void {
            $secondPage->assertJsonFragment(['id' => $article->articleID]);
        });
    }

    public function test_that_show_return_article(): void
    {
        // use dummy data
        $article = $this->articles[5]; // i use this bc RefreshDatabase trait dosn't restart AutoIncrement

        // call the endpoint
        $response = $this->getJson('/api/v1/articles/' . $article->articleID);

        // compare the results ( code status and data is equal )
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $article->articleID, 'title' => $article->title, 'category' => $article->category]);
    }

    public function test_that_post_create_article(): void
    {
        // use dummy data
        $credentials = Article::factory()->make()->toArray();
        // call the endpoint
        $response = $this->postJson('/api/v1/jwt/admin/articles', $credentials, [
            'Authorization' => 'Bearer ' . $this->token
        ]);
        // compare the results ( code status and data is equal)
        $response->assertStatus(201);
        $response->assertJsonFragment(['title' => $credentials['title'], 'category' => $credentials['category']]);
        //check if the article created in DB
        $article = $response->json('data');
        $this->assertDatabaseHas('articles', [
            'articleID' => $article['id'],
            'title' => $article['title'],
            'category' => $article['category'],
        ]);
    }

    public function test_that_patch_update_article(): void
    {
        // use dumy data
        $article = $this->articles[1];
        $payload = ['title' => 'this article update inside Test'];
        //call the endpoint
        $response = $this->patchJson('/api/v1/jwt/admin/articles/' . $article->articleID, $payload, [
            'Authorization' => 'Bearer ' . $this->token
        ]);
        //compare the results ( code status and data equal)
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $article->articleID, 'title' => $payload['title'], 'category' => $article->category]);
        // check the articles updated in DB
        $this->assertDatabaseHas('articles', [
            'articleID' => $article->articleID,
            'title' => $payload['title'],
            'category' => $article->category,
        ]);
    }

    public function test_that_delete_can_delete_article(): void
    {
        $article = $this->articles[1];
        $response = $this->deleteJson('/api/v1/jwt/admin/articles/' . $article->articleID, [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertNoContent();
        $this->assertSoftDeleted($article);
    }

    public function test_that_index_rejects_invalid_parameter(): void
    {
        $response = $this->getJson('/api/v1/articles?page=abc');
        $response->assertStatus(422);
    }

    public function test_that_show_returns_not_found_for_non_valid_id(): void
    {
        $response = $this->getJson('/api/v1/articles/abc');

        $response->assertStatus(404);
    }

    public function test_that_show_return_not_found_for_nonexistent_id(): void
    {
        $response = $this->getJson('/api/v1/articles/99999');

        $response->assertStatus(404);
    }


    public function test_that_update_return_not_found_for_non_numeric_id(): void
    {
        $response = $this->patchJson('/api/v1/jwt/admin/articles/abc', [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(404);
    }

    public function test_that_update_return_not_found_for_nonexistent_id(): void
    {
        $response = $this->patchJson('/api/v1/jwt/admin/articles/99999', [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);
    }

    public function test_that_delete_return_not_found_for_non_numeric_id(): void
    {
        $response = $this->deleteJson('/api/v1/jwt/admin/articles/abc', [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(404);
    }

    public function test_that_delete_return_not_found_for_nonexistent_id(): void
    {
        $response = $this->deleteJson('/api/v1/jwt/admin/articles/99999', [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);
    }
}
