<?php

function paginate($total, $page = 1, $limit = 10) {

    $totalPages = ceil($total / $limit);

    return [
        "current_page" => (int)$page,
        "total_pages" => $totalPages,
        "limit" => $limit,
        "offset" => ($page - 1) * $limit
    ];
}