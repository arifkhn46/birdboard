<?php

namespace App;

trait RecordActivity
{
  public static function bootRecordActivity()
  {


    foreach (self::recordableEvents() as $event) {
      static::$event(function ($model) use ($event) {
        $model->recordActivty($model->activityDescription($event));
      });
    }
  }

  protected function activityDescription($description)
  {
    return $description = "{$description}_" . strtolower(class_basename($this));
  }

  protected static function recordableEvents()
  {
    if (isset(static::$recordableEvents)) {
      return static::$recordableEvents;
    }
    return ['created', 'updated'];
  }

  public function activity()
  {
    return $this->morphMany(Activity::class, 'subject')->latest();
  }

  public function recordActivty($description)
  {
    $this->activity()->create([
      'user_id' => ($this->project ?? $this)->owner->id,
      'description' => $description,
      'changes' => $this->activityChanges(),
      'project_id' => class_basename($this) == 'Project' ? $this->id : $this->project_id,
    ]);
  }

  public function activityChanges()
  {
    if ($this->wasChanged()) {
        return [
            'before' => array_except(
                array_diff($this->getOriginal(), $this->getAttributes()), 'updated_at'
            ),
            'after' => array_except(
                $this->getChanges(), 'updated_at'
            )
        ];
    }
  }
}

