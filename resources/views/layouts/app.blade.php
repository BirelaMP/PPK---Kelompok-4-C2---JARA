<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>JARA</title></head><body>
<nav><a href="{{ route('lists.index') }}">JARA</a> | <a href="{{ route('lists.index') }}">Daftar</a> | <a href="{{ route('tasks.index') }}">Tugas</a> @if(auth()->user()->is_admin) | <a href="{{ route('admin.users') }}">Admin</a> @endif <form style="display:inline" method="POST" action="{{ route('logout') }}">@csrf <button>Logout</button></form></nav><hr>
@if(session('success'))<p>{{ session('success') }}</p>@endif @if($errors->any())<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
@yield('content')
</body></html>
