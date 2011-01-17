
CREATE TABLE bookmarks (
    href VARCHAR(750) NOT NULL PRIMARY KEY,
    title TEXT,
    notes TEXT,
    private BOOLEAN
);

CREATE TABLE bookmark_tags (
    href VARCHAR(750),
    tag VARCHAR(250),
    PRIMARY KEY (href, tag)
);

