<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'siteName' => 'Restoran 19',
            'siteLogo' => '',
            'siteDescription' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Vero consequatur numquam autem quo ad id veritatis quisquam similique repellat possimus?',
            'siteEmail' => 'restoran19@gmail.com',
            'sitePhoneNumber' => '082281666584',
            'siteAddress' => 'Jl. Wr. Supratman Kel. Kandang Limun Bengkulu'
            
        ];

        foreach ($data as $key => $value) {
            DB::table('settings')->insertOrIgnore([
                'key' => $key,
                'value' => $value
            ]);
        }
    }
}
