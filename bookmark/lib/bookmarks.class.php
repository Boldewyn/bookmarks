<?php defined('BOOKMARKS') or die('Access denied.');

/**
 * Manage bookmarks
 */
class Bookmarks {

    private $privates = False;
    private $db;
    private $hard_limit = 1000;

    /**
     * Set database connection (PDO) and whether private bookmarks are fetched
     */
    public function __construct($db, $privates=False) {
        $this->db = $db;
        $this->privates = $privates;
    }

    /**
     * Determine, if private bookmarks are fetched
     */
    public function set_privacy($privates) {
        $old = $this->privates;
        $this->privates = $privates;
        return $old;
    }

    /**
     * Save a bookmark in the database
     */
    public function save($url, $title, $tags, $notes, $private) {
        $url = $this->_sanitize_url($url);
        if ($this->fetch($url)) {
            return Null;
        }
        try {
            $tag = Null;
            $stmt = $this->db->prepare('INSERT INTO bookmarks (url, title, notes, private, created, modified)
                                       VALUES (:url, :title, :notes, :private, NOW(), NOW())');
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':private', $private, PDO::PARAM_BOOL);
            $stmt->execute();
            $stmt = $this->db->prepare('INSERT INTO bookmark_tags (url, tag) VALUES (:url, :tag)');
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':tag', $tag);
            foreach ($tags as $tag) {
                $stmt->execute();
            }
        } catch (PDOException $e) {
            return False;
        }
        return True;
    }

    /**
     * Change an already stored bookmark (defined by its url)
     */
    public function change($url, $title=Null, $tags=Null, $notes=Null, $private=Null) {
        $url = $this->_sanitize_url($url);
        $bm = $this->fetch($url);
        if ($bm === False) {
            return Null;
        }
        if ($title === Null) {
            $title = $bm['title'];
        }
        if ($tags === Null) {
            $tags = $bm['tags'];
        }
        if ($notes === Null) {
            $notes = $bm['notes'];
        }
        if ($private === Null) {
            $private = $bm['private'];
        }
        try {
            $tag = Null;
            $stmt = $this->db->prepare('UPDATE bookmarks SET
                                        title = :title,
                                        notes = :notes,
                                        private = :private
                                        WHERE url = :url');
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':private', $private, PDO::PARAM_BOOL);
            $stmt->execute();
            # TODO: Only diff change
            $stmt = $this->db->prepare('DELETE * FROM bookmark_tags WHERE url = :url');
            $stmt->execute();
            $stmt = $this->db->prepare('INSERT INTO bookmark_tags (url, tag) VALUES (:url, :tag)');
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':tag', $tag);
            foreach ($tags as $tag) {
                $stmt->execute();
            }
        } catch (PDOException $e) {
            return False;
        }
        return True;
    }

    /**
     * Fetch a single bookmark
     */
    function fetch($url) {
        $query = 'SELECT url, title, notes, private, UNIX_TIMESTAMP(created) AS created,
                         UNIX_TIMESTAMP(modified) AS modified
                    FROM bookmarks WHERE url = :url';
        if (! $this->privates) {
            $query .= ' AND private = False ';
        }
        $query = $this->db->prepare($query);
        $query->bindParam(':url', $url);
        $query->execute();
        $bookmark = $query->fetch(PDO::FETCH_ASSOC);
        if ($bookmark !== False) {
            $bookmark['tags'] = $this->fetch_tags($url);
        }
        return $bookmark;
    }

    /**
     * Fetch all (or some) bookmarks
     */
    function fetch_all($tags=array(), $limit=200, $offset=0) {
        $limit = min($limit, $this->hard_limit);
        $bookmarks = array();
        try {
            if (count($tags) > 1) {
                $query = sprintf(
                         'SELECT url, title, notes, private, UNIX_TIMESTAMP(created) AS created,
                                 UNIX_TIMESTAMP(modified) AS modified
                            FROM bookmarks
                           WHERE (
                                 SELECT COUNT(*)
                                   FROM bookmark_tags
                                  WHERE bookmarks.url = bookmark_tags.url
                                    AND bookmark_tags.tag in (%s)
                                ) = :n',
                            join(',', array_map(array($this->db, 'quote'), $tags))
                         );
            } elseif (count($tags) === 1) {
                $query = 'SELECT b.url url, b.title title, b.notes notes, b.private private,
                                 UNIX_TIMESTAMP(b.created) AS created,
                                 UNIX_TIMESTAMP(b.modified) AS modified
                            FROM bookmarks b, bookmark_tags t
                           WHERE b.url = t.url
                             AND t.tag = :tag';
                if (! $this->privates) {
                    $query .= ' AND b.private = False';
                }
            } else {
                $query = 'SELECT url, title, notes, private, UNIX_TIMESTAMP(created) AS created,
                                 UNIX_TIMESTAMP(modified) AS modified
                            FROM bookmarks ';
                if (! $this->privates) {
                    $query .= 'WHERE private = False ';
                }
            }
            $query .= ' LIMIT :offset,:limit';
            $query = $this->db->prepare($query);
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->bindParam(':limit', $limit, PDO::PARAM_INT);
            if (count($tags) === 1) {
                $query->bindParam(':tag', $tags[0]);
            } elseif (count($tags) > 1) {
                $query->bindParam(':n', count($tags));
            }
            $query->execute();
            $bookmarks = $query->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($bookmarks); $i++) {
                $bookmarks[$i]['tags'] = $this->fetch_tags($bookmarks[$i]['url']);
            }
        } catch (PDOException $e) {
            return array();
        }
        return $bookmarks;
    }

    /**
     * Fetch all tags for a given bookmark
     * @param $url The URL of the bookmark
     */
    public function fetch_tags($url) {
        $query = $this->db->prepare('SELECT tag FROM bookmark_tags WHERE url = :url');
        $query->execute(array(':url' => $url));
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Fetch all ever used tags
     * @param $prefix An optional prefix tags have to start with
     */
    public function fetch_all_tags($prefix='') {
        $query = $this->db->prepare(
            'SELECT COUNT(t.tag) AS n, t.tag AS tag
               FROM bookmark_tags t
              WHERE t.tag LIKE :prefix'.
             ($this->privates?'':'
                AND (SELECT COUNT(*) FROM bookmarks b
                      WHERE b.url = t.url
                        AND b.private = 0 ) > 0').'
           GROUP BY t.tag');
        $query->execute(array(':prefix' => $prefix.'%'));
        $query->debugDumpParams();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sanitize a URL
     *
     * Checks for non-ASCII characters, prepends 'http://' if necessary,
     * and quotes special chars
     * @param $url A URL
     */
    protected function _sanitize_url($url) {
        if (! preg_match('/^[a-z0-9+\.-]+:/i', $url)) {
            $url = "http://$url";
        }
        $enc = mb_detect_encoding($url);
        switch ($enc) {
            case 'ASCII':
                break;
            case 'UTF-8':
                $url = $this->_urlencode($url);
                break;
            default:
                $url = mb_convert_encoding($url, 'UTF-8', $enc);
                $url = $this->_urlencode($url);
                break;
        }
        return $url;
    }

    /**
     * URLencode a non-ASCII URL
     *
     * DON'T throw an ASCII URL onto this function, since '%' will also be quoted
     * @param $utf8 an almost-URL with unencoded UTF-8 chars
     */
    protected function _urlencode($utf8) {
        $utf8 = preg_replace_callback('/[^a-zA-Z0-9$\-_\.+#;\/?@=&:]/', create_function(
            '$m',
            'return rawurlencode($m[0]);'
        ), $utf8);
        return $utf8;
    }

}

#__END__