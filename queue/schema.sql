-- satusehat-integration: Queue table schema
-- Compatible with: SQLite, MySQL 5.7+, PostgreSQL 12+
--
-- SQLite:
--   sqlite3 queue.db < schema.sql
--
-- MySQL:
--   mysql -u root -p dbname < schema.sql

CREATE TABLE IF NOT EXISTS satusehat_queue (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- BIGINT for MySQL; INTEGER PRIMARY KEY for SQLite
    uuid                VARCHAR(36)  NOT NULL,
    bundle_type         VARCHAR(50)  NOT NULL DEFAULT 'single',
    bundle_payload      LONGTEXT,    -- JSON payload (use TEXT for SQLite)
    resource_type       VARCHAR(100),
    method              VARCHAR(10)  NOT NULL DEFAULT 'POST',
    url                 VARCHAR(500),
    status              VARCHAR(20)  NOT NULL DEFAULT 'pending',
    attempts            INT UNSIGNED NOT NULL DEFAULT 0,
    max_attempts        INT UNSIGNED NOT NULL DEFAULT 5,
    last_error          TEXT,
    last_response       LONGTEXT,    -- JSON response (use TEXT for SQLite)
    etag                VARCHAR(255),
    idempotency_key    VARCHAR(36),
    scheduled_at        DATETIME,
    completed_at        DATETIME,
    dlq_reason         TEXT,
    user_id            VARCHAR(255),
    metadata            LONGTEXT,    -- JSON metadata (use TEXT for SQLite)
    created_at          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX idx_queue_uuid (uuid),
    INDEX idx_queue_status (status),
    INDEX idx_queue_scheduled (scheduled_at)
);

-- For PostgreSQL: use TEXT instead of LONGTEXT/VARCHAR(500)
-- For SQLite: use TEXT instead of LONGTEXT, and remove AUTO_INCREMENT
