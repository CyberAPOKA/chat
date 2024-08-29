<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ConversationGroupSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $user1 = User::find(1); // Eu

        for ($i = 1; $i <= 10; $i++) {
            $randomUser = User::inRandomOrder()->where('id', '!=', 1)->first();

            $conversation = Conversation::create([
                'is_group' => false,
            ]);

            $conversation->users()->attach([$user1->id, $randomUser->id]);

            for ($j = 1; $j <= 5; $j++) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $faker->randomElement([$user1->id, $randomUser->id]),
                    'content' => $faker->sentence,
                ]);
            }
        }

        // Cria 5 grupos com 5 usuários aleatórios
        for ($i = 1; $i <= 5; $i++) {
            $groupUsers = User::inRandomOrder()->limit(rand(3, 5))->pluck('id')->toArray();
            $groupName = 'Group ' . $i;
            
            $conversation = Conversation::create([
                'name' => $groupName,
                'is_group' => true,
            ]);

            $group = Group::create([
                'name' => $groupName,
                'conversation_id' => $conversation->id,
            ]);

            // Adiciona o usuário com ID 1 (eu) ao grupo e outros usuários aleatórios
            $conversation->users()->attach(array_merge([$user1->id], $groupUsers));

            for ($j = 1; $j <= 5; $j++) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $faker->randomElement(array_merge([$user1->id], $groupUsers)),
                    'content' => $faker->sentence,
                ]);
            }
        }
    }
}
