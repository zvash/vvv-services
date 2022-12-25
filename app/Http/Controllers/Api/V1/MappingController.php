<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Mapping;
use App\Traits\Responses\ResponseMaker;
use Illuminate\Http\Request;

class MappingController extends Controller
{
    use ResponseMaker;
    public function all(Request $request)
    {
        $ip = $request->ip();
        $mappings = Mapping::query()
            ->where('destination_ip', $ip)
            ->get();
        return $this->success($mappings);
    }
}
