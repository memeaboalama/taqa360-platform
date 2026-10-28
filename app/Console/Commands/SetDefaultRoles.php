<?php

namespace App\Console\Commands;

use Domain\MarketPlace\Models\Merchant;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetDefaultRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-default-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old roles and Set Default roles for old merchants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->deleteRoles();
        $this->setNewRoles();
        echo "DONE" . "\n";
    }

    protected function deleteRoles() : void
    {
        $roles =  Role::all();
        foreach ($roles as $role)
        {
            $role->delete();
        }
    }

    protected function setNewRoles() : void
    {
        $merchants =  Merchant::all();
        foreach ($merchants as $merchant)
        {
            $supeRole = Role::create([
                'name' => 'الادمن' . '_' . $merchant->id
                ,'guard_name' => "merchant_employee"
                ,'team_id' => $merchant->id
            ]);

            $supeRole->syncPermissions($this->preparePermissions([], "hello"));

            $managerRole = Role::create([
                'name' => 'مدير الفرع' . '_' . $merchant->id
                ,'guard_name' => "merchant_employee"
                ,'team_id' => $merchant->id
            ]);

            $managerRole->syncPermissions($this->preparePermissions(['profile'], "hello"));

            $employeeRole = Role::create([
                'name' => 'موظف' . '_' . $merchant->id
                ,'guard_name' => "merchant_employee"
                ,'team_id' => $merchant->id
            ]);

            $employeeRole->syncPermissions($this->preparePermissions(['profile', 'branches', 'roles', 'employees'], 'delete'));
        }
    }

    protected function preparePermissions(array $groupNames = [], $except = "") : array
    {
        return Permission::where('team_type', Merchant::class)
            ->whereNotIn('group_name', $groupNames)
            ->where('name', 'not LIKE', '%'.$except.'%')
            ->pluck('id','id')
            ->all();
    }


}
