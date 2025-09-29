<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Helper method to create a test user
     */
    protected function createTestUser($attributes = [])
    {
        $defaultAttributes = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'user_status_id' => 1,
            'user_type_id' => 1,
            'request_change_pass' => false,
        ];

        return \App\Models\User::create(array_merge($defaultAttributes, $attributes));
    }

    /**
     * Helper method to create test user status
     */
    protected function createTestUserStatus($attributes = [])
    {
        $defaultAttributes = [
            'name' => 'Active',
            'sign' => 'active',
            'color' => 'green',
        ];

        return \App\Models\UserStatus::create(array_merge($defaultAttributes, $attributes));
    }

    /**
     * Helper method to create test user type
     */
    protected function createTestUserType($attributes = [])
    {
        $defaultAttributes = [
            'name' => 'Admin',
            'sign' => 'admin',
            'color' => 'blue',
        ];

        return \App\Models\UserType::create(array_merge($defaultAttributes, $attributes));
    }

    /**
     * Helper method to authenticate a user
     */
    protected function authenticateUser($user = null)
    {
        if (!$user) {
            $user = $this->createTestUser();
        }

        $this->actingAs($user);
        return $user;
    }
}
