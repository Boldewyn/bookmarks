<?php

/**
 * Manage bookmarks
 */
class Bookmarks {

    private $privates = False;
    private $db;
    private $hard_limit = 1000;

    public function __construct($db, $privates=False) {
        $this->db = $db;
        $this->privates = $privates;
    }

    public function save($href, $title, $tags, $notes, $private) {
        $href = $this->_sanitize_url($href);
        if ($this->fetch($href)) {
            return Null;
        }
        try {
            $tag = Null;
            $stmt = $this->db->prepare('INSERT INTO bookmarks (href, title, notes, private)
                                       VALUES (:href, :title, :notes, :private)');
            $stmt->bindParam(':href', $href);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':private', $private, PDO::PARAM_BOOL);
            $stmt->execute();
            $stmt = $this->db->prepare('INSERT INTO bookmark_tags (href, tag) VALUES (:href, :tag)');
            $stmt->bindParam(':href', $href);
            $stmt->bindParam(':tag', $tag);
            foreach ($tags as $tag) {
                $stmt->execute();
            }
        } catch (PDOException $e) {
            return False;
        }
        return True;
    }

    public function change($href, $title=Null, $tags=Null, $notes=Null, $private=Null) {
        $href = $this->_sanitize_url($href);
        $bm = $this->fetch($href);
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
                                        WHERE href = :href');
            $stmt->bindParam(':href', $href);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':private', $private, PDO::PARAM_BOOL);
            $stmt->execute();
            # TODO: Only diff change
            $stmt = $this->db->prepare('DELETE * FROM bookmark_tags WHERE href = :href');
            $stmt->execute();
            $stmt = $this->db->prepare('INSERT INTO bookmark_tags (href, tag) VALUES (:href, :tag)');
            $stmt->bindParam(':href', $href);
            $stmt->bindParam(':tag', $tag);
            foreach ($tags as $tag) {
                $stmt->execute();
            }
        } catch (PDOException $e) {
            return False;
        }
        return True;
    }

    function fetch($href) {
        $query = 'SELECT href, title, notes, private
                    FROM bookmarks WHERE href = :href';
        if (! $this->privates) {
            $query .= ' AND private = False ';
        }
        $query = $this->db->prepare($query);
        $query->bindParam(':href', $href);
        $query->execute();
        $bookmark = $query->fetch(PDO::FETCH_ASSOC);
        if ($bookmark !== False) {
            $bookmark['tags'] = $this->fetch_tags($href);
        }
        return $bookmark;
    }

    function fetch_all($tags=array(), $limit=200, $offset=0) {
        $limit = min($limit, $this->hard_limit);
        $bookmarks = array();
        try {
            if (count($tags) > 0) {
                $query = 'SELECT b.href href, b.title title, b.notes notes, b.private private
                            FROM bookmarks b, bookmark_tags t
                        WHERE b.href = t.href
                            AND ';
                if (! $this->privates) {
                    $query .= 'b.private = False AND ';
                }
                $where = array();
                foreach ($tags as $tag) {
                    $where[] = 't.tag = '.$this->db->quote($tag);
                }
                $query .= join(' AND ', $where);
            } else {
                $query = 'SELECT href, title, notes, private
                                        FROM bookmarks ';
                if (! $this->privates) {
                    $query .= 'WHERE private = False ';
                }
            }
            $query .= ' LIMIT :offset,:limit';
            $query = $this->db->prepare($query);
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->bindParam(':limit', $limit, PDO::PARAM_INT);
            $query->execute();
            $bookmarks = $query->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($bookmarks); $i++) {
                $bookmarks[$i]['tags'] = $this->fetch_tags($href);
            }
            $query->debugDumpParams();
        } catch (PDOException $e) {
            return array();
        }
        return $bookmarks;
    }

    public function fetch_tags($href) {
        $query = $this->db->prepare('SELECT tag FROM bookmark_tags WHERE href = :href');
        $query->execute(array(':href' => $href));
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function _sanitize_url($href) {
        return $href;
    }

}

#__END__
