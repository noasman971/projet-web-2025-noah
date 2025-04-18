<?php

namespace Database\Seeders;

use App\Models\Cohort;
use App\Models\Cohort_Task;
use App\Models\CommonTask;
use App\Models\School;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserSchool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the default user
        $admin = User::create([
            'last_name'     => 'Admin',
            'first_name'    => 'Admin',
            'email'         => 'admin@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $teacher = User::create([
            'last_name'     => 'Teacher',
            'first_name'    => 'Teacher',
            'email'         => 'teacher@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $user = User::create([
            'last_name'     => 'Student',
            'first_name'    => 'Student',
            'email'         => 'student@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);




        // Create the default school
        $school = School::create([
            'user_id'   => $user->id,
            'name'      => 'Coding Factory',
        ]);

        // Create the admin role
        UserSchool::create([
            'user_id'   => $admin->id,
            'school_id' => $school->id,
            'role'      => 'admin'
        ]);

        // Create the teacher role
        UserSchool::create([
            'user_id'   => $teacher->id,
            'school_id' => $school->id,
            'role'      => 'teacher'
        ]);

        // Create the student role
        UserSchool::create([
            'user_id'   => $user->id,
            'school_id' => $school->id,
            'role'      => 'student'
        ]);

        // Create the default common task
        CommonTask::create([
            'name'          => 'Nettoyage des surfaces de travail',
            'description'   => 'Essuyer et désinfecter régulièrement les bureaux, tables et autres surfaces de travail pour éviter l’accumulation de poussière, de saleté et de germes.',
            'validate'      => false,
        ]);
        CommonTask::create([
            'name'          => 'Gestion des déchets',
            'description'   => 'Vider les poubelles et les bacs de recyclage régulièrement pour éviter les mauvaises odeurs et maintenir un environnement propre.',
            'validate'      => false,
        ]);
        CommonTask::create([
            'name'          => 'Entretien des équipements',
            'description'   => 'Vérifier régulièrement le bon fonctionnement des équipements de la salle de classe (projecteurs, ordinateurs, etc.) et signaler tout problème.',
            'validate'      => false,
        ]);
        CommonTask::create([
            'name'          => 'Rangement des fournitures',
            'description'   => 'S’assurer que les fournitures scolaires (stylos, papiers, etc.) sont rangées correctement et en bon état.',
            'validate'      => false,
        ]);
        CommonTask::create([
            'name'          => 'Vérification des équipements de sécurité',
            'description'   => 'S’assurer que les équipements de sécurité (extincteurs, trousses de premiers secours, etc.) sont en bon état et accessibles.',
            'validate'      => false,
        ]);






        // Create the default cohorts
        Cohort::create([
            'school_id'     => 1,
            'name'          => 'Cohort 1',
            'description'   => 'Cohort 1 description',
            'start_date'    => now(),
            'end_date'      => now()->addMonths(6),
        ]);

        Cohort::create([
            'school_id'     => 1,
            'name'          => 'Cohort 2',
            'description'   => 'Cohort 2 description',
            'start_date'    => now(),
            'end_date'      => now()->addMonths(6),
        ]);

        Cohort::create([
            'school_id'     => 1,
            'name'          => 'Cohort 3',
            'description'   => 'Cohort 3 description',
            'start_date'    => now(),
            'end_date'      => now()->addMonths(6),
        ]);


        // Create the default cohort_task
        Cohort_Task::create([
            'cohort_id'     => 1,
            'common_task_id' => 1,
        ]);
        Cohort_Task::create([
            'cohort_id'     => 2,
            'common_task_id' => 1,
        ]);
        Cohort_Task::create([
            'cohort_id'     => 3,
            'common_task_id' => 1,
        ]);
        Cohort_Task::create([
            'cohort_id'     => 1,
            'common_task_id' => 2,
        ]);
        Cohort_Task::create([
            'cohort_id'     => 2,
            'common_task_id' => 2,
        ]);

    }
}
