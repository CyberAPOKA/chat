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

        // Cria 10 conversas individuais (1 pra 1)
        $createdUsers = [];

        for ($i = 1; $i <= 10; $i++) {
            do {
                $randomUser = User::inRandomOrder()->where('id', '!=', $user1->id)->first();
            } while (in_array($randomUser->id, $createdUsers));

            $createdUsers[] = $randomUser->id; // Evita duplicações

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

        // Cria 5 grupos com 3 a 5 usuários aleatórios
        for ($i = 1; $i <= 5; $i++) {
            $groupUsers = User::inRandomOrder()->limit(rand(3, 5))->pluck('id')->toArray();
            $groupName = 'Group ' . $i;

            $conversation = Conversation::create([
                'name' => $groupName,
                'is_group' => true,
            ]);

            Group::create([
                'name' => $groupName,
                'conversation_id' => $conversation->id,
            ]);

            // Adiciona eu e outros usuários aleatórios ao grupo
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
