<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Genre::class)->create([
            'name' => 'test'
        ])->first();
        $genres = Genre::all();
        $this->assertCount(1, $genres);

        $genresKeys = array_keys($genres->first()->getAttributes());
        $this->assertEquals(
            [
                'id',
                'name',
                'is_active',
                'deleted_at',
                'created_at',
                'updated_at'
            ],
            $genresKeys
        );

    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'test1'
        ]);

        $genre->refresh();

        $this->assertEquals(36, strlen($genre->id));
        $this->assertEquals('test1', $genre->name);
        $this->assertTrue($genre->is_active);

        $genre = Genre::create([
            'name' => 'test1',
            'is_active' => false
        ]);

        $this->assertFalse($genre->is_active);

        $genre = Genre::create([
            'name' => 'test1',
            'is_active' => true
        ]);

        $this->assertTrue($genre->is_active);
    }

    public function testUpdate()
    {
        /** @var Genre $genre */
        $genre = factory(Genre::class)->create([
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'test_name_updated',
            'is_active' => true
        ];

        $genre->update($data);

        foreach($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testeDelete()
    {
        /** @var Genre $genre */
        $genre = factory(Genre::class)->create()->first();
        $genre->delete();
        $this->assertNull(Genre::find($genre->id));

        $genre->restore();
        $this->assertNotNull(Genre::find($genre->id));
    }
}
