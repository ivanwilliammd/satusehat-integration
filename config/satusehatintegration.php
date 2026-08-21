<?php

return [

    /*
     * Table names (prefix via env or use defaults).
     */
    'log_table_name'               => env('SATUSEHAT_LOG_TABLE', 'satusehat_log'),
    'token_table_name'             => env('SATUSEHAT_TOKEN_TABLE', 'satusehat_token'),
    'queue_table_name'             => env('SATUSEHAT_QUEUE_TABLE', 'satusehat_queue'),
    'icd10_table_name'             => env('SATUSEHAT_ICD10_TABLE', 'satusehat_icd10'),
    'kode_wilayah_indonesia_table_name' => env('SATUSEHAT_KODE_WILAYAH_TABLE', 'kode_wilayah_indonesia'),

    /*
     * Transaction logging — log every HTTP call to satusehat_log table.
     * Disable for high-throughput environments where logging overhead matters.
     */
    'log_enabled' => env('SATUSEHAT_LOG_ENABLED', true),

    /*
     * Default user_id written to satusehat_log / satusehat_queue when
     * no authenticated user context is available.
     */
    'log_user_id' => env('SATUSEHAT_LOG_USER_ID', 'system'),

    /*
     * Queue defaults.
     */
    'queue_max_attempts' => env('SATUSEHAT_QUEUE_MAX_ATTEMPTS', 5),

    /*
     * Override the SATUSEHAT environment, organization, ClientID, and ClientSecret to use
     * non environment variable
     */
    'ss_parameter_override' => false,

    /*
     * Database connections.
     */
    'database_connection_master'    => env('DB_CONNECTION_MASTER', 'mysql'),
    'database_connection_satusehat' => env('DB_CONNECTION', 'mysql'),
];
