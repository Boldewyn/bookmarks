<?php defined('BOOKMARKS') or die('Access denied.');


require_once dirname(__FILE__).'/sql.php';


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
    public function __construct($privates=False) {
        $this->db = get_db();
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
        $private = (int)$private;
        $url = $this->_sanitize_url($url);
        if ($this->fetch($url)) {
            return Null;
        }
        try {
            $tag = Null;
            $stmt = $this->db->prepare('INSERT INTO '.cfg('database/prefix').
                                       'bookmarks (url, title, notes, private, created, modified)
                                        VALUES (:url, :title, :notes, :private, '.db_now().', '.db_now().')');
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':private', $private, db_bool());
            $stmt->execute();
            $stmt->closeCursor();
            $stmt = $this->db->prepare('INSERT INTO '.cfg('database/prefix').'bookmark_tags
                                        (url, tag) VALUES (:url, :tag)');
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':tag', $tag);
            foreach ($tags as $tag) {
                $stmt->execute();
            }
            $stmt->closeCursor();
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
        } else {
            $private = (int)$private;
        }
        try {
            $tag = Null;
            $stmt = $this->db->prepare('UPDATE '.cfg('database/prefix').'bookmarks SET
                                        title = :title,
                                        notes = :notes,
                                        private = :private
                                        WHERE url = :url');
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':private', $private, db_bool());
            $stmt->execute();
            $stmt->closeCursor();
            # TODO: Only diff change
            $stmt = $this->db->prepare('DELETE FROM '.cfg('database/prefix').'bookmark_tags
                                        WHERE url = :url');
            $stmt->execute();
            $stmt->closeCursor();
            $stmt = $this->db->prepare('INSERT INTO '.cfg('database/prefix').'bookmark_tags
                                        (url, tag) VALUES (:url, :tag)');
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':tag', $tag);
            foreach ($tags as $tag) {
                $stmt->execute();
            }
            $stmt->closeCursor();
        } catch (PDOException $e) {
            return False;
        }
        return True;
    }

    /**
     * Fetch a single bookmark
     */
    public function fetch($url) {
        $query = 'SELECT url, title, notes, private, '.unix_timestamp('created').' AS created,
                         '.unix_timestamp('modified').' AS modified
                    FROM '.cfg('database/prefix').'bookmarks WHERE url = :url';
        if (! $this->privates) {
            $query .= ' AND private = 0 ';
        }
        $query = $this->db->prepare($query);
        $query->bindParam(':url', $url);
        $query->execute();
        $bookmark = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        if ($bookmark !== False) {
            $bookmark['tags'] = $this->fetch_tags($url);
        }
        return $bookmark;
    }

    /**
     * delete a bookmark
     */
    public function delete($url) {
        $query = $this->db->prepare('DELETE
                                     FROM '.cfg('database/prefix').'bookmarks
                                     WHERE url = :url');
        $query->bindParam(':url', $url);
        $query->execute();
        $query->closeCursor();
        $query = $this->db->prepare('DELETE
                                     FROM '.cfg('database/prefix').'bookmark_tags
                                     WHERE url = :url');
        $query->bindParam(':url', $url);
        $query->execute();
        $query->closeCursor();
        return True;
    }

    /**
     * Search for Bookmarks
     */
    public function search($qarray, $limit=200, $offset=0) {
        $limit = min($limit, $this->hard_limit);
        $bookmarks = array();
        try {
            $query = sprintf(
                'SELECT b.url AS url,
                        b.title AS title,
                        b.notes AS notes,
                        b.private AS private,
                        '.str_replace('%', '%%', unix_timestamp('b.created')).' AS created,
                        '.str_replace('%', '%%', unix_timestamp('b.modified')).' AS modified
                   FROM '.cfg('database/prefix').'bookmarks b
                          WHERE b.url REGEXP %1$s
                          OR b.title REGEXP %1$s
                          OR b.notes REGEXP %1$s
                        ',
                        join('|', array_map(array($this->db, 'quote'),
                            $qarray))
                        );
            if (! $this->privates) {
                $query .= ' AND private = 0';
            }
            $query .= ' ORDER BY modified LIMIT :offset,:limit';
            $query = $this->db->prepare($query);
            //$query->debugDumpParams();
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->bindParam(':limit', $limit, PDO::PARAM_INT);
            $query->execute();
            $bookmarks = $query->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($bookmarks); $i++) {
                $bookmarks[$i]['tags'] = $this->fetch_tags($bookmarks[$i]['url']);
            }
            $query->closeCursor();
        } catch (PDOException $e) {
            return array();
        }
        return $bookmarks;
    }

    /**
     * Fetch all (or some) bookmarks
     */
    public function fetch_all($tags=array(), $limit=200, $offset=0) {
        $limit = min($limit, $this->hard_limit);
        $bookmarks = array();
        try {
            if (count($tags) > 1) {
                $query = sprintf(
                         'SELECT url, title, notes, private,
                                 '.unix_timestamp('created').' AS created,
                                 '.unix_timestamp('modified').' AS modified
                            FROM '.cfg('database/prefix').'bookmarks b
                           WHERE (
                                 SELECT COUNT(*)
                                   FROM '.cfg('database/prefix').'bookmark_tags t
                                  WHERE b.url = t.url
                                    AND t.tag in (%s)
                                ) = :n',
                            join(',', array_map(array($this->db, 'quote'), $tags))
                         );
                if (! $this->privates) {
                    $query .= ' AND private = 0';
                }
            } elseif (count($tags) === 1) {
                $query = 'SELECT b.url url, b.title title, b.notes notes, b.private private,
                                 '.unix_timestamp('b.created').' AS created,
                                 '.unix_timestamp('b.modified').' AS modified
                            FROM '.cfg('database/prefix').'bookmarks b,
                                 '.cfg('database/prefix').'bookmark_tags t
                           WHERE b.url = t.url
                             AND t.tag = :tag';
                if (! $this->privates) {
                    $query .= ' AND b.private = 0';
                }
            } else {
                $query = 'SELECT url, title, notes, private,
                                 '.unix_timestamp('created').' AS created,
                                 '.unix_timestamp('modified').' AS modified
                            FROM '.cfg('database/prefix').'bookmarks ';
                if (! $this->privates) {
                    $query .= 'WHERE private = 0 ';
                }
            }
            $query .= ' ORDER BY modified LIMIT :offset,:limit';
            $query = $this->db->prepare($query);
            if (! $query) {
                redirect('/install');
            }
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
            $query->closeCursor();
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
        $query = $this->db->prepare('SELECT tag FROM '.cfg('database/prefix').
                                    'bookmark_tags WHERE url = :url');
        $query->execute(array(':url' => $url));
        $return = $query->fetchAll(PDO::FETCH_COLUMN);
        $query->closeCursor();
        return $return;
    }

    /**
     * Fetch all ever used tags
     * @param $prefix An optional prefix tags have to start with
     */
    public function fetch_all_tags($prefix='') {
        $query = $this->db->prepare(
            'SELECT COUNT(t.tag) AS n, t.tag AS tag
               FROM '.cfg('database/prefix').'bookmark_tags t
              WHERE t.tag LIKE :prefix'.
             ($this->privates?'':'
                AND (SELECT COUNT(*) FROM '.cfg('database/prefix').'bookmarks b
                      WHERE b.url = t.url
                        AND b.private = 0 ) > 0').'
           GROUP BY t.tag');
        $query->execute(array(':prefix' => $prefix.'%'));
        //$query->debugDumpParams();
        $return = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $return;
    }

    /**
     * Create the necessary tables
     */
    public function install() {
        try {
            $this->db->exec('
            CREATE TABLE '.cfg('database/prefix').'bookmarks (
                id INTEGER PRIMARY KEY '.auto_increment().',
                url VARCHAR(750) NOT NULL,
                title TEXT,
                notes TEXT,
                private BOOLEAN NOT NULL DEFAULT 1,
                modified TIMESTAMP NOT NULL,
                created TIMESTAMP NOT NULL
            )');
            $this->db->exec('
            CREATE INDEX has_url
                ON '.cfg('database/prefix').'bookmarks (url)');
            $this->db->exec('
            CREATE INDEX is_private
                ON '.cfg('database/prefix').'bookmarks (private)');
            $this->db->exec('
            CREATE TABLE '.cfg('database/prefix').'bookmark_tags (
                id INTEGER PRIMARY KEY '.auto_increment().',
                url VARCHAR(750) NOT NULL,
                tag VARCHAR(250) NOT NULL
            )');
            $this->db->exec('
            CREATE INDEX has_tag
                ON '.cfg('database/prefix').'bookmark_tags (tag)');
        } catch (Exception $e) {
            return False;
        }
        return True;
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


//__END__
