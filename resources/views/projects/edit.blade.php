<!DOCTYPE html>
<html>
<head>
    <title>JARA - Edit Project</title>
</head>
<body>

    <h1>Edit Project</h1>

    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Nama Project</label><br>
            <input type="text" name="name" value="{{ old('name', $project->name) }}" required>
        </div>

        <br>

        <div>
            <label>Deskripsi</label><br>
            <textarea name="description">{{ old('description', $project->description) }}</textarea>
        </div>

        <br>

        <button type="submit">Update</button>
        <a href="{{ route('projects.index') }}">Kembali</a>
    </form>

</body>
</html>