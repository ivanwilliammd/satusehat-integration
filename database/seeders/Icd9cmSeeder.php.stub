<?php

namespace Database\Seeders;

use JeroenZwart\CsvSeeder\CsvSeeder;
use DB;

class Icd9cmSeeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct(){
        $this->file = base_path().'/database/seeders/csv/icd9cm.csv';
        $this->tablename = config('satusehatintegration.icd9cm_table_name');
        $this->delimiter = ',';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
		DB::disableQueryLog();

		parent::run();
    }
}
