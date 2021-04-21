<?php
//klssen skulle tex kunna heta Dog istället i dett fall!. Eftersom den bara jobbar med dog API 
class App
{
    private static $endpoint_list = 'https://dog.ceo/api/breeds/list/all';
    private static $endpoint_random = 'https://dog.ceo/api/breeds/image/random';

    public static function main()
    {
        try {
            $array = self::getData(self::$endpoint_list);
            self::renderList($array['message']);
            self::renderMainContent();
        } catch (Exception $error) {
            echo $error->getMessage();
        }
    }
    private static function getData($endpoint)
    {
        $json = @file_get_contents($endpoint);
        if (!$json) {
            throw new Exception('Could not access ' . $endpoint);
        }
        return json_decode($json, true);
    }
    private static function renderList($array)
    {
        $container = "
        <div class='col-sm-3'>
            <h1 class='text-center'>
                <a href='index.php'>Dog API</a>
            </h1>
            <ul class='list-group'>";
        foreach (array_keys($array) as $dog) {
            $container .= "
            <li class='list-group-item text-capitalize text-center bg-light'>
                    <a href='?breed=$dog'>$dog</a>
            </li>";
        }
        echo "$container</div></ul>";
    }
    private static function renderMainContent()
    {
        if (isset($_GET['breed'])) {
            $breed = $_GET['breed']; // gröm ej htmlspecialchars
            $data = self::getData("https://dog.ceo/api/breed/$breed/images");
            $container = "
            <div class='col-sm-9'>
                <div class='row'>
                    <h1 class='text-capitalize text-center'>$breed</h1>
                </div>
                <div class='row justify-content-center'>";
            foreach ($data['message'] as $image) {
                $container .= "
                <div class='image-container m-2 col-sm-6 col-md-4 col-lg-3'>
                    <img class='img-fluid img-thumbnail w-100 bg-light' src='$image' alt='$breed'>
                </div>";
            }
            echo "$container</div>"; // avsluta inte såhär
        } else {
            $data = self::getData(self::$endpoint_random);
            $img_src = $data['message'];
            echo "
            <div class='col-sm-9'>
                <h1 class='text-center'>
                    <a href='index.php'>Random image</a>
                </h1>
                <div class='d-flex'>
                    <img class='img-fluid img-thumbnail flex-grow-1 p-3 bg-light' src='$img_src' alt='Random dog'>
                </div>
            </div>";
        }
    }
}
