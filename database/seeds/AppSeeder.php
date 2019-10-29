<?php

use App\Models\Admin\AdminUser;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminUser::query()->create([
            'names' => 'karel puerto diaz',
            'email' => 'karelpuerto78@gmail.com',
            'password' => Hash::make('12345'),
            'secret' => \Illuminate\Support\Str::random(25),
            'status_id' => 1
        ]);

/*
        $religions = [
            ['id' => 1, 'descriptor' => 'Ateo'],
            ['id' => 2, 'descriptor' => 'Budismo'],
            ['id' => 3, 'descriptor' => 'Catolica'],
            ['id' => 4, 'descriptor' => 'Cristiano'],
            ['id' => 5, 'descriptor' => 'Hinduismo'],
            ['id' => 6, 'descriptor' => 'Islam'],
            ['id' => 7, 'descriptor' => 'Judaísmo'],
            ['id' => 8, 'descriptor' => 'Ortodoxo'],
            ['id' => 9, 'descriptor' => 'Protestante'],

        ];
        \App\Models\Religion::query()->insert($religions);

        $politics = [
            ['id' => 1, 'descriptor' => 'Apolitico'],
            ['id' => 2, 'descriptor' => 'Concervador'],
            ['id' => 3, 'descriptor' => 'Liberal'],
            ['id' => 4, 'descriptor' => 'Republicano'],
            ['id' => 5, 'descriptor' => 'Democrata'],
            ['id' => 6, 'descriptor' => 'Socialista'],
        ];
        \App\Models\Politics::query()->insert($politics);

        $users_status = [
            ['id' => 1, 'descriptor' => 'Activo'],
            ['id' => 2, 'descriptor' => 'Inactivo'],
            ['id' => 3, 'descriptor' => 'Desconectado'],
            ['id' => 4, 'descriptor' => 'No molestar'],
        ];
        \App\Models\UserStatus::query()->insert($users_status);

        $users_sex = [
            ['id' => 1, 'descriptor' => 'Masculino'],
            ['id' => 2, 'descriptor' => 'Femenino'],
        ];
        \App\Models\UserSex::query()->insert($users_sex);

        $users_civil_status = [
            ['id' => 1, 'descriptor' => 'Casado'],
            ['id' => 2, 'descriptor' => 'Soltero'],
        ];
        \App\Models\UserCivilStatus::query()->insert($users_civil_status);

        // CREANDO UN USUARIO DE PRUEBA
      User::query()->create([
           'full_names' => 'karel puerto diaz',
           'email' => 'karelpuerto78@gmail.com',
           'password' => Hash::make('12345'),
           'sex_id' => 1,
           'secret' => \Illuminate\Support\Str::random(25),
           'birthdate' => '1978-10-07'
       ]);
*/
    }
}
