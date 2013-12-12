<?php

    $searchQuery = preg_replace('/ +/u', ' ', preg_replace('/[^\@\w+ ]/u', '', $_POST['query'])) . "*";

    $dbh = mysql_connect('localhost', 'rootuser', 'rootuser');
    mysql_query('use beatguide_devel_service', $dbh);

    // ##########################################

    $results = [];

    $query = mysql_query('SELECT id, name, url_soundcloud, url_facebook, url_avatar, country FROM djs WHERE MATCH(name) AGAINST("' . $searchQuery . '" IN BOOLEAN MODE) AND review=0 AND url_avatar IS NOT NULL LIMIT 100', $dbh);

    while ($row = mysql_fetch_array($query, MYSQL_ASSOC))
    {
        $results[] = [
            'id'             => $row['id'],
            'name'           => $row['name'],
            'url_soundcloud' => $row['url_soundcloud'],
            'url_facebook'   => $row['url_facebook'],
            'url_avatar'     => $row['url_avatar'],
            'country'        => $row['country'],
        ];
    }

    echo json_encode(['results' => $results, 'query' => $_POST['query']]);

    // ##########################################

    mysql_close($dbh);