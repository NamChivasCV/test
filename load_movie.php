<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');


for ($i = 549; $i >= 1; $i--) {
    if ($i == 548) break;
    $pageData = json_decode(file_get_contents("https://ophim1.com/danh-sach/phim-moi-cap-nhat?page=" . $i));

    if ($pageData->status) {


        foreach ($pageData->items as $item) {
            getMovie($item->slug);
        }
    } else {
        echo "Error PAGE: " . $i;
        echo '<br>';
    }
    if ($i == 1) {

        echo '<br>';
        echo 'DONE !';
    }
}






function getMovie($slug)
{
    $movieData = json_decode(file_get_contents("https://ophim1.com/phim/" . $slug));
    if ($movieData->status) {
        $movie = new stdClass();
        $movie = $movieData->movie;
        $movie->episodes = $movieData->episodes;
        $movie = remakeMovie($movie);
        pushToDB($movie);
    } else {
        echo "Error Slug: " . $slug;
        echo '<br>';
    }
}



function pushToDB($movie)
{

    $conn = mysqli_connect(
        'localhost',
        'root',
        '',
        'hacked'
    ) or die("Can't Connect To Database!");
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: " . $conn->connect_error;
        die();
    }


    $movie->name = addslashes($movie->name);
    $movie->origin_name = addslashes($movie->origin_name);
    $movie->content = addslashes($movie->content);
    $movie->time = addslashes($movie->time);

    $movie->slug = $movie->slug . '-' . rand(1000, 9999);


    $movie->actor = json_encode($movie->actor, JSON_UNESCAPED_UNICODE);
    $movie->director = json_encode($movie->director, JSON_UNESCAPED_UNICODE);
    $movie->category = json_encode($movie->category, JSON_UNESCAPED_UNICODE);
    $movie->country = json_encode($movie->country, JSON_UNESCAPED_UNICODE);
    $movie->episodes = json_encode($movie->episodes, JSON_UNESCAPED_UNICODE);

    $movie->last_modified = time();
    $movie->banner_url = '';


    $sql = "INSERT INTO `movie`(
        `_id`,
        `name`,
        `origin_name`,
        `content`,
        `type`,
        `status`,
        `thumb_url`,
        `trailer_url`,
        `time`,
        `episode_current`,
        `episode_total`,
        `quality`,
        `lang`,
        `slug`,
        `year`,
        `actor`,
        `director`,
        `category`,
        `country`,
        `episodes`,
        `last_modified`,
        `banner_url`,
        `is_trending`,
        `active`
    )VALUES(
        '$movie->_id',
        '$movie->name',
        '$movie->origin_name',
        '$movie->content',
        '$movie->type',
        '$movie->status',
        '$movie->thumb_url',
        '$movie->trailer_url',
        '$movie->time',
        '$movie->episode_current',
        '$movie->episode_total',
        '$movie->quality',
        '$movie->lang',
        '$movie->slug',
        '$movie->year',
        '$movie->actor',
        '$movie->director',
        '$movie->category',
        '$movie->country',
        '$movie->episodes',
        '$movie->last_modified',
        '$movie->banner_url',
        0,
        1
    )";





    if ($conn->query($sql)) {
        // echo "OK";
    } else {
        echo "Error: " . $movie->name . " | " . $movie->slug;
        echo '<br>';
    }

    $conn->close();
}




function remakeMovie($movieDecode)
{
    unset($movieDecode->modified);
    unset($movieDecode->showtimes);
    unset($movieDecode->notify);
    unset($movieDecode->is_copyright);
    unset($movieDecode->chieurap);
    unset($movieDecode->sub_docquyen);
    $movieDecode->content = strip_tags($movieDecode->content);
    return $movieDecode;
}
