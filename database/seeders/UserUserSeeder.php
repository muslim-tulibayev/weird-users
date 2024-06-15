<?php

namespace Database\Seeders;

use App\Enum\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_ids = User::pluck('id');

        $used_combinations = [];
        $user_users = [];

        foreach ($user_ids as $user_1) {
            foreach ($user_ids as $user_2) {
                if ($user_1 != $user_2) {
                    foreach (UserType::toArray() as $type) {

                        $combination_1 = $user_1 . '-' . $user_2 . '-' . $type->value;
                        $combination_2 = $user_2 . '-' . $user_1 . '-' . $type->value;

                        if (!in_array($combination_1, $used_combinations) and !in_array($combination_2, $used_combinations)) {

                            $used_combinations[] = $combination_1;

                            $user_users[] = [
                                'user_id' => $user_1,
                                'related_user_id' => $user_2,
                                'type' => $type
                            ];

                        }
                    }
                }
            }
        }

        DB::table('user_users')->insert($user_users);
    }
}
