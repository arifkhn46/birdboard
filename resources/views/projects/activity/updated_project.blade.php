@if (count($activity->changes['after']) == 1)
  {{ $activity->owner->name }} updated the {{ key($activity->changes['after']) }} of the project
@else
  {{ $activity->owner->name }} updated the project
@endif