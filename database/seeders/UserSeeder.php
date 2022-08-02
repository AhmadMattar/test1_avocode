<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name'          => 'Admin System',
            'email'         => 'admin@gmail.com',
            'status'        => 1,
            'password'      => bcrypt('123456789'),
        ]);
        $countrySupervisor = User::create([
            'name' => 'CountrySupervisor',
            'email' => 'countrysupervisor@gmail.com',
            'status'        => 1,
            'password'      => bcrypt('1234567'),
        ]);
        $citySupervisor = User::create([
            'name' => 'CitySupervisor',
            'email' => 'citysupervisor@gmail.com',
            'status'        => 1,
            'password'      => bcrypt('1234567'),
        ]);
        $districtSupervisor = User::create([
            'name' => 'DistrictSupervisor',
            'email' => 'districtsupervisor@gmail.com',
            'status'        => 1,
            'password'      => bcrypt('1234567'),
        ]);
        $customerSupervisor = User::create([
            'name' => 'CustomerSupervisor',
            'email' => 'customersupervisor@gmail.com',
            'status'        => 1,
            'password'      => bcrypt('1234567'),
        ]);


        $adminRole = Role::create(['name' => 'Admin']); $admin->assignRole($adminRole);

        $permission1 = Permission::create(['name' => 'admin_permission']); $admin->givePermissionTo($permission1);

        $permission2 = Permission::create(['name' => 'create_country']); $countrySupervisor->givePermissionTo($permission2);
        $permission3 = Permission::create(['name' => 'edit_country']);  $countrySupervisor->givePermissionTo($permission3);
        $permission4 = Permission::create(['name' => 'delete_country']); $countrySupervisor->givePermissionTo($permission4);
        $permission5 = Permission::create(['name' => 'active_country']); $countrySupervisor->givePermissionTo($permission5);
        $permission6 = Permission::create(['name' => 'disactive_country']); $countrySupervisor->givePermissionTo($permission6);

        $permission7 = Permission::create(['name' => 'create_city']); $citySupervisor->givePermissionTo($permission7);
        $permission8 = Permission::create(['name' => 'edit_city']); $citySupervisor->givePermissionTo($permission8);
        $permission9 = Permission::create(['name' => 'delete_city']); $citySupervisor->givePermissionTo($permission9);
        $permission10 = Permission::create(['name' => 'active_city']); $citySupervisor->givePermissionTo($permission10);
        $permission11 = Permission::create(['name' => 'disactive_city']); $citySupervisor->givePermissionTo($permission11);

        $permission12 = Permission::create(['name' => 'create_district']); $districtSupervisor->givePermissionTo($permission12);
        $permission13 = Permission::create(['name' => 'edit_district']); $districtSupervisor->givePermissionTo($permission13);
        $permission14 = Permission::create(['name' => 'delete_district']); $districtSupervisor->givePermissionTo($permission14);
        $permission15 = Permission::create(['name' => 'active_district']); $districtSupervisor->givePermissionTo($permission15);
        $permission16 = Permission::create(['name' => 'disactive_district']); $districtSupervisor->givePermissionTo($permission16);

        $permission17 = Permission::create(['name' => 'create_customer']); $customerSupervisor->givePermissionTo($permission17);
        $permission18 = Permission::create(['name' => 'edit_customer']); $customerSupervisor->givePermissionTo($permission18);
        $permission19 = Permission::create(['name' => 'delete_customer']); $customerSupervisor->givePermissionTo($permission19);
        $permission20 = Permission::create(['name' => 'active_customer']); $customerSupervisor->givePermissionTo($permission20);
        $permission21 = Permission::create(['name' => 'disactive_customer']); $customerSupervisor->givePermissionTo($permission21);
    }
}
