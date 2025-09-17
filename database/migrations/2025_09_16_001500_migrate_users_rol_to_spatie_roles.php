<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'rol')) {
            return;
        }

        // Gather distinct roles present in users table
        $roles = DB::table('users')->select('rol')->whereNotNull('rol')->distinct()->pluck('rol')->filter()->values();

        foreach ($roles as $rolName) {
            // Create role in Spatie if missing
            if (! Role::where('name', $rolName)->exists()) {
                Role::create(['name' => $rolName]);
            }
        }

        // Assign role to each user
        $users = DB::table('users')->select('id', 'rol')->whereNotNull('rol')->get();
        foreach ($users as $u) {
            if (empty($u->rol)) {
                continue;
            }
            // Use the model to assign role so Spatie pivot is populated
            $model = \App\Models\User::find($u->id);
            if ($model) {
                try {
                    $model->assignRole($u->rol);
                } catch (\Throwable $e) {
                    // swallow assignment exceptions but keep migration going
                    \Log::warning('Role assignment failed for user '.$u->id.': '.$e->getMessage());
                }
            }
        }

        // Finally drop the column (if DB engine supports it)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'rol')) {
                $table->dropColumn('rol');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the column but leave it null - we can't reliably reverse role assignments
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'rol')) {
                $table->enum('rol', ['Admin_General','Admin_Secretaria','Supervisor','Conductor'])->default('Conductor')->after('telefono');
            }
        });
    }
};
