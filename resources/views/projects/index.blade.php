<!DOCTYPE html>
<html>
<head>
    <title>JARA - Projects</title>
</head>
<body>

    <h1>Daftar Project</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('projects.create') }}">+ Tambah Project</a>

    <hr>

    @forelse($projects as $project)
        <div>
            <h2>{{ $project->name }}</h2>

            @if($project->description)
                <p>{{ $project->description }}</p>
            @endif

            <a href="{{ route('projects.edit', $project) }}">Edit</a>

            <form action="{{ route('projects.destroy', $project) }}"
                  method="POST"
                  style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </div>

        <hr>
    @empty
        <p>Belum ada project.</p>
    @endforelse

</body>
</html>