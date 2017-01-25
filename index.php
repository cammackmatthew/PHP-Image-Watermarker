<?php
    session_start();

    $id = session_id();

    if (empty($_SESSION['images'])) {
        $array = array();
        $_SESSION['images'] = $array;
    };
?>

<html>
    <head>
        <title>Image watermarker</title>

        <meta charset="UTF-8">
        <meta name="description" content="Photo watermarking tool">
        <meta name="keywords" content="Photo,upload,watermark,watermarking,watermaker,tool,online">
        <meta name="author" content="Matthew Cammack">

        <link href="./css/base.min.css" rel="stylesheet">
        <link href="./css/project.min.css" rel="stylesheet">
        <link href="./css/main.css" rel="stylesheet">
    </head>

    <body style="overflow-x: hidden;">
        <div class="container-fluid">
            <div class="row" style="padding-left: 17%;">
                <div class="col-md-2" id="sidebar">
                    <h3 style="margin-top: 20px">Image Watermarker</h3>
                    <hr />
                    <span class="avatar avatar-inline avatar-brand margin-right avatar-sm">1</span> Upload Images
                    <form class="form" id="form" action="./upload.php" method="POST" enctype="multipart/form-data">
                        <label>Photos</label>
                        <input class="form-control" type="file" name="files[]" id="files" accept="image/*" multiple required/>
                        <br />
                        <button class="btn btn-brand waves-attach waves-light btn-block" id="upload" style="color: white !important;">Upload images</button>
                    </form>
                    <hr />
                    <span class="avatar avatar-inline avatar-brand margin-right avatar-sm avatar-red">2</span> Watermark Settings
                    <form class="form">
                        <label>Watermark Text</label>
                        <input class="form-control" type="text" id="userText" value="©" required/>
                        <label>Watermark Text Position</label>
                        <!--<input class="form-control" type="text" id="position" required/>-->
                        <select class="form-control" id="position">
                            <option value="Top Left">Top Left</option>
                            <option value="Top Right">Top Right</option>
                            <option value="Center">Center</option>
                            <option value="Bottom Left">Bottom Left</option>
                            <option value="Bottom Right">Bottom Right</option>
                        </select>
                        <label>Watermark Text Size</label>
                        <input type="range" class="form-control" min="10" max="200" value="100" id="userTextSize">
                        <label>Watermark Text Colour</label>
                        <input type="color" id="textColour" class="form-control" style="width: 100%;" value="#ffffff">
                    </form>
                    <button class="btn btn-brand waves-attach waves-light btn-block btn-red" style="color: white !important;" id="updateSettings">Preview Settings</button>
                    <hr />
                    <span class="avatar avatar-inline avatar-brand margin-right avatar-sm avatar-green" style="color: white !important;">3</span> Export Images
                    <br /><br />
                    <button class="btn btn-brand waves-attach waves-light btn-block btn-green" style="color: white !important;" id="exportSelected">Export Selected Image</button>
                    <br />
                    <button class="btn btn-brand waves-attach waves-light btn-block btn-green" style="color: white !important;" id="exportAll">Export All Images (.zip)</button>
                    <hr />
                    <a href="./clear.php" class="btn btn-brand waves-attach waves-light btn-block btn-orange" style="color: white !important;">Clear Session</a>
                </div>
                <div class="col-md-12" style="position: fixed; height: 100%; width: 84%;">
                    <div style="margin: 10px; height: 85%;">
                    <center>
                        <p id="error"></p>
                        <?php
                            $array = $_SESSION['images'];
                            if (empty($array)) {
                                echo '<br /><br /><span class="icon icon-5x">warning</span><br /><h3 style="margin-top: 5px;">No images to show, upload some!</h3>';
                            } else {
                                echo '<img src="' . $array[0] . '" height="100%" class="img-rounded" id="mainImage" />'; 
                            }; 
                        ?>
                        </center>
                    </div>

                    <footer class="footerFiles">
                        <div class="footerContent">
                            <ul>
                                <?php
                                    $array = $_SESSION['images'];

                                    foreach ($array as $image) {
                                        echo '<li><a href="#" class="footerImageLink"><img src="' . $image . '" width="145px;" class="footerImage"></a></li>';
                                    };
                                ?>
                            </ul>
                        </div>
                    </footer>
                </div>
            </div>
        </div>

        <div aria-hidden="true" class="modal modal-va-middle fade" id="confirmModal" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-xs">
                <div class="modal-content">
                    <div class="modal-inner">
                        <p class="h5 margin-top-sm text-black-hint">This all apply your watermark settings to ALL images in your queue, are you sure you want to do this?</p>
                    </div>
                    <div class="modal-footer">
                        <p class="text-right"><a class="btn btn-flat btn-brand-accent waves-attach waves-effect" data-dismiss="modal">No</a><a class="btn btn-flat btn-brand-accent waves-attach waves-effect" id="downloadProceed">Yes</a></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
        <script src="http://malsup.github.com/min/jquery.form.min.js"></script>
        <script src="./js/main.js"></script>
        <script src="./js/base.min.js"></script>
        <script src="./js/project.min.js"></script>
    </body>
</html>