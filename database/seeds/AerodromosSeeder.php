<?php

use Illuminate\Database\Seeder;

class AerodromosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('aerodromos')->truncate();
        DB::table('aerodromos')->insert([
            [   
                'code'  => 'LPAR',
                'nome'  => 'Base Aérea Alverca',
                'militar'   => true,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPAV',
                'nome'  => 'Base Aérea de São Jacinto - Aveiro',
                'militar'   => true,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPBG',
                'nome'  => 'Bragança',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPBJ',
                'nome'  => 'Beja',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPBR',
                'nome'  => 'Braga',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPCB',
                'nome'  => 'Castelo Branco',
                'militar'   => false,
                'ultraleve' => false
            ],        
            [   
                'code'  => 'LPCH',
                'nome'  => 'Chaves',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPCO',
                'nome'  => 'Coimbra - Bissaya Barreto',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPCS',
                'nome'  => 'Cascais',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPEV',
                'nome'  => 'Évora',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPFA',
                'nome'  => 'Ferreira do Alentejo',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPFC',
                'nome'  => 'Figueira de Cavaleiros',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPFR',
                'nome'  => 'Faro',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPIN',
                'nome'  => 'Espinho',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPJF',
                'nome'  => 'Leiria - José Ferrinho',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPLZ',
                'nome'  => 'Lousã',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPMI',
                'nome'  => 'Mirandela',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPMN',
                'nome'  => 'Amendoeira - Montemor-o-Novo',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPMR',
                'nome'  => 'Base Aérea Monte Real',
                'militar'   => true,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPMT',
                'nome'  => 'Base Aérea Montijo',
                'militar'   => true,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPMU',
                'nome'  => 'Mogadouro',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPOT',
                'nome'  => 'Base Aérea Ota',
                'militar'   => true,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPOV',
                'nome'  => 'Base Aérea Ovar',
                'militar'   => true,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPPM',
                'nome'  => 'Portimão',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPPN',
                'nome'  => 'Proença a Nova',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPPR',
                'nome'  => 'Porto - Sá Carneiro',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPPT',
                'nome'  => 'Lisboa - Humberto Delgado',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPSC',
                'nome'  => 'Santa Cruz',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPSE',
                'nome'  => 'Seia',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPSO',
                'nome'  => 'Ponte de Sôr',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPSR',
                'nome'  => 'Santarém',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPST',
                'nome'  => 'Base Aérea Sintra',
                'militar'   => true,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPTN',
                'nome'  => 'Base Aérea Tancos',
                'militar'   => true,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPVL',
                'nome'  => 'Maia - Vilar de Luz',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPVR',
                'nome'  => 'Vila Real',
                'militar'   => false,
                'ultraleve' => false
            ],
            [   
                'code'  => 'LPVZ',
                'nome'  => 'Viseu',
                'militar'   => false,
                'ultraleve' => false
            ],

            // Pistas Ultraleve
            [   
                'code'  => 'U-AIRPARK',
                'nome'  => 'Alentejo Air Park',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-ALQUEIDAO',
                'nome'  => 'Alqueidão',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-ATOUGUIA',
                'nome'  => 'Atouguia da Baleia',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-AZAMBUJA',
                'nome'  => 'Azambuja',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-BEJA',
                'nome'  => 'Beja UL',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-BENAVENTE',
                'nome'  => 'Benavente',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-CAB_BASTO',
                'nome'  => 'Cabeceiras de Basto',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-CAB_VACA',
                'nome'  => 'Cabeço de Vaca',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-CAMPINHO',
                'nome'  => 'Campinho',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-POMBAL',
                'nome'  => 'Casalinho Pombal',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-CASARAO',
                'nome'  => 'Casarão',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-FAIAS',
                'nome'  => 'Faias',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-CERVAL',
                'nome'  => 'Cerval',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-LAMEIRA',
                'nome'  => 'Herdade da Lameira',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-PEGOES',
                'nome'  => 'Herdade do Pontal - Pegões',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-ZAMBUJEIRA',
                'nome'  => 'Herdade da Zambujeira',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-LAGOS',
                'nome'  => 'Lagos',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-LEZIRIAS',
                'nome'  => 'Lezirias',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-PALMA',
                'nome'  => 'Palma',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-PIAS_LONGAS',
                'nome'  => 'Pias Longas',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-LAUNDOS',
                'nome'  => 'São Miguel de Laúndos',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-TOJEIRA',
                'nome'  => 'Tojeira',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-VALADAS',
                'nome'  => 'Valadas - Ferreira do Zêzere',
                'militar'   => false,
                'ultraleve' => true
            ],
            [   
                'code'  => 'U-VALDONAS',
                'nome'  => 'Valdonas',
                'militar'   => false,
                'ultraleve' => true
            ]
        ]);
    }
}
