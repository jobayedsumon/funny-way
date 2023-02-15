<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;



class PostCommentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 12; $i++) {
            $post = new Post();
            $post->title = $faker->sentence;
            $post->slug = $faker->slug;
            $post->content = $faker->paragraph;
            $post->user_id = 2;
            $post->save();

            for ($j = 1; $j <= rand(1, 3); $j++) {
                $post->comments()->create([
                    'post_id' => $post->id,
                    'content' => $faker->sentence,
                    'user_id' => 2
                ]);
            }
        }
    }
}
