<?php
/* @var $id string */
/* @var $url string */
/* @var $path string */
/* @var $hiddenField string */
/* @var $maxFiles int */
?>
<div class="filemanager-container">
    <div class="filemanager-input">
        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#<?= $id ?>-file-manager-modal">انتخاب از سرور</button>
        <label class="filemanager-label ltr"></label>
        <?php echo $hiddenField ?>
    </div>
    <div class="modal fade filemanager-modal" id="<?= $id ?>-file-manager-modal" data-fetch-url="<?= $url ?>" data-fetch-path="<?= $path ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" class="close">&times;</button>
                    <h3>لیست فایل ها</h3>
                </div>
                <div class="modal-body">
                    <div class="modal-loading-container">
                        <div class="modal-loading">
                            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="48px" height="48px" viewBox="0 0 40 40" enable-background="new 0 0 48 48" xml:space="preserve"> <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"></path>
                                <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0C22.32,8.481,24.301,9.057,26.013,10.047z" transform="rotate(96.306 20 20)">
                                    <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.5s" repeatCount="indefinite"></animateTransform>
                                </path> </svg>
                        </div>
                    </div>
                    <div class="filemanager-list-container"></div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-ex-12">
                            <button type="button" class="btn btn-success filemanager-submit">انتخاب</button>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-ex-12">
                            <button type="button" class="btn btn-danger filemanager-dismiss" data-dismiss="modal">انصراف</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>