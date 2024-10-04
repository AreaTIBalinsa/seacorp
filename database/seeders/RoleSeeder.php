<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_administradores = Role::create(['name' => 'Administradores']);
        $role_recusoshumanos = Role::create(['name' => 'Recursos Humanos']);
        $role_pesadores = Role::create(['name' => 'Pesadores']);

        $permission_usuarios = Permission::create(['name' => 'listarusuarios.index', 'guard_name' => 'web']);
        $permission_reportes = Permission::create(['name' => 'reportes.index', 'guard_name' => 'web']);
        $permission_personal = Permission::create(['name' => 'personal.index', 'guard_name' => 'web']);
        $permission_precios = Permission::create(['name' => 'precios.index', 'guard_name' => 'web']);

        $permission_administradores = [
            $permission_usuarios,
            $permission_reportes,
            $permission_personal,
            $permission_precios
        ];

        $permission_recursoshumanos =  [
            $permission_reportes,
            $permission_personal,
            $permission_precios
        ];

        $permission_pesadores =  [
            $permission_personal
        ];

        $role_administradores->syncPermissions($permission_administradores);
        $role_recusoshumanos->syncPermissions($permission_recursoshumanos);
        $role_pesadores->syncPermissions($permission_pesadores);
    }
}
