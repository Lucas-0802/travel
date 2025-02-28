<?php

namespace Database\Seeders;

use App\Models\Cities;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {   
        $cities = [
            'São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Curitiba', 'Salvador',
            'Porto Alegre', 'Brasília', 'Fortaleza', 'Manaus', 'Recife',
            'Goiânia', 'Belém', 'Florianópolis', 'Vitória', 'Campo Grande',
            'João Pessoa', 'Maceió', 'Natal', 'São Luís', 'Teresina',
            'Aracaju', 'Palmas', 'Macapá', 'Porto Velho', 'Boa Vista', 'Rio Branco'
        ];

        foreach ($cities as $city) {
            Cities::create(['name' => $city]);
        }
    }
}

