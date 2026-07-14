<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $super_admin = Role::firstOrCreate(['name'=>'super_admin']);
        $admin = Role::firstOrCreate(['name'=>'admin']);

        Permission::firstOrCreate(['name'=> 'dashboard.index'])->syncRoles([$super_admin, $admin]);

        Permission::firstOrCreate(['name'=> 'brands.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brands.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brands.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brands.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brands.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brands.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brands.destroy'])->syncRoles([$super_admin, $admin]);
  
        Permission::firstOrCreate(['name'=> 'brand_models.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brand_models.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brand_models.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brand_models.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brand_models.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brand_models.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'brand_models.destroy'])->syncRoles([$super_admin, $admin]);
       
        Permission::firstOrCreate(['name'=> 'companies.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'companies.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'companies.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'companies.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'companies.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'companies.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'companies.destroy'])->syncRoles([$super_admin, $admin]);
 
        Permission::firstOrCreate(['name'=> 'computers.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'computers.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'computers.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'computers.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'computers.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'computers.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'computers.destroy'])->syncRoles([$super_admin, $admin]);

        Permission::firstOrCreate(['name'=> 'departments.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'departments.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'departments.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'departments.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'departments.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'departments.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'departments.destroy'])->syncRoles([$super_admin, $admin]);

        Permission::firstOrCreate(['name'=> 'drives.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drives.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drives.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drives.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drives.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drives.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drives.destroy'])->syncRoles([$super_admin, $admin]);
        
        Permission::firstOrCreate(['name'=> 'drive_types.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drive_types.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drive_types.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drive_types.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drive_types.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drive_types.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'drive_types.destroy'])->syncRoles([$super_admin, $admin]);
        
        Permission::firstOrCreate(['name'=> 'employees.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'employees.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'employees.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'employees.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'employees.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'employees.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'employees.destroy'])->syncRoles([$super_admin, $admin]);
        
        Permission::firstOrCreate(['name'=> 'images.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'images.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'images.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'images.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'images.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'images.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'images.destroy'])->syncRoles([$super_admin, $admin]);
        
        Permission::firstOrCreate(['name'=> 'operating_systems.index'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'operating_systems.create'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'operating_systems.store'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'operating_systems.show'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'operating_systems.edit'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'operating_systems.update'])->syncRoles([$super_admin, $admin]);
        Permission::firstOrCreate(['name'=> 'operating_systems.destroy'])->syncRoles([$super_admin, $admin]);
        
        Permission::firstOrCreate(['name'=> 'users.index'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'users.create'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'users.store'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'users.show'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'users.edit'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'users.update'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'users.destroy'])->syncRoles([$super_admin]);
  
        Permission::firstOrCreate(['name'=> 'warranties.index'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'warranties.create'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'warranties.store'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'warranties.show'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'warranties.edit'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'warranties.update'])->syncRoles([$super_admin]);
        Permission::firstOrCreate(['name'=> 'warranties.destroy'])->syncRoles([$super_admin]);


        // User::firstOrCreate([
        //     'name' => 'Administrador TI',
        //     'username' => 'admin', 
        //     // 'email' => 'admin@empresa.com',
        //     'password' => Hash::make('admin@1'),
        // ])->assignRole('super_admin');

    }
}
