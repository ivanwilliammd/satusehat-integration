<?php

return [
    /*
     * This is the name of the table that will be created by the migration and
     * used by the Activity model shipped with this package.
     */
    'log_table_name' => 'satusehat_log',
    'token_table_name' => 'satusehat_token',

    'icd10_table_name' => 'satusehat_icd10',
    'icd9cm_table_name' => 'satusehat_icd9cm',

    'loinc_table_name' => 'satusehat_loinc',
    'loinc_answer_table_name' => 'satusehat_loinc_answer',

    'kode_wilayah_indonesia_table_name' => 'kode_wilayah_indonesia',

    'snomedct_table_name' => 'satusehat_snomedct',

    'cvx_table_name' => 'satusehat_cvx',

    'ucum_table_name' => 'satusehat_ucum',

    'kfa_table_name' => 'satusehat_kfa',

    'kemkesterm_table_name' => 'satusehat_kemkesterm',

    'fhirr4term_table_name' => 'fhir_r4_term',

    'fhirr4vs_table_name' => 'fhir_r4_vs',

    'kptl_base_table_name' => 'kptl_base',
    'kptl_modifier_table_name' => 'kptl_modifier',
    'kptl_base_modifier_mapping_table_name' => 'kptl_base_modifier_mapping',
    'kptl_kamar_table_name' => 'kptl_kamar',

    'ss_parameter_override' => [
        'enabled' => env('SATUSEHAT_PARAMETER_OVERRIDE', false),
        'driver' => env('SATUSEHAT_PARAMETER_DRIVER', 'env'), // env | database
        'parameters' => [
            'environment' => env('SATUSEHAT_ENVIRONMENT'),
            'organization_id' => env('SATUSEHAT_ORGANIZATION_ID'),
            'organization_name' => env('SATUSEHAT_ORGANIZATION_NAME'),
            'client_id' => env('SATUSEHAT_CLIENT_ID'),
            'client_secret' => env('SATUSEHAT_CLIENT_SECRET'),
        ],
    ],

    'tenancy' => [
        'enabled' => env('SATUSEHAT_MULTI_TENANT', false),
        'default_team_key' => env('SATUSEHAT_DEFAULT_TEAM'),
        'teams_table_name' => env('SATUSEHAT_TEAMS_TABLE', 'satusehat_teams'),
        'team_key_column' => env('SATUSEHAT_TEAMS_KEY_COLUMN', 'team_key'),
        'connection' => env('SATUSEHAT_TEAMS_CONNECTION', env('DB_CONNECTION_MASTER', 'mysql')),
        'cache_ttl' => env('SATUSEHAT_TEAMS_CACHE_TTL', 300),
        'columns' => [
            'client_id' => env('SATUSEHAT_TEAMS_CLIENT_ID_COL', 'client_id'),
            'client_secret' => env('SATUSEHAT_TEAMS_CLIENT_SECRET_COL', 'client_secret'),
            'organization_id' => env('SATUSEHAT_TEAMS_ORGANIZATION_ID_COL', 'organization_id'),
            'organization_name' => env('SATUSEHAT_TEAMS_ORGANIZATION_NAME_COL', 'organization_name'),
            'environment' => env('SATUSEHAT_TEAMS_ENVIRONMENT_COL', 'environment'),
        ],
    ],

    /*
     * Override the SATUSEHAT environment, organization, ClientID, and ClientSecret to use
     * non environment variable
     */

    /*
     * This is the database connection that will be used by the migration and
     * the Activity model shipped with this following Laravel's database.default
     * If not set, it will use mysql instead.
     */
    'database_connection_master' => env('DB_CONNECTION_MASTER', 'mysql'),
    'database_connection_satusehat' => env('DB_CONNECTION', 'mysql'),
];
