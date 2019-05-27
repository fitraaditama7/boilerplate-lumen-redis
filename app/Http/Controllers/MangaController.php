<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use App\Manga;

class MangaController extends Controller {
    public function index() {
        $key = "data-manga";

        // Get Cache if key exist in redis database


            // getCache($key);
            $data = Manga::select('*')
                            // ->where('id', '=', '230')
                            ->get();

            return responses($data, null);
            // setCache($key, $data);



        // Set Cache if value of data in Key equals nil in redis database
        // deleteCache($key);

    }

}
