<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = array(
			array('code' => 'US', 'name' => 'United States'),
			array('code' => 'CA', 'name' => 'Canada'),
			array('code' => 'AU', 'name' => 'Australia'),
			array('code' => 'AT', 'name' => 'Austria'),
			
			array('code' => 'BY', 'name' => 'Belarus'),
			array('code' => 'BE', 'name' => 'Belgium'),
			
			array('code' => 'BA', 'name' => 'Bosnia and Herzegovina'),
			
			array('code' => 'BG', 'name' => 'Bulgaria'),
			
			array('code' => 'HR', 'name' => 'Croatia (Hrvatska)'),
			
			array('code' => 'CY', 'name' => 'Cyprus'),
			array('code' => 'CZ', 'name' => 'Czech Republic'),
			
			array('code' => 'DK', 'name' => 'Denmark'),
			
			array('code' => 'FI', 'name' => 'Finland'),
			array('code' => 'FR', 'name' => 'France'),
			array('code' => 'FX', 'name' => 'France, Metropolitan'),
			
			array('code' => 'GE', 'name' => 'Georgia'),
			array('code' => 'DE', 'name' => 'Germany'),
			
			array('code' => 'GI', 'name' => 'Gibraltar'),
			array('code' => 'GR', 'name' => 'Greece'),
			array('code' => 'GL', 'name' => 'Greenland'),
			
			array('code' => 'HU', 'name' => 'Hungary'),
			array('code' => 'IS', 'name' => 'Iceland'),
			
			array('code' => 'IE', 'name' => 'Ireland'),
			
			array('code' => 'IT', 'name' => 'Italy'),
			
			array('code' => 'LV', 'name' => 'Latvia'),
			
			array('code' => 'LI', 'name' => 'Liechtenstein'),
			array('code' => 'LT', 'name' => 'Lithuania'),
			array('code' => 'LU', 'name' => 'Luxembourg'),
			
			array('code' => 'MK', 'name' => 'Macedonia'),
			
			array('code' => 'MC', 'name' => 'Monaco'),
			
			array('code' => 'NL', 'name' => 'Netherlands'),
			array('code' => 'AN', 'name' => 'Netherlands Antilles'),
			
			array('code' => 'NO', 'name' => 'Norway'),
			array('code' => 'OM', 'name' => 'Oman'),
			
			array('code' => 'PL', 'name' => 'Poland'),
			array('code' => 'PT', 'name' => 'Portugal'),
			
			array('code' => 'QA', 'name' => 'Qatar'),
			
			array('code' => 'RO', 'name' => 'Romania'),
			array('code' => 'RU', 'name' => 'Russian Federation'),
			
			array('code' => 'SM', 'name' => 'San Marino'),
			
			
			array('code' => 'RS', 'name' => 'Serbia'),
			
			array('code' => 'SK', 'name' => 'Slovakia'),
			array('code' => 'SI', 'name' => 'Slovenia'),
			
			array('code' => 'ES', 'name' => 'Spain'),
			
			array('code' => 'SE', 'name' => 'Sweden'),
			array('code' => 'CH', 'name' => 'Switzerland'),
			
			array('code' => 'TR', 'name' => 'Turkey'),
			
			array('code' => 'UA', 'name' => 'Ukraine'),
			array('code' => 'AE', 'name' => 'United Arab Emirates'),
			array('code' => 'GB', 'name' => 'United Kingdom'),
			
		);

		DB::table('countries')->insert($countries);

    }
}
