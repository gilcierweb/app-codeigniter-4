<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Profiles extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $users = $this->db->table('users')->get()->getResult();

        if (empty($users)) {
            log_message('warning', 'No users found to create profiles for.');
            return;
        }

        $profilesData = [];
        foreach ($users as $user) {
            // Check if a profile already exists for this user to avoid duplicates
            $existingProfile = $this->db->table('profiles')->where('user_id', $user->id)->get()->getRow();
            if (!$existingProfile) {
                $profilesData[] = [
                    'first_name' => $faker->firstName,
                    'last_name'  => $faker->lastName,
                    'website'    => $faker->url,
                    'instagram'  => $faker->userName,
                    'facebook'   => 'https://facebook.com/' . $faker->userName,
                    'linkedin'   => 'https://linkedin.com/in/' . $faker->userName,
                    'twitter_x'  => '@' . $faker->userName,
                    'avatar'     => $faker->imageUrl(200, 200, 'people', true),
                    'bio'        => $faker->paragraph(3),
                    'user_id'    => $user->id,
                ];
            }
        }

        if (!empty($profilesData)) {
            $this->db->table('profiles')->insertBatch($profilesData);
            echo "Created " . count($profilesData) . " fake profiles.\n";
        } else {
            echo "No new profiles to create.\n";
        }
    }
}
