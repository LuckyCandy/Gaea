<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SysLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
	public function list(Request $request)
    {
    	$builder = SysLog::with('user:id,name');

    	if ($type = $request->get('type', null)) {
			$builder->where('type', $type);
    	}

    	if ($user = $request->get('user', null)) {
    		$builder->whereHas('user', function(Builder $query) use ($user){
    			$query->where('name', 'like', "%{$user}%");
    		});
    	}

        $total = $builder->count();
        return response()->jsr(200, [
            'total' => $total,
            'list' => $builder->forPage($request->get('page', 1), $request->get('page_size', 1))->get()
        ]);
    }
}