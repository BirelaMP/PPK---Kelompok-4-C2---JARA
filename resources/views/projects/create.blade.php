<!DOCTYPE html>
<html>
<head>
    <title>JARA - Tambah Project</title>
</head>
<body>

    <h1>Tambah Project</h1>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div>
            <label>Nama Project</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <br>

        <div>
            <label>Deskripsi</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <br>

        <button type="submit">Simpan</button>
        <a href="{{ route('projects.index') }}">Kembali</a>
    </form>

</body>
</html>