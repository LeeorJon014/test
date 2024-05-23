<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Yajra\Acl\Models\Role;
use Yajra\Acl\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedRoles($this->getRoles());

        $this->createRole('Super Administrator', Permission::all()->pluck('slug')->toArray());

        $this->createRole('Relevant Interested Party', [
            'cultural-property-create',
            'cultural-property-retrieve',
            'announcement-retrieve',
            'dashboard-retrieve'
        ]);

        $this->createRole('Administrative Service Officer', [
            'cultural-property-retrieve',
            'dashboard-retrieve',
            'announcement-retrieve',
            'company-retrieve',
        ]);

        $this->createRole('Registry Coordinator', [
            'cultural-property-retrieve',
            'cultural-property-edit',
            'dashboard-retrieve',
            'announcement-create',
            'announcement-retrieve',
            'announcement-edit',
            'company-create',
            'company-retrieve',
        ]);

        $this->createRole('Cultural Registry Data Officer', ['']);
        $this->createRole('PRECUP Head', ['']);
        $this->createRole('Geographic Information Systems Analyst', ['']);
    }


    /**
     * Get roles to be inserted to the database.
     *
     * @return array
     */
    private function getRoles(): array
    {
        return [
            [
                'name' => 'Super Administrator',
                'slug' => 'super-administrator',
                'description' => '',
                'system' => '0',
            ],
            [
                'name' => 'Relevant Interested Party',
                'slug' => 'relevant-interested-party',
                'description' => '',
                'system' => '0',
            ],
            [
                'name' => 'Administrative Service Officer',
                'slug' => 'administrative-service-officer',
                'description' => '',
                'system' => '0',
            ],
            [
                'name' => 'Registry Coordinator',
                'slug' => 'registry-coordinator',
                'description' => '',
                'system' => '0',
            ],
            [
                'name' => 'Cultural Registry Data Officer',
                'slug' => 'cultural-registry-data-officer',
                'description' => '',
                'system' => '0',
            ],
            [
                'name' => 'PRECUP Head',
                'slug' => 'precup-head',
                'description' => '',
                'system' => '0',
            ],
            [
                'name' => 'Geographic Information Systems Analyst',
                'slug' => 'geographic-information-systems-analyst',
                'description' => '',
                'system' => '0',
            ],
        ];
    }


    /**
     * Populate the database with dummy data.
     *
     * @return void
     */
    private function seedRoles(array $roles): void
    {
        foreach ($roles as $role) {
            Role::updateOrCreate(
                [
                    'name' => $role['name']
                ],
                [
                    'slug' => $role['slug'],
                    'description' => $role['description'],
                    'system' => $role['system'],
                ]
            );
        }
    }

    /**
     * Create or retrieve a role with the given name and grant permissions.
     *
     * @param string $roleName 
     * @param array $permissions Optional array of permission slugs to grant to the role.
     * @return \App\Models\Role
     */
    function createRole($roleName, $permissions = []): Role
    {
        $role = Role::where('name', '=', $roleName)->first();

        if (!$role) {
            $role = Role::create(['name' => $roleName]);
        }

        if (!empty($permissions)) {
            $role->grantPermissionBySlug($permissions);
        }

        return $role;
    }
}
