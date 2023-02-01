<?php

use App\Accommodation;
use App\Addon;
use App\Meta;
use App\Operator;
use App\Package;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(AddonsTableSeeder::class);
        $this->call(OperatorsTableSeeder::class);
        $this->call(AccommodationsTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(MetasTableSeeder::class);

        $this->command->info('Database seeded!');
    }
}

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@skihire2u.com',
            'password' => Hash::make('admin@123#'),
        ]);

        $user = User::create([
            'name' => 'Paul Sepe',
            'email' => 'paul.sepe@webee.com.mt',
            'password' => Hash::make('test1234'),
        ]);
    }
}

class AddonsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('addons')->delete();

        $addons = [
            [
                'name' => 'boots',
                'price' => 4,
            ],
            [
                'name' => 'helmet',
                'price' => 4,
            ],
            [
                'name' => 'insurance',
                'price' => 2,
            ],
        ];

        Addon::insert($addons);
    }
}

class OperatorsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('operators')->delete();

        $operators = [
            ['name' => '- Independent -'],
            ['name' => '9, Les Ecureuils'],
            ['name' => 'Alpine Bliss', 'web_address' => 'http://www.alpinebliss.co.uk/'],
            ['name' => 'Alpine Breaks', 'web_address' => 'http://www.thealpinechaletcompany.com/'],
            ['name' => 'Alpine Heart', 'web_address' => 'http://www.alpineheart.com/'],
            ['name' => 'Alpine Majestic'],
            ['name' => 'Alpine Quests', 'web_address' => 'http://www.alpinequests.com/'],
            ['name' => 'Chalet Anna'],
            ['name' => 'Chalet Bises Blanches'],
            ['name' => 'Chalet Blanc', 'web_address' => 'http://www.chalet-blanc.co.uk ', 'postal_address' => 'Suvay 74360 La Chapelle d\'Abondance France'],
            ['name' => 'Chalet Cannelle', 'postal_address' => 'Suvay, La Chapelle d\'Abondance, 74360'],
            ['name' => 'Chalet Cateriane', 'is_active' => false],
            ['name' => 'Chalet Chocolat', 'web_address' => 'http://www.skichaletchocolat.com/'],
            ['name' => 'Chalet Isobel', 'web_address' => 'http://www.chaletisobel.com/'],
            ['name' => 'Chalet La Petite Bev', 'postal_address' => 'Petite Chatel'],
            ['name' => 'Chalet Le Tadorne'],
            ['name' => 'Chalet Les Loups', 'postal_address' => 'Petite Chatel'],
            ['name' => 'Chalet Les Sapins', 'postal_address' => 'Les Dents Blanches'],
            ['name' => 'Chalet Magui'],
            ['name' => 'Chalet Montbeliard', 'is_active' => false],
            ['name' => 'Chalet Nina', 'web_address' => 'http://www.chaletnina.com/'],
            ['name' => 'Chalet Norm'],
            ['name' => 'Chalet Pique Vert', 'postal_address' => 'Chalet Pique Vert, 886 Route de Roitet, Chatel'],
            ['name' => 'Chatel Chalets (Andy Kimmerling)'],
            ['name' => 'Chris Francis', 'postal_address' => 'Chalet Pascal'],
            ['name' => 'Clarian Chalets'],
            ['name' => 'Lawrence Kormornick', 'web_address' => 'http://www.chaletchatel.com', 'postal_address' => 'Route de la Dranse, Chatel', 'is_active' => false],
            ['name' => 'Le Mouflon, Apt 24, Les Jonquilles'],
            ['name' => 'Mountain Addiction', 'web_address' => 'http://www.mountainaddiction.co.uk/', 'is_active' => false],
            ['name' => 'Mountain Magic'],
            ['name' => 'Nine & Tenne', 'web_address' => 'http://www.nine-tenne.com ', 'postal_address' => 'Chatel'],
            ['name' => 'Penthouse Caribou', 'web_address' => 'http://www.penthousecaribou.com/'],
            ['name' => 'Self Catered Chatel'],
            ['name' => 'Ski Bike or Hike', 'web_address' => 'http://www.skibikeorhike.com/'],
            ['name' => 'Ski Diamond', 'web_address' => 'http://www.skidiamond.com/'],
            ['name' => 'Ski Goddess', 'web_address' => 'http://www.skigoddess.co.uk/'],
            ['name' => 'Ski PDS - Mustang Chalet'],
            ['name' => 'Ski Ride Chatel'],
            ['name' => 'Skialot', 'web_address' => 'http://www.skialot.com/'],
            ['name' => 'Skichatel.co.uk', 'web_address' => 'http://www.skichatel.co.uk/'],
            ['name' => 'Telski', 'web_address' => 'http://www.telskitrailblazers.com/'],
        ];

        foreach ($operators as $operator) {
            Operator::create($operator);
        }

        //Operator::insert($operators);
    }
}

class AccommodationsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('accommodations')->delete();

        $accommodations = [
            [
                'operator_id' => 1,
                'name' => 'Not Listed',
            ],
            [
                'operator_id' => 2,
                'name' => '9, Les Ecureuils',
                'postal_address' => 'La Chapelle d\'Abondance',
                'discount' => '10',
            ],
            [
                'operator_id' => 3,
                'name' => 'Chalet Grand Coeur',
                'discount' => '10',
            ],
            [
                'operator_id' => 4,
                'name' => 'Chalet 1972',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 4,
                'name' => 'Chalet Lac de Vonnes',
                'discount' => '10',
            ],
            [
                'operator_id' => 4,
                'name' => 'Chalet Linga',
                'postal_address' => 'Linga',
                'discount' => '10',
            ],
            [
                'operator_id' => 4,
                'name' => 'Chalet Simplet',
                'discount' => '10',
            ],
            [
                'operator_id' => 4,
                'name' => 'Chalet Timide',
                'discount' => '10',
            ],
            [
                'operator_id' => 5,
                'name' => 'Chalet Les Erines',
                'discount' => '10',
            ],
            [
                'operator_id' => 6,
                'name' => 'Chalet Soleil',
                'discount' => '10',
            ],
            [
                'operator_id' => 7,
                'name' => 'Chalet Aaron',
                'discount' => '10',
            ],
            [
                'operator_id' => 7,
                'name' => 'Chalet Coeur du Bois',
                'discount' => '10',
            ],
            [
                'operator_id' => 7,
                'name' => 'Chalet Helen',
                'discount' => '10',
            ],
            [
                'operator_id' => 7,
                'name' => 'Les Frangins',
                'discount' => '10',
            ],
            [
                'operator_id' => 8,
                'name' => 'Chalet Anna',
                'discount' => '10',
            ],
            [
                'operator_id' => 9,
                'name' => 'Chalet Bises Blanches',
                'discount' => '10',
            ],
            [
                'operator_id' => 10,
                'name' => 'Chalet Blanc',
                'postal_address' => 'Suvay 74360 La Chapelle d\'Abondance France',
                'discount' => '10',
            ],
            [
                'operator_id' => 11,
                'name' => 'Chalet Cannelle',
                'postal_address' => 'Suvay, La Chapelle d\'Abondance, 74360',
                'discount' => '10',
            ],
            [
                'operator_id' => 12,
                'name' => 'Chalet Cateriane',
                'discount' => '10',
            ],
            [
                'operator_id' => 12,
                'name' => 'Chalet Montbeliard',
                'discount' => '10',
            ],
            [
                'operator_id' => 13,
                'name' => 'Chalet Chocolat',
                'discount' => '10',
            ],
            [
                'operator_id' => 14,
                'name' => 'Chalet Isobel',
                'discount' => '10',
            ],
            [
                'operator_id' => 15,
                'name' => 'Chalet La Petite Bev',
                'discount' => '10',
            ],
            [
                'operator_id' => 16,
                'name' => 'Chalet Le Tadorne',
                'discount' => '10',
            ],
            [
                'operator_id' => 17,
                'name' => 'Chalet Les Loups',
                'discount' => '10',
            ],
            [
                'operator_id' => 18,
                'name' => 'Chalet Les Sapins',
                'discount' => '10',
            ],
            [
                'operator_id' => 19,
                'name' => 'Chalet Magui',
                'discount' => '10',
            ],
            [
                'operator_id' => 20,
                'name' => 'Chalet Montbeliard',
                'discount' => '10',
            ],
            [
                'operator_id' => 21,
                'name' => 'Chalet Nina',
                'discount' => '10',
            ],
            [
                'operator_id' => 22,
                'name' => 'Chalet Norm',
                'discount' => '10',
            ],
            [
                'operator_id' => 23,
                'name' => 'Chalet Pique Vert',
                'postal_address' => 'Chalet Pique Vert, 886 Route de Roitet, Chatel',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => '11a Les Sorbiers',
                'postal_address' => ' La Chapelle',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Apartment Clos des Orson 22',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Apartment Erin',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Apartment Les Arolettes 13',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Apartment Yeti 4',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Apartments Clos des Orson 2',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Bois Alpage',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Bois Mazot',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Bois Neige',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Authentique',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Bonnevaux',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Cascade (La Pesse)',
                'postal_address' => 'La Pesse',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Cascade (Les Dents Blanche)',
                'postal_address' => 'Le Jardy',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Chanterelle',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Chatelard',
                'postal_address' => 'Le Jardy',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Chez Bev',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Dow',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Du Caire',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Enneige',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Fleur d\'Ecosse',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Enneige',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Fleurs des Montagnes',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Gallois',
                'postal_address' => 'Route du Linga',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Genepy',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Geranium',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Gooding',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Gretel',
                'postal_address' => 'Chatel',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Hansel',
                'postal_address' => 'Chatel',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Kramer',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Laurette',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Laurette',
                'postal_address' => 'Linga',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Le Foret',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Les Chasseurs',
                'postal_address' => 'Le Jardy',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Les Clarines',
                'discount' => '10',
                'notes' => 'Petit Chatel',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Les Fleurs',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Les Herrissons',
                'postal_address' => 'Le Jardy',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Liseron',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Louvetaux',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Ma Reve',
                'postal_address' => 'Le Jardy',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Margaux',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Oranger',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Page',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Pascal',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Pelerin',
                'postal_address' => 'Richebourg',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Ravigote',
                'postal_address' => 'Below Chalet Gallois',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Sans Souci',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Sur Les Chars',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Tonton',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Chalet Varlope',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Florimontagne No B6',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Florimontagne No D6',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Les Sorbiers No 11a',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Pags Bas',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Pags Haut',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Florimontagne No 6',
                'discount' => '10',
                'notes' => 'Opposite planet Glisse',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Le Grand Roc 1',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Le Grand Roc 2',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Armoises 13',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Armoises No. 13',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Balcons de Chatel No. 14',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Balcons de Chatel No. 21',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Balcons de Chatel No. 4',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Fermes de Jules No. 11a',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Pins No. 12',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Pins No. 3',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Pins No. 4',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Pins No. 5',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Pins No. 6',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Pins No. 9',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Seilles 2b',
                'discount' => '10',
                'notes' => 'Just beyond Super Chatel lift',
                'is_active' => false,
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Les Seilles, 1B',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Yeti 3, 18',
                'discount' => '10',
            ],
            [
                'operator_id' => 24,
                'name' => 'Residence Yeti 6 No.1',
                'discount' => '10',
            ],
            [
                'operator_id' => 25,
                'name' => 'Chalet Pascal',
                'discount' => '10',
            ],
            [
                'operator_id' => 26,
                'name' => 'La Grange au Merle',
                'discount' => '10',
            ],
            [
                'operator_id' => 27,
                'name' => 'Chalet Le Reve',
                'postal_address' => 'Chemin de Vorres',
                'discount' => '10',
            ],
            [
                'operator_id' => 28,
                'name' => 'Le Mouflon, Apt 24',
                'discount' => '10',
            ],
            [
                'operator_id' => 29,
                'name' => 'Chalet Timide',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Beauboo',
                'discount' => '10',
                'notes' => 'Nr Chalet Grand Couer',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Cachette',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Cascade (Nr Jet D\'eau)',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Foret des Reves',
                'discount' => '10',
                'notes' => 'Nr Chalet Grand Couer',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Hayjam',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet La Grange au Merle',
                'postal_address' => '900 Route de Roitet',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet La vie en Roos',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Mont de Grange',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Nobel Maison',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Phoenix',
                'discount' => '10',
                'notes' => 'y',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Ruisseau',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Chalet Sapin (Nr Jet D\'eau)',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 118',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 120',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 122',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 124',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 146',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 16/109',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 172',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 190',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 20',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 36',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 64',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 66',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 70',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 72',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 770',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 79',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 81',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 83',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 85',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 87',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 89',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Le Jet D\'eau 92',
                'discount' => '10',
            ],
            [
                'operator_id' => 30,
                'name' => 'Residence Chamois D\'or',
                'discount' => '10',
            ],
            [
                'operator_id' => 31,
                'name' => 'Chalet Belle Vache',
                'discount' => '10',
            ],
            [
                'operator_id' => 31,
                'name' => 'Chalet Folie',
                'discount' => '10',
            ],
            [
                'operator_id' => 31,
                'name' => 'Chalet L\'Hermitage',
                'postal_address' => 'Chatel',
                'discount' => '10',
            ],
            [
                'operator_id' => 31,
                'name' => 'Chalet Marguerite',
                'discount' => '10',
            ],
            [
                'operator_id' => 31,
                'name' => 'Chalet Nine & Tenne',
                'postal_address' => 'Chatel',
                'discount' => '10',
            ],
            [
                'operator_id' => 32,
                'name' => 'Penthouse Caribou',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Chalet Cateriane',
                'postal_address' => 'Route de la Dranse',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Chalet Montbeliard',
                'postal_address' => 'Route de la Bechigne',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Chalet Tarine',
                'postal_address' => 'Route de la Bechigne',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Ferme Bois Alpage',
                'postal_address' => 'Suvay',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Ferme Bois Grange',
                'postal_address' => 'Suvay',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Ferme Bois Mazot',
                'postal_address' => 'Suvay',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Ferme Bois Neige',
                'postal_address' => 'Suvay',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Pag\'s Bas',
                'postal_address' => 'Route de la Bechigne',
                'discount' => '10',
            ],
            [
                'operator_id' => 33,
                'name' => 'Pag\'s Bas',
                'postal_address' => 'Route de la Bechigne',
                'discount' => '10',
                'is_active' => false,
            ],
            [
                'operator_id' => 33,
                'name' => 'Pag\'s Haut',
                'postal_address' => 'Route de la Bechigne',
                'discount' => '10',
            ],
            [
                'operator_id' => 34,
                'name' => 'Chalet Le Dragon',
            ],
            [
                'operator_id' => 35,
                'name' => 'La Corniche',
                'discount' => '10',
            ],
            [
                'operator_id' => 36,
                'name' => 'Add Later',
                'discount' => '10',
            ],
            [
                'operator_id' => 37,
                'name' => 'Mustang Chalet',
                'postal_address' => '1496 Route du Linga',
                'discount' => '10',
            ],
            [
                'operator_id' => 38,
                'name' => 'Chalet Les Sapins',
            ],
            [
                'operator_id' => 38,
                'name' => 'Chalet Skean Dhu',
            ],
            [
                'operator_id' => 38,
                'name' => 'Chalet Tzigane',
            ],
            [
                'operator_id' => 39,
                'name' => 'Chalet Chataigne',
                'postal_address' => '438 Route de Boude',
                'discount' => '10',
            ],
            [
                'operator_id' => 40,
                'name' => 'Independent',
                'discount' => '10',
            ],
            [
                'operator_id' => 41,
                'name' => 'Chalet L\'Ancolie',
                'discount' => '10',
            ],
        ];

        foreach ($accommodations as $accommodation) {
            Accommodation::create($accommodation);
        }
    }
}

class PackagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('packages')->delete();

        $packages = [
            [
                'name' => 'Adult Skis and Poles',
                'level' => 'Recreational',
                'type' => 'Adult',
                'category' => 'Adult Skis',
                'prices' => json_encode([
                    '1' => [
                        'flat' => '24.00',
                        'boots' => '28.00',
                    ],
                    '2' => [
                        'flat' => '36.00',
                        'boots' => '41.00',
                    ],
                    '3' => [
                        'flat' => '49.00',
                        'boots' => '56.00',
                    ],
                    '4' => [
                        'flat' => '64.00',
                        'boots' => '72.00',
                    ],
                    '5' => [
                        'flat' => '73.00',
                        'boots' => '83.00',
                    ],
                    '6' => [
                        'flat' => '80.00',
                        'boots' => '91.00',
                    ],
                    '7' => [
                        'flat' => '86.00',
                        'boots' => '98.00',
                    ],
                    '8' => [
                        'flat' => '92.00',
                        'boots' => '105.00',
                    ],
                    '9' => [
                        'flat' => '98.00',
                        'boots' => '112.00',
                    ],
                    '10' => [
                        'flat' => '104.00',
                        'boots' => '119.00',
                    ],
                    '11' => [
                        'flat' => '110.00',
                        'boots' => '126.00',
                    ],
                    '12' => [
                        'flat' => '116.00',
                        'boots' => '133.00',
                    ],
                    '13' => [
                        'flat' => '122.00',
                        'boots' => '140.00',
                    ],
                    '14' => [
                        'flat' => '128.00',
                        'boots' => '147.00',
                    ],
                    '15' => [
                        'flat' => '134.00',
                        'boots' => '154.00',
                    ],
                ]),
                'notes' => 'This is our most popular range of skis and will accommodate most skiers whether you are a complete beginner or looking to progress to the next level riding hard and fast on blues and reds.',
                'image_url' => '/images/adult-ski-recreational.jpg',
            ],
            [
                'name' => 'Adult Skis and Poles',
                'level' => 'Performance',
                'type' => 'Adult',
                'category' => 'Adult Skis',
                'prices' => json_encode([
                    '1' => [
                        'flat' => '28.00',
                        'boots' => '33.00',
                    ],
                    '2' => [
                        'flat' => '43.00',
                        'boots' => '50.00',
                    ],
                    '3' => [
                        'flat' => '57.00',
                        'boots' => '67.00',
                    ],
                    '4' => [
                        'flat' => '75.00',
                        'boots' => '87.00',
                    ],
                    '5' => [
                        'flat' => '86.00',
                        'boots' => '100.00',
                    ],
                    '6' => [
                        'flat' => '94.00',
                        'boots' => '109.00',
                    ],
                    '7' => [
                        'flat' => '101.00',
                        'boots' => '118.00',
                    ],
                    '8' => [
                        'flat' => '108.00',
                        'boots' => '127.00',
                    ],
                    '9' => [
                        'flat' => '115.00',
                        'boots' => '136.00',
                    ],
                    '10' => [
                        'flat' => '122.00',
                        'boots' => '145.00',
                    ],
                    '11' => [
                        'flat' => '129.00',
                        'boots' => '154.00',
                    ],
                    '12' => [
                        'flat' => '136.00',
                        'boots' => '163.00',
                    ],
                    '13' => [
                        'flat' => '143.00',
                        'boots' => '172.00',
                    ],
                    '14' => [
                        'flat' => '150.00',
                        'boots' => '181.00',
                    ],
                    '15' => [
                        'flat' => '157.00',
                        'boots' => '190.00',
                    ],
                ]),
                'notes' => 'These are generally high-end skis and would suit someone with at least 12 weeks of skiing experience.',
                'image_url' => '/images/adult-ski-performance.jpg',
            ],
            [
                'name' => 'Adult Skis and Poles',
                'level' => 'Expert',
                'type' => 'Adult',
                'category' => 'Adult Skis',
                'prices' => json_encode([
                    '1' => [
                        'flat' => '35.00',
                        'boots' => '40.00',
                    ],
                    '2' => [
                        'flat' => '52.00',
                        'boots' => '64.00',
                    ],
                    '3' => [
                        'flat' => '70.00',
                        'boots' => '87.00',
                    ],
                    '4' => [
                        'flat' => '92.00',
                        'boots' => '107.00',
                    ],
                    '5' => [
                        'flat' => '105.00',
                        'boots' => '125.00',
                    ],
                    '6' => [
                        'flat' => '115.00',
                        'boots' => '137.00',
                    ],
                    '7' => [
                        'flat' => '125.00',
                        'boots' => '148.00',
                    ],
                    '8' => [
                        'flat' => '135.00',
                        'boots' => '159.00',
                    ],
                    '9' => [
                        'flat' => '145.00',
                        'boots' => '170.00',
                    ],
                    '10' => [
                        'flat' => '155.00',
                        'boots' => '181.00',
                    ],
                    '11' => [
                        'flat' => '165.00',
                        'boots' => '192.00',
                    ],
                    '12' => [
                        'flat' => '175.00',
                        'boots' => '203.00',
                    ],
                    '13' => [
                        'flat' => '185.00',
                        'boots' => '214.00',
                    ],
                    '14' => [
                        'flat' => '195.00',
                        'boots' => '225.00',
                    ],
                    '15' => [
                        'flat' => '205.00',
                        'boots' => '236.00',
                    ],
                ]),
                'notes' => 'These really are top end skis, they feature our brand new Salomon BBR ski, the X Kart Race carver and most of the Storm range of specialist skis.',
                'image_url' => '/images/adult-ski-expert.jpg',
            ],
            [
                'name' => 'Adult Snowboard',
                'type' => 'Adult',
                'category' => 'Adult Snowboards',
                'prices' => json_encode([
                    '1' => [
                        'flat' => '29.00',
                        'boots' => '33.00',
                    ],
                    '2' => [
                        'flat' => '43.00',
                        'boots' => '50.00',
                    ],
                    '3' => [
                        'flat' => '59.00',
                        'boots' => '67.00',
                    ],
                    '4' => [
                        'flat' => '76.00',
                        'boots' => '87.00',
                    ],
                    '5' => [
                        'flat' => '87.00',
                        'boots' => '100.00',
                    ],
                    '6' => [
                        'flat' => '95.00',
                        'boots' => '109.00',
                    ],
                    '7' => [
                        'flat' => '103.00',
                        'boots' => '118.00',
                    ],
                    '8' => [
                        'flat' => '111.00',
                        'boots' => '127.00',
                    ],
                    '9' => [
                        'flat' => '119.00',
                        'boots' => '136.00',
                    ],
                    '10' => [
                        'flat' => '127.00',
                        'boots' => '145.00',
                    ],
                    '11' => [
                        'flat' => '135.00',
                        'boots' => '154.00',
                    ],
                    '12' => [
                        'flat' => '143.00',
                        'boots' => '163.00',
                    ],
                    '13' => [
                        'flat' => '151.00',
                        'boots' => '172.00',
                    ],
                    '14' => [
                        'flat' => '159.00',
                        'boots' => '181.00',
                    ],
                    '15' => [
                        'flat' => '167.00',
                        'boots' => '190.00',
                    ],
                ]),
                'notes' => 'There is only one category, we have a wide range of all mountain boards will suit all snowboarders from complete beginner to the advanced who want to do something specialised such as riding in the parks or spending their time off-piste.',
                'image_url' => '/images/adult-snowboard.jpg',
            ],
            [
                'name' => 'Child Skis and Poles, 70 - 105CM',
                'type' => 'Child',
                'category' => 'Child Skis',
                'prices' => json_encode([
                    '1' => [
                        'flat' => '11.00',
                        'boots' => '15.00',
                    ],
                    '2' => [
                        'flat' => '17.00',
                        'boots' => '23.00',
                    ],
                    '3' => [
                        'flat' => '22.00',
                        'boots' => '31.00',
                    ],
                    '4' => [
                        'flat' => '29.00',
                        'boots' => '41.00',
                    ],
                    '5' => [
                        'flat' => '33.00',
                        'boots' => '47.00',
                    ],
                    '6' => [
                        'flat' => '36.00',
                        'boots' => '51.00',
                    ],
                    '7' => [
                        'flat' => '39.00',
                        'boots' => '55.00',
                    ],
                    '8' => [
                        'flat' => '42.00',
                        'boots' => '59.00',
                    ],
                    '9' => [
                        'flat' => '45.00',
                        'boots' => '63.00',
                    ],
                    '10' => [
                        'flat' => '48.00',
                        'boots' => '67.00',
                    ],
                    '11' => [
                        'flat' => '51.00',
                        'boots' => '71.00',
                    ],
                    '12' => [
                        'flat' => '54.00',
                        'boots' => '75.00',
                    ],
                    '13' => [
                        'flat' => '57.00',
                        'boots' => '79.00',
                    ],
                    '14' => [
                        'flat' => '60.00',
                        'boots' => '83.00',
                    ],
                    '15' => [
                        'flat' => '63.00',
                        'boots' => '87.00',
                    ],
                ]),
                'notes' => 'No cheap unbranded rubbish that inhibit your children\'s ski development, we use only top quality branded skis.',
                'image_url' => '/images/child-ski.jpg',
            ],
            [
                'name' => 'Child Skis and Poles, 106CM and above',
                'type' => 'Child',
                'category' => 'Child Skis',
                'prices' => json_encode([
                    '1' => [
                        'flat' => '13.00',
                        'boots' => '18.00',
                    ],
                    '2' => [
                        'flat' => '20.00',
                        'boots' => '26.00',
                    ],
                    '3' => [
                        'flat' => '27.00',
                        'boots' => '36.00',
                    ],
                    '4' => [
                        'flat' => '35.00',
                        'boots' => '46.00',
                    ],
                    '5' => [
                        'flat' => '40.00',
                        'boots' => '53.00',
                    ],
                    '6' => [
                        'flat' => '44.00',
                        'boots' => '58.00',
                    ],
                    '7' => [
                        'flat' => '47.00',
                        'boots' => '63.00',
                    ],
                    '8' => [
                        'flat' => '50.00',
                        'boots' => '68.00',
                    ],
                    '9' => [
                        'flat' => '53.00',
                        'boots' => '73.00',
                    ],
                    '10' => [
                        'flat' => '56.00',
                        'boots' => '78.00',
                    ],
                    '11' => [
                        'flat' => '59.00',
                        'boots' => '83.00',
                    ],
                    '12' => [
                        'flat' => '62.00',
                        'boots' => '88.00',
                    ],
                    '13' => [
                        'flat' => '65.00',
                        'boots' => '93.00',
                    ],
                    '14' => [
                        'flat' => '68.00',
                        'boots' => '98.00',
                    ],
                    '15' => [
                        'flat' => '71.00',
                        'boots' => '103.00',
                    ],
                ]),
                'notes' => 'No cheap unbranded rubbish that inhibit your children\'s ski development, we use only top quality branded skis.',
                'image_url' => '/images/child-ski.jpg',
            ],
            [
                'name' => 'Child Snowboard',
                'type' => 'Child',
                'category' => 'Child Snowboards',
                'prices' => json_encode([
                    '1' => [
                        'flat' => '23.00',
                        'boots' => '26.00',
                    ],
                    '2' => [
                        'flat' => '35.00',
                        'boots' => '40.00',
                    ],
                    '3' => [
                        'flat' => '47.00',
                        'boots' => '53.00',
                    ],
                    '4' => [
                        'flat' => '61.00',
                        'boots' => '69.00',
                    ],
                    '5' => [
                        'flat' => '70.00',
                        'boots' => '80.00',
                    ],
                    '6' => [
                        'flat' => '76.00',
                        'boots' => '87.00',
                    ],
                    '7' => [
                        'flat' => '82.00',
                        'boots' => '94.00',
                    ],
                    '8' => [
                        'flat' => '88.00',
                        'boots' => '101.00',
                    ],
                    '9' => [
                        'flat' => '94.00',
                        'boots' => '108.00',
                    ],
                    '10' => [
                        'flat' => '100.00',
                        'boots' => '115.00',
                    ],
                    '11' => [
                        'flat' => '106.00',
                        'boots' => '122.00',
                    ],
                    '12' => [
                        'flat' => '112.00',
                        'boots' => '129.00',
                    ],
                    '13' => [
                        'flat' => '118.00',
                        'boots' => '136.00',
                    ],
                    '14' => [
                        'flat' => '124.00',
                        'boots' => '143.00',
                    ],
                    '15' => [
                        'flat' => '130.00',
                        'boots' => '150.00',
                    ],
                ]),
                'notes' => 'No cheap unbranded rubbish that inhibit your children\'s ski development, we use only top quality branded snowboards.',
                'image_url' => '/images/child-snowboard.jpg',
            ],
            [
                'name' => 'X Country Skis and Poles',
                'type' => 'Adult',
                'category' => 'X Country Skis',
                'prices' => json_encode([

                    '1' => [
                        'flat' => '20.00',
                        'boots' => '20.00',
                    ],
                    '2' => [
                        'flat' => '33.00',
                        'boots' => '33.00',
                    ],
                    '3' => [
                        'flat' => '40.00',
                        'boots' => '40.00',
                    ],
                    '4' => [
                        'flat' => '46.00',
                        'boots' => '46.00',
                    ],
                    '5' => [
                        'flat' => '53.00',
                        'boots' => '53.00',
                    ],
                    '6' => [
                        'flat' => '59.00',
                        'boots' => '59.00',
                    ],
                    '7' => [
                        'flat' => '65.00',
                        'boots' => '65.00',
                    ],
                    '8' => [
                        'flat' => '70.00',
                        'boots' => '70.00',
                    ],
                    '9' => [
                        'flat' => '75.00',
                        'boots' => '75.00',
                    ],
                    '10' => [
                        'flat' => '80.00',
                        'boots' => '80.00',
                    ],
                    '11' => [
                        'flat' => '85.00',
                        'boots' => '85.00',
                    ],
                    '12' => [
                        'flat' => '90.00',
                        'boots' => '90.00',
                    ],
                    '13' => [
                        'flat' => '95.00',
                        'boots' => '95.00',
                    ],
                    '14' => [
                        'flat' => '100.00',
                        'boots' => '100.00',
                    ],
                    '15' => [
                        'flat' => '105.00',
                        'boots' => '105.00',
                    ],
                ]),
                'notes' => 'We offer the same top branded skis for our X-Country range, with a wealth of tracks around the valley you will not be disappointed.',
                'image_url' => '/images/x-country.png',
            ],
            [
                'name' => 'Snowshoes',
                'type' => 'Adult',
                'category' => 'X Country Skis',
                'prices' => json_encode([
                    '1' => [
                        'flat' => '10.00',
                        'boots' => '10.00',
                    ],
                    '2' => [
                        'flat' => '20.00',
                        'boots' => '20.00',
                    ],
                    '3' => [
                        'flat' => '29.00',
                        'boots' => '29.00',
                    ],
                    '4' => [
                        'flat' => '38.00',
                        'boots' => '38.00',
                    ],
                    '5' => [
                        'flat' => '46.00',
                        'boots' => '46.00',
                    ],
                    '6' => [
                        'flat' => '52.00',
                        'boots' => '52.00',
                    ],
                    '7' => [
                        'flat' => '57.00',
                        'boots' => '57.00',
                    ],
                    '8' => [
                        'flat' => '62.00',
                        'boots' => '62.00',
                    ],
                    '9' => [
                        'flat' => '67.00',
                        'boots' => '67.00',
                    ],
                    '10' => [
                        'flat' => '72.00',
                        'boots' => '72.00',
                    ],
                    '11' => [
                        'flat' => '77.00',
                        'boots' => '77.00',
                    ],
                    '12' => [
                        'flat' => '82.00',
                        'boots' => '82.00',
                    ],
                    '13' => [
                        'flat' => '87.00',
                        'boots' => '87.00',
                    ],
                    '14' => [
                        'flat' => '92.00',
                        'boots' => '92.00',
                    ],
                    '15' => [
                        'flat' => '97.00',
                        'boots' => '97.00',
                    ],
                ]),
                'notes' => 'Our high quality snowshoes will take you all over the mountain and let you make the most of your time in the Alps',
                'image_url' => '/images/snowshoes.png',
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}

class MetasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('metas')->delete();

        $metas = [
            [
                'key' => 'helmet_prices',
                'value' => json_encode(['5.0', '5.0']),
            ],
            [
                'key' => 'helmet_increments',
                'value' => json_encode(['1.0', '2.0']),
            ],
            [
                'key' => 'insurance_increments',
                'value' => json_encode(['1.0', '2.0']),
            ],
        ];

        foreach ($metas as $meta) {
            Meta::create($meta);
        }
    }
}
