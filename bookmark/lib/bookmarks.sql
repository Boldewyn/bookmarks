-- SQL for MySQL

CREATE TABLE bookmarks (
    url VARCHAR(750) NOT NULL PRIMARY KEY,
    title TEXT,
    notes TEXT,
    private BOOLEAN NOT NULL DEFAULT TRUE,

    -- see http://dev.mysql.com/doc/refman/5.0/en/timestamp.html for these:
    modified TIMESTAMP NOT NULL,
    created TIMESTAMP NOT NULL,

    INDEX is_private (private)
);

CREATE TABLE bookmark_tags (
    url VARCHAR(750) NOT NULL,
    tag VARCHAR(250) NOT NULL,

    PRIMARY KEY (url, tag),
    INDEX has_tag (tag)
);

