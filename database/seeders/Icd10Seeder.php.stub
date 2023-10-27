<?php
namespace Database\Seeders;

use JeroenZwart\CsvSeeder\CsvSeeder;
use DB;

class Icd10Seeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct(){
        $this->file = base_path().'/database/seeders/csv/icd10.csv';
        $this->tablename = config('satusehatintegration.icd10_table_name');
        $this->delimiter = ';';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
		DB::disableQueryLog();

		parent::run();
    }
}
