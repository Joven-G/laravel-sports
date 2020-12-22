<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
  use HasFactory;

  protected $guarded = [];
  
  public function user()
  {
      return $this->belongsTo('App\Models\User');
  }

  public function scopeEventNotAvailable($query, $startHour, $endHour)
  {
  	$notAvailable = $query->whereBetween('start', [$startHour, $endHour])
  		->where('field_number', request('field_number'))
      ->orWhere(function ($nav) use ($startHour, $endHour) {

        $nav->whereBetween('end', [$startHour, $endHour])
            ->where('field_number', request('field_number'));
      })
      ->orWhere(function ($query) use ($startHour, $endHour) {

        $query->where('start', '<', $startHour)
		          ->where('end',   '>', $endHour)
		          ->where('field_number', request('field_number'));
      });

    return $notAvailable;
  }

  public function scopeEventNotAvailableUpdate($query, $startHour, $endHour)
  {
  	$notAvailableUpdate = $query->where('id', request('id'))
        ->orWhere(function ($query) {
          $query->where('id', request('id'));
        })
        ->where('start', '<=', $startHour)
        ->where('end',   '>=', $endHour)
        ->orWhere(function ($query) use ($startHour, $endHour) {

          $query->whereBetween('start',[$startHour, $endHour])
                ->orWhereBetween('end',[$startHour, $endHour]);
        })
        ->where('field_number', request('field_number'));

    return $notAvailableUpdate;
  }

}
