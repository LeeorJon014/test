<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Yajra\Acl\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedPermissions($this->getPermissions());
    }


    /**
     * Get permissions to be inserted to the database.
     *
     * @return array
     */
    private function getPermissions(): array
    {
        return [
            [
                'name' => 'cultural-property.publish',
                'slug' => 'cultural-property-publish',
                'system' => '1',
            ],
            [
                'name' => 'cultural-property.approve',
                'slug' => 'cultural-property-approve',
                'system' => '1',
            ],
            [
                'name' => 'cultural-property.decline',
                'slug' => 'cultural-property-decline',
                'system' => '1',
            ],
            [
                'name' => 'cultural-property.create',
                'slug' => 'cultural-property-create',
                'system' => '1',
            ],
            [
                'name' => 'cultural-property.view',
                'slug' => 'cultural-property-view',
                'system' => '1',
            ],
            [
                'name' => 'cultural-property.edit',
                'slug' => 'cultural-property-edit',
                'system' => '1',
            ],
            [
                'name' => 'cultural-property.delete ',
                'slug' => 'cultural-property-delete',
                'system' => '1',
            ],
            [
                'name' => 'dashboard.view',
                'slug' => 'dashboard-view',
                'system' => '1',
            ],
            [
                'name' => 'announcement.create',
                'slug' => 'announcement-create',
                'system' => '1',
            ],
            [
                'name' => 'announcement.view',
                'slug' => 'announcement-view',
                'system' => '1',
            ],
            [
                'name' => 'announcement.edit',
                'slug' => 'announcement-edit',
                'system' => '1',
            ],
            [
                'name' => 'announcement.delete ',
                'slug' => 'announcement-delete',
                'system' => '1',
            ],
            [
                'name' => 'company.create',
                'slug' => 'company-create',
                'system' => '1',
            ],
            [
                'name' => 'company.view',
                'slug' => 'company-view',
                'system' => '1',
            ],
            [
                'name' => 'company.edit',
                'slug' => 'company-edit',
                'system' => '1',
            ],
            [
                'name' => 'company.delete ',
                'slug' => 'company-delete',
                'system' => '1',
            ],
            [
                'name' => 'Cultural Property Table - Create',
                'slug' => 'cultural-property-table-create',
                'system' => '1',
            ],
            [
                'name' => 'Cultural Property Table - edit',
                'slug' => 'cultural-property-table-edit',
                'system' => '1',
            ],
            [
                'name' => 'Cultural Property Table - retrieve',
                'slug' => 'cultural-property-table-retrieve',
                'system' => '1',
            ],
            [
                'name' => 'Cultural Property Table - delete',
                'slug' => 'cultural-property-table-delete',
                'system' => '1',
            ],

        ];
    }


    /**
     * Populate the database with dummy data.
     *
     * @return void
     */
    private function seedPermissions(array $permissions): void
    {
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission['name']
                ],
                [
                    'slug' => $permission['slug'],
                    'system' => $permission['system'],
                ]
            );
        }
    }
}
