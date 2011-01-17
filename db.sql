
CREATE TABLE bookmarks (
    href VARCHAR(750) NOT NULL PRIMARY KEY,
    title TEXT,
    notes TEXT,
    private BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE bookmark_tags (
    href VARCHAR(750) NOT NULL,
    tag VARCHAR(250) NOT NULL,
    PRIMARY KEY (href, tag)
);

