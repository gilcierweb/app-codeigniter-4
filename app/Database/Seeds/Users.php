<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Users extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $numUsers = 10; // You can adjust the number of fake users

        $usersData = [];
        for ($i = 0; $i < $numUsers; $i++) {
            $username = $faker->userName;
            $email = $faker->email;
            $usersData[] = [
                'username' => $username,
                'email'    => $email,
                'password' => password_hash('password', PASSWORD_BCRYPT), // Use a generic password for fake users
            ];
        }

        // Using Query Builder
        $this->db->table('users')->insertBatch($usersData);

        $users = $this->db->table('users')->get()->getResult();
        $profilesData = [];

        foreach ($users as $user) {
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

        // Using Query Builder
        $this->db->table('profiles')->insertBatch($profilesData);
    }
}
