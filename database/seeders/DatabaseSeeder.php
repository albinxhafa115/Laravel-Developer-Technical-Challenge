<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = collect([
            User::factory()->create([
                'name' => 'Alice',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
            ]),
            User::factory()->create([
                'name' => 'Bob',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
            ]),
        ]);

        $tags = Tag::factory(8)->create();

        Project::factory(5)->create()->each(function ($project) use ($tags, $users) {
            $project->update(['user_id' => $users->random()->id]);

            $issues = Issue::factory(rand(3, 6))->create(['project_id' => $project->id]);

            $issues->each(function ($issue) use ($tags, $users) {
                $issue->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
                $issue->users()->attach($users->random(rand(1, 2))->pluck('id'));
                Comment::factory(rand(2, 5))->create(['issue_id' => $issue->id]);
            });
        });
    }
}
