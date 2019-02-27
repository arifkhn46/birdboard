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
    return ['created', 'updated', 'deleted'];
  }

  public function activity()
  {
      return $this->morphMany(Activity::class, 'subject')->latest();
  }

  public function recordActivty($description)
    {
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) == 'Project' ? $this->id : $this->project_id,
        ]);
    }

    public function activityChanges()
    {
        return $this->wasChanged() ? [
            'before' => array_diff($this->getOriginal(), $this->getAttributes()),
            'after' => $this->getChanges(),
        ] : null;
    }
}