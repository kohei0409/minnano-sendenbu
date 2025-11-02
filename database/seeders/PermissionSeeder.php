<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view_posts',
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            'view_comments',
            'create_comments',
            'edit_comments',
            'delete_comments',
            'manage_store_info',
            'view_analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
