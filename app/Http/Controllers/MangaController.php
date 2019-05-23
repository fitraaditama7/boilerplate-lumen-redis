<?php

namespace App\Http\Controllers;

use App\Manga;

class MangaController extends Controller {
    public function index() {
        $key = "data-manga";

        // Get Cache if key exist in redis database
        getCache($key);

        $data = Manga::all();

        // Set Cache if value of data in Key equals nil in redis database
        setCache($key, $data);
        // deleteCache($key);

        return responses($data, null);
    }

}
