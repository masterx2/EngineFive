<?php namespace App;

use App\Controllers\Visitor,
    App\Models\Comment,
    Silex\Application,
    App\Controllers\Auth,
    App\Views\Main,
    App\Models\Post,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;
use App\Models\Collector;

$app = new Application();
$app['debug'] = true;

$app->before(function (Request $request, Application $app) {
    $auth = new Auth();
    $app['auth'] = $auth->getAuth();
});

$app->get('/', function (Request $request) use ($app) {
    $post = new Post();
    $main_view = new Main();
    $visitor = new Visitor();

    $data = $post->getAll(0,3);
    $tags = $post->getTags();
    $projects = $post->query(['tags' => 'project'], 0, 3);

    return $main_view->render('main.tpl', [
        'auth' => $visitor->getVisitor($app['auth']['visitor']),
        'data' => $data,
        'projects' => $projects,
        'tags' => $tags
    ]);

});

$app->get('/blog', function (Request $request) use ($app) {

    $post = new Post();
    $main_view = new Main();

    $page = intval($request->query->get('page'));

    $data = $data = $post->getAll(max($page-1,0));
    $tags = $post->getTags();

    return $main_view->render('post_feed.tpl', [
        'data' => $data,
        'tags' => $tags
    ]);

});

$app->get('/post/delete/{id}', function($id) use ($app) {
    $post = new Post();
    $post->delById($id);
    return 'Post Deleted';
});

$app->get('/post/view/{id}', function($id) use ($app) {
    $post = new Post();
    $comment = new Comment();
    $main_view = new Main();

    $post_data = $post->getById($id);

    $post->getById($id);
    return $main_view->render('post_view.tpl', [
        'scripts' => $main_view->getScript('tinymce.jquery.min.js'),
        'record' => $post_data,
        'comment_form' => $main_view->getForm($comment->schema, [
                'post_id' => $post_data['id']
            ]),
        'comments' => $main_view->render('comments.tpl', [
                'comments' => $comment->getById($id)
            ])
    ]);
});

$app->get('/post/edit/{id}', function($id) use ($app) {
    $post = new Post();

    $main_view = new Main();
    return $main_view->render('index.tpl',[
        'content' => $main_view->getForm(Post::$schema, $post->getById($id)),
        'scripts' => $main_view->getScript('tinymce.jquery.min.js')
    ]);
});

$app->post('/post/edit/{id}', function(Request $request, $id) use ($app) {
    $post = new Post();
    $post->updateById($id, $request->request->all());
    return 'Post Added';
});

$app->get('/post/add', function() use ($app) {
    $main_view = new Main();
    return $main_view->render('index.tpl',[
        'content' => $main_view->getForm(Post->$schema),
        'scripts' => $main_view->getScript('tinymce.jquery.min.js')
    ]);
});

$app->post('/post/add', function(Request $request) use ($app) {
    $post = new Post();
    $post->addNext($request->request->all());
    return 'Post Added';
});

$app->post('/dump', function(Request $request) use ($app) {
    $main_view = new Main();
    return $main_view->render('dump.tpl', [
        'data' => $request->request->all()
    ]);
});

$app->get('/blog/{type}/{param}', function (Request $request, $type, $param) use ($app) {
    $post = new Post();
    $main_view = new Main();

    $page = intval($request->query->get('page'));

    $data = $post->query([urldecode($type) => urldecode($param)], max($page-1,0));
    $tags = $post->getTags();

    return $main_view->render('post_feed.tpl', [
        'data' => $data,
        'tags' => $tags
    ]);
});

$app->post('/api', function(Request $request) use ($app) {

    $main_view = new Main();

    $collector = new Collector();
    $collector->addNext($request->request->all());

    return $main_view->render('dump.tpl', [
        'data' => $request->request->all()
    ]);
});

$app->run();