<?php
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
trait Query{
    public function scopeSearchBy($query,$column,$data,$type=null)
    {
        $isBoolean=is_bool($data);
        $isValidData= $isBoolean?true: (!!$data);
        if($isValidData){
            if($type=='whereDate'){
                return  $query->whereDate($column, $type ,$data);
            }
            if($type=='whereMonth'){
                return  $query->whereMonth($column, $type ,$data);
            }
            if($type=='whereDay'){
                return  $query->whereDay($column, $type ,$data);
            }
            if($type=='whereYear'){
                return  $query->whereYear($column, $type ,$data);
            }
            if($type=='whereTime'){
                return  $query->whereTime($column, $type ,$data);
            }
            if($type=='whereNull'){
                return  $query->whereNull($column);
            }
            if($type=='whereNotNull '){
                return  $query->whereNotNull($column);
            }
            if($type=='whereBetween'){
                return  $query->whereBetween($column, $data);
            }
            if($type=='whereNotBetween'){ 
                return  $query->whereNotBetween($column, $data);
            }
            if($type=='whereNotIn'){
                return  $query->whereNotIn($column, $data);
            }
            if($type=='whereColumn'){
                return  $query->whereNotIn($column, $data);
            }
            if($type==null){
                return  $query->where($column,$data);
            }
            return  $query->where($column,$type,$data);
        }
        return $query;
    }


    public function scopeSearchByRelationship($query,$relationships=null,$column=null,$data=null,$type='=')
    {
        
        $recrusive=function ($query,$relationships,$column,$data,$type) use (&$recrusive)
        {
            $removed=array_shift($relationships);
            $query->whereHas( $removed, function (Builder $query) use ($relationships,$column,$data,$type,$recrusive) 
            {
                $continue=count($relationships)>=1; 
                if($continue){ return $recrusive($query,$relationships,$column,$data,$type);}

                if($type=='whereDate'){
                    return  $query->whereDate($column, $type ,$data);
                }
                if($type=='whereMonth'){
                    return  $query->whereMonth($column, $type ,$data);
                }
                if($type=='whereDay'){
                    return  $query->whereDay($column, $type ,$data);
                }
                if($type=='whereYear'){
                    return  $query->whereYear($column, $type ,$data);
                }
                if($type=='whereTime'){
                    return  $query->whereTime($column, $type ,$data);
                }
                if($type=='whereNull'){
                    return  $query->whereNull($column);
                }
                if($type=='whereNotNull '){
                    return  $query->whereNotNull($column);
                }
                if($type=='whereBetween'){
                    return  $query->whereBetween($column, $data);
                }
                if($type=='whereNotBetween'){ 
                    return  $query->whereNotBetween($column, $data);
                }
                if($type=='whereNotIn'){
                    return  $query->whereNotIn($column, $data);
                }
                if($type=='whereColumn'){
                    return  $query->whereNotIn($column, $data);
                }
                if($type==null){
                    return  $query->where($column,$data);
                }
                return  $query->where($column,$type,$data);
            });         
        };
         
        $isValidData= is_bool($data)?true: (!!$data);
        if(!$isValidData){return $query;}
        $relationships=is_string($relationships)?[$relationships]:$relationships;

        return $recrusive($query,$relationships,$column,$data,$type);

}


}