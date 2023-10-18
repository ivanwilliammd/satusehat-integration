<?php

return [

    /*
     * This is the name of the table that will be created by the migration and
     * used by the Activity model shipped with this package.
     */
    'log_table_name' => 'satusehat_log',
    'token_table_name' => 'satusehat_token',
    'icd10_table_name' => 'satusehat_icd10',

    /*
     * This is the database connection that will be used by the migration and
     * the Activity model shipped with this following Laravel's database.default
     * If not set, it will use mysql instead.
     */
    'database_connection' => env('DB_CONNECTION', 'mysql'),
];
