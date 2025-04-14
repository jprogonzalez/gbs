<?php 

namespace App\Traits;

use Carbon\Carbon;

trait SearchPattern 
{
    public function scopeSearchPattern($query,$value, array $fields = []) 
    {
        foreach($fields as $index => $field) {
            if ($index == 0) {
                $query->where($field,'like',"%{$value}%");
            }else{
                $query->orWhere($field,'like',"%{$value}%");
            }   
        }
    }
}