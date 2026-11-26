<?php

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticlesRoutesTest extends TestCase
{
    use RefreshDatabase; // this refresh data and AutoIncrement of id not.
    private Collection $articles;

    protected function setUp(): void
    {
        parent::setUp();

        $this->articles = Article::factory(15)->create();
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
        $response = $this->getJson('/api/v1/articles/'. $article->articleID);

        // compare the results ( code status and data is equal )
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $article->articleID, 'title' => $article->title, 'category' => $article->category]);
    }
}
