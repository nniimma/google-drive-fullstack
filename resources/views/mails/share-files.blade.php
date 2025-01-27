<h2>Hello <b>{{ $user->name }}</b></h2>

<h4>User <b>{{ $author->name }}</b> share the following files to you.</h4>
<hr>
@foreach ($files as $file)
    <p>{{ $file->is_folder ? 'Folder: ' : 'File: ' }}{{ $file->name }}</p>
@endforeach
