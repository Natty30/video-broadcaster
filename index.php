<?php

require './backend/env/config.php';

require './backend/class/autoload-class.php';

spl_autoload_register('Autoload');

$videos = null;
$error = "";

try {
    $videos = new Videos();
    $v_inf = $videos->getAllVideos();
} catch (Exception $e) {
    $error = "Error : ".$e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>YMI</title>

    <!-- CSS THEME -->

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link href="https://vjs.zencdn.net/7.8.2/video-js.min.css" rel="stylesheet">

    <!-- ADDITIONAL CSS -->

    <style type="text/css">

        body {
            background-color: #edf2f7;
        }

        .bg-light {
            background-color: white !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .no-bottom-margin {
            margin-bottom: 0rem !important;
        }

        .little {
            font-size: 1rem;
        }

        .btn:focus {
            border-color: transparent !important;
            box-shadow: none !important;
        }

        .btn-primary {
            background-color: #6366f1 !important;
        }

        .btn-primary:hover {
            background-color: #5356e1 !important;
        }

        .video-js .vjs-big-play-button:focus, .video-js:hover .vjs-big-play-button {
            background-color: rgba(237, 242, 247, 1);
            border-width: 2px;
            border-color: #6366f1;
        }

        .video-js .vjs-big-play-button:focus, .video-js .vjs-big-play-button {
            background-color: rgba(237, 242, 247, 0.7);
            border-width: 2px;
            border-color: #6366f1;
        }

        .vjs-matrix .vjs-control-bar {
            background-color: rgba(237, 242, 247, 0.7);
        }

        .vjs-icon-placeholder::before {
            color: #6366f1;
        }

        .pointer {
            cursor: pointer;
        }

        .shadow-on-hover:hover {
            box-shadow: 0 3px 9px 0 rgba(0, 0, 0, 0.1), 0 3px 6px 0 rgba(0, 0, 0, 0.06);
        }

        .card {
            border-radius: 0px 0px 10px 10px;
        }

        .red-text {
            color: red;
        }

        .theme-text {
            color: #6366f1;
        }

        .progress-bar {
            background-color: #6366f1;
        }

    </style>

</head>

<body id="body">

    <!-- navbar -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <h2 class="no-bottom-margin"><b>YMI</b></h2>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    <!-- page content -->
    
    <div class="container">
        
        <div class="row mt-5">
            
            <div class="col-12">
                
                <h4 class="no-bottom-margin"><b>Welcome on your Youtube Multistream Interface.</b></h4>
                
                <button id="addStreamButton" type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#addStreamModal">
                    add a stream
                </button>

                <h4 class="no-bottom-margin mt-5"><b>Uploaded videos : </b></h4>

            </div>

        </div>

        <div class="row">

<?php

    if (count($v_inf) !== 0) {

        foreach ($v_inf as $v) {

            // stream info
            $v_src = "upload/".$v['id']."/video.".$v['video_extension']."#t=2";
            $s_url = $v['stream_url'];
            $s_key = $v['stream_key'];
            $s_on = $v['is_in_stream'];

            $stream_url_id = "url-".$v['id'];
            $stream_key_id = "sk-".$v['id'];

            // stream action
            $start_stream_id = "start-".$v['id'];
            $stop_stream_id = "stop-".$v['id'];
            $update_stream_id = "update-".$v['id'];
            $delete_stream_id = "delete-".$v['id'];
            $error_action_id = "error-".$v['id'];

?>

            <div class="col-12 col-md-6 col-lg-4 mt-5">

                <div class="card shadow-on-hover">

                    <video class="car-img-top video-js vjs-matrix vjs-big-play-centered" controls preload="auto" data-setup='{"fluid":true}'>

                        <source src="<?php echo $v_src; ?>" type="video/mp4">

                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a web browser that
                            <a href="https://videojs.com/html5-video-support/" target="_blank">
                                supports HTML5 video
                            </a>
                        </p>

                    </video>

                    <div class="card-body">

                        <p class="little no-bottom-margin mt-3"><b>Stream URL</b></p>
                        <p id="<?php echo $stream_url_id; ?>" class="little no-bottom-margin"><?php echo $s_url; ?></p>
                        <hr>
                        <p class="little no-bottom-margin"><b>Stream key</b></p>
                        <p id="<?php echo $stream_key_id; ?>" class="little no-bottom-margin"><?php echo $s_key; ?></p>
                        <hr>
                        <p id="<?php echo $error_action_id; ?>" class="little no-bottom-margin red-text"></p>
                        <p id="<?php echo $update_stream_id; ?>" class="little no-bottom-margin pointer update-stream" style="text-decoration: underline;">Edit information</p>
                        <div class="row">
                    
                            <div class="col-6">
                                <button id="<?php echo $delete_stream_id; ?>" class="btn btn-danger mt-3 delete-stream">Delete video</button>
                            </div>

<?php 

            if (!$s_on) {

?>

                            <div class="col-6 d-flex justify-content-end">
                                <button id="<?php echo $start_stream_id; ?>" class="btn btn-primary mt-3 start-stream">Start stream</button>
                            </div>

<?php

            } else {

?>

                            <div class="col-6 d-flex justify-content-end">
                                <button id="<?php echo $stop_stream_id; ?>" class="btn btn-danger mt-3 stop-stream">Stop stream</button>
                            </div>

<?php

            }

?>

                        </div>

                    </div>

                </div>

            </div>

<?php
    
        }

    } else {

?>

            <div class="col-12 mt-5">
                <p class="red-text little">No videos have been uploaded.</p>
            </div>

<?php

    }

?>
            
        </div>

    </div>

    <!-- modals -->

    <div class="modal fade" id="addStreamModal" tabindex="-1" role="dialog" aria-labelledby="addStream" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStream">Upload a video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="upload-video-form" action="" method="post">
                        <div class="form-group">
                            <label for="ytVIDEO">Choose a video to upload</label>
                            <input type="file" class="d-none" id="ytVIDEO" name="file">
                            <div class="col-12 d-flex justify-content-between" style="padding-right: 0px; padding-left: 0px;">
                                <input id="path-video-to-upload" class="col-8" type="text" name="filename" disabled="">
                                <button type="button" id="choose-video-to-upload" class="btn btn-primary col-3">upload</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ytURL">RTMP URL</label>
                            <input type="url" class="form-control" id="ytURL" name="url" placeholder="rtmp://example.com/app" pattern="rtmp://.*">
                        </div>
                        <div class="form-group">
                            <label for="ytSK">Stream key</label>
                            <input type="text" class="form-control" id="ytSK" name="sk" placeholder="xxxx-xxxx-xxxx-xxxx-xxxx">
                        </div>
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <p class="uploadStatus theme-text"></p>
                        <p id="error-upload" class="red-text"></p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-dismiss="modal">Close</button>
                    <button id="upload-video-submit" type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStreamModal" tabindex="-1" role="dialog" aria-labelledby="editStream" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStream">Edit stream information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-stream-form" action="" method="post">
                        <input type="hidden" id="edit-id" name="edit-id">
                        <div class="form-group">
                            <label for="edit-url">RTMP URL</label>
                            <input type="url" class="form-control" id="edit-url" name="edit-url" placeholder="rtmp://example.com/app" pattern="rtmp://.*">
                        </div>
                        <div class="form-group">
                            <label for="edit-sk">Stream key</label>
                            <input type="text" class="form-control" id="edit-sk" name="edit-sk" placeholder="xxxx-xxxx-xxxx-xxxx-xxxx">
                        </div>
                        <p id="error-edit" class="red-text"></p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-dismiss="modal">Close</button>
                    <button id="edit-stream-submit" type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- scripts -->

    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script src="https://vjs.zencdn.net/7.8.2/video.min.js"></script>

    <script type="text/javascript">
        
        $(document).ready(function() {

            // video upload

            $('.progress').hide()
            $('.uploadStatus').text('')

            $("#addStreamButton").on('click', function() {
                $("#addStreamModal").modal()
            })

            $('#choose-video-to-upload').on('click', function() {
                $('#ytVIDEO').click()
            })

            $('#ytVIDEO').on('change', function() {
                $('#path-video-to-upload').val($(this).val().replace(/C:\\fakepath\\/i, ''))
            })

            $('#upload-video-submit').on('click', function() {

                let fileName = $('#ytVIDEO').val(),
                    fileExtension = ['mp4', 'ogg', 'flv'],
                    url = $('#ytURL').val(),
                    urlRegex = new RegExp('rtmp:\/\/[a-zA-Z0-9.-]+\/[a-zA-Z0-9-]+'),
                    sk = $('#ytSK').val(),
                    error = "";

                if (!fileName || $.inArray($('#ytVIDEO').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                    error = "Please select a mp4, ogg or flv video to upload."
                } else if (!url || !urlRegex.test(url)) {
                    error = "URL must respect the RTMP format."
                } else if (!sk) {
                    error = "Stream key must not be empty."
                }

                if (!error) {

                    let form = $('#upload-video-form')[0],
                        formData = new FormData(form);

                    $.ajax({
                        xhr: function() {
                            $('.progress').fadeIn()
                            $('#error-upload').text('')
                            var xhr = new window.XMLHttpRequest()
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = Math.round((evt.loaded / evt.total) * 100)
                                    $(".progress-bar").width(percentComplete + '%')
                                    $(".progress-bar").html(percentComplete+'%')
                                }
                            }, false);
                            return xhr;
                        },
                        type: 'POST',
                        url: 'backend/forms/upload.php',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend: function() {
                            $(".progress-bar").width('0%')
                        },
                        error: function() {
                            $('.progress').hide()
                            $('#error-upload').text('File upload failed, please try again.')
                        },
                        success: function(resp) {
                            if(resp == 'ok'){
                                $('#error-upload').text('')
                                $('.progress').hide()
                                $('#upload-video-form')[0].reset()
                                $('.uploadStatus').text('Video has uploaded successfully !')
                                location.reload()
                            } else {
                                $('#error-upload').text(resp)
                            }
                        }
                    });

                } else {
                    $('#error-upload').text(error)
                }

            })

            // manage streams

            $('.start-stream').on('click', function() {

                let id = $(this).attr('id').replace('start-', ''),
                    err_id = "#error-" + id;

                $.ajax({

                    type: 'POST',
                    url: 'backend/forms/start_stream.php',
                    data: 'id=' + id,
                    success: function(resp) {

                        $(err_id).text('')

                        if (resp == 'ok') {
                            location.reload()
                        } else {
                            $(err_id).text(resp)
                        }

                    },
                    error: function(err) {

                        $(err_id).text(err)

                    }

                })

            })

            $('.stop-stream').on('click', function() {

                let id = $(this).attr('id').replace('stop-', ''),
                    err_id = "#error-" + id;

                $.ajax({

                    type: 'POST',
                    url: 'backend/forms/stop_stream.php',
                    data: 'id=' + id,
                    success: function(resp) {

                        $(err_id).text('')

                        if (resp == 'ok') {
                            location.reload()
                        } else {
                            $(err_id).text(resp)
                        }

                    },
                    error: function(err) {

                        $(err_id).text(err)
                        
                    }

                })

            })

            $('.update-stream').on('click', function() {

                let id = $(this).attr('id').replace('update-', ''),
                    url_id = "#url-" + id,
                    sk_id = "#sk-" + id,
                    url = $(url_id).text(),
                    sk = $(sk_id).text();

                $('#editStreamModal').modal()
                $('#edit-id').val(id)
                $('#edit-url').val(url)
                $('#edit-sk').val(sk)

            })

            $('#edit-stream-submit').on('click', function() {

                let id = $('#edit-id').val(),
                    url = $('#edit-url').val(),
                    urlRegex = new RegExp('rtmp:\/\/[a-zA-Z0-9.]+\/[a-zA-Z0-9]+'),
                    sk = $('#edit-sk').val(),
                    error = "";

                $('#error-edit').text('')

                if (!url || !urlRegex.test(url)) {
                    error = "URL must respect the RTMP format."
                } else if (!sk) {
                    error = "Stream key must not be empty."
                }

                if (!error) {

                    $.ajax({
                        type: 'POST',
                        url: 'backend/forms/update_stream.php',
                        data: 'id=' + id + '&url=' + url + '&sk=' + sk ,
                        error: function() {
                            $('#error-edit').text('File upload failed, please try again.')
                        },
                        success: function(resp) {
                            if(resp == 'ok'){
                                $('#edit-stream-form')[0].reset()
                                location.reload()
                            } else {
                                $('#error-edit').text(resp)
                            }
                        }
                    });

                } else {
                    $('#error-edit').text(error)
                }

            })

            $('.delete-stream').on('click', function() {

                let id = $(this).attr('id').replace('delete-', ''),
                    err_id = "#error-" + id;

                $.ajax({

                    type: 'POST',
                    url: 'backend/forms/delete_stream.php',
                    data: 'id=' + id,
                    success: function(resp) {

                        $(err_id).text('')

                        if (resp == 'ok') {
                            location.reload()
                        } else {
                            $(err_id).text(resp)
                        }

                    },
                    error: function(err) {

                        $(err_id).text(err)
                        
                    }

                })

            })

        })

    </script>

</body>

</html>