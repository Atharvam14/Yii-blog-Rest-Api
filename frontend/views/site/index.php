<?php

use yii\helpers\Json;

/** @var yii\web\View $this */
/** @var array|null $tokenData */

$this->title = 'My Yii Application';
?>
<?php
$this->registerJsFile('@web/js/comment.js',[
    'depends' => [ yii\web\JqueryAsset::class],
]);

if ($tokenData !== null) {
    $this->registerJs('window.blogApiAuth = ' . Json::htmlEncode($tokenData) . ';');
}

?>

<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Welcome to the Blog API!</h1>
            <p class="fs-5 fw-light">Explore our API to manage posts and comments.</p>
            <p><a class="btn btn-lg btn-primary" href="/yii2-blog-api/frontend/web/post?_format=json">View Posts</a></p>
        </div>
    </div>
    <div>
        <div>
        <button onclick="loadComments()">Get Comments</button>
        <button onclick="addComment()" >Add Comment</button>
         </div>
        <div id="comments"></div>

     
        
       
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-6">
                <h2>Posts</h2>
                <p>Manage your blog posts with our RESTful API. Create, read, update, and delete posts as needed.</p>
                <p><a class="btn btn-outline-secondary" href="/yii2-blog-api/frontend/web/post">Go to Posts &raquo;</a></p>
            </div>
            <div class="col-lg-6">
                <h2>Comments</h2>
                <p>Engage with your audience by managing comments on your posts. Moderate and respond to feedback easily.</p>
                <p><a class="btn btn-outline-secondary" href="/yii2-blog-api/frontend/web/comment">Go to Comments &raquo;</a></p>
            </div>
        </div>
    </div>
