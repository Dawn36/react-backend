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
    public function getStatus()
    {
        return DB::select(DB::raw("SELECT 
        count(private.alarm_type.alarm_type),alarm_type
        
              FROM private.alarm_instance 
              INNER JOIN private.site ON private.alarm_instance.site_id = private.site.site_id 
              INNER JOIN private.alarm_type ON private.alarm_instance.device_alarm_type_id = private.alarm_type.id
             
         group by (private.alarm_type.alarm_type)
        "));
        // INNER JOIN  private.site_last_seen on private.site_last_seen.site_id=private.site.site_id
        //where private.site_last_seen.status='true'
    }
    public function getStatusOnlineOffline()
    {
        return DB::select(DB::raw("select sum(case when private.site_last_seen.status = 'true' then 1 else 0 end ) as online_site,
        sum(case when private.site_last_seen.status = 'false' then 1 else 0 end ) as offline_site
        from private.site inner join private.site_last_seen on private.site.site_id=private.site_last_seen.site_id        
        "));
    }
    public function getsiteData()
    {
        return DB::select(DB::raw("SELECT count(*) AS value,string_agg(DISTINCT s.site_name, ',') site_name, private.alarm_type.alarm_type  FROM private.alarm_instance 
        INNER JOIN private.site s ON private.alarm_instance.site_id = s.site_id 
        INNER JOIN private.alarm_type ON private.alarm_instance.device_alarm_type_id = private.alarm_type.id
        INNER JOIN  private.site_last_seen on private.site_last_seen.site_id=s.site_id
         group by (private.alarm_type.alarm_type)       
        "));
    }
    public function getTotalSite()
    {
        return DB::select(DB::raw("select count(*) as total_site from private.site"));
    }
    public function getActiveEvent()
    {
        return DB::select(DB::raw("select s.site_id,s.site_name,sts.status As online_offline_status,r.rname,al.set_time,al.reset_time
        from private.site s inner join private.region r on s.rid=r.rid inner join private.site_last_seen sts
        on sts.site_id=s.site_id inner join private.alarm_instance al on al.site_id=s.site_id"));
    }
    public function siteLiveWidget()
    {
        return DB::select(DB::raw("select *,r.rname from private.site s inner join private.region r on s.rid=r.rid"));
    }
}
