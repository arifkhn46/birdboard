<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Birdboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <h1>Birdboard</h1>

  <ul>
    @foreach ($projects as $project)
      <li>
        <a href="{{ $project->path() }}">{{ $project->title }}</a>
      </li>
    @endforeach
  </ul>
</body>
</html>