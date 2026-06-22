<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $tags = Tag::factory(8)->create();

        Project::factory(5)->create()->each(function ($project) use ($tags) {
            $issues = Issue::factory(rand(3, 6))->create(['project_id' => $project->id]);

            $issues->each(function ($issue) use ($tags) {
                $issue->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
                \App\Models\Comment::factory(rand(2, 5))->create(['issue_id' => $issue->id]);
            });
        });
    }
}
