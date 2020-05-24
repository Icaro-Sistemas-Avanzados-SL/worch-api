<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* $this->call(UserSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ChallengesTableSeeder::class);
        $this->call(FilesTableSeeder::class);*/
       /* factory(App\Models\User::class, 50)->create()->each(function($u) {
            $u->challenges()->save(factory(App\Models\Challenge::class, 10)->create([
                'parent_id' => null
            ]));
            $u->challenges()->save(factory(App\Models\Challenge::class, 30)->create());
        });*/
        /*factory(App\Models\User::class, 50)->create();
        factory(App\Models\Challenge::class, 20)->create(['parent_id' => null])->each(function($u) {
            $u->files()->save(factory(App\Models\File::class)->create([
                'challenge_id' => $u->id,
                'category_id' => null,
            ]));
            $u->ratings()->saveMany(factory(App\Models\Rating::class, 50)->create([
                'challenge_id' => $u->id,
            ]));
            $u->children()->saveMany(factory(App\Models\Challenge::class, 150)->create(['parent_id' => $u->id])->each(function($p) use($u) {
                $p->files()->save(factory(App\Models\File::class)->create([
                    'challenge_id' => $p->id,
                    'category_id' => null,
                ]));
                $p->ratings()->saveMany(factory(App\Models\Rating::class, 30)->create([
                    'challenge_id' => $p->id,
                ]));
            }));
        });*/

        factory(App\Models\Favourite::class, 200)->create();
        factory(App\Models\Follower::class, 200)->create();
    }
}
