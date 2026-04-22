<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==================== 默认博主账号 ====================
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => '博主',
                'password' => Hash::make('password', ['rounds' => 12]),
                'role' => UserRole::ADMIN,
                'bio' => '我是这个博客的博主，热爱技术，喜欢分享。',
                'email_verified_at' => now(),
            ]
        );

        // ==================== 编辑账号 ====================
        User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => '编辑小李',
                'password' => Hash::make('password', ['rounds' => 12]),
                'role' => UserRole::EDITOR,
                'bio' => '负责内容编辑和审核工作。',
                'email_verified_at' => now(),
            ]
        );

        // ==================== 测试用户 ====================
        $testUsers = [
            [
                'name' => '测试用户1',
                'email' => 'user1@example.com',
                'role' => UserRole::SUBSCRIBER,
                'bio' => '热爱阅读和写作的朋友',
            ],
            [
                'name' => '测试用户2',
                'email' => 'user2@example.com',
                'role' => UserRole::SUBSCRIBER,
                'bio' => '技术爱好者',
            ],
            [
                'name' => '游客用户',
                'email' => 'guest@example.com',
                'role' => UserRole::SUBSCRIBER,
                'bio' => '随便看看',
            ],
        ];

        foreach ($testUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password', ['rounds' => 12]),
                    'role' => $userData['role'],
                    'bio' => $userData['bio'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('User seeder completed:');
        $this->command->info('- Admin: admin@example.com / password');
        $this->command->info('- Editor: editor@example.com / password');
        $this->command->info('- Test users: user1@example.com, user2@example.com, guest@example.com / password');
    }
}
