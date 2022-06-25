<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Site extends Model
{
    use HasFactory;
    public function siteMap()
    {
        return DB::select(DB::raw("SELECT s.site_id,s.site_name,s.latitude,s.longitude,sls.status,sls.epoch_time FROM private.site s inner join private.site_last_seen sls on 
        s.site_id=sls.site_id
        "));
    }
}
