<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $project->name }} · JARA</title>
        <style>
            :root { font-family: Inter, ui-sans-serif, system-ui, sans-serif; color: #3d3140; background: #fff; }
            * { box-sizing: border-box; }
            body { margin: 0; min-height: 100vh; background: linear-gradient(135deg, #fff 0%, #fdfbf1 100%); }
            .jara-page { width: min(100% - 32px, 800px); margin: 0 auto; padding: 56px 0; }
            .brand { color: #744577; font-size: 1.05rem; font-weight: 800; letter-spacing: .12em; }
            h1 { margin: 13px 0 34px; color: #362039; font-size: clamp(2rem, 7vw, 3.25rem); letter-spacing: -.045em; }
            .card { padding: 27px; margin-top: 20px; border: 1px solid #eee7df; border-radius: 22px; background: #fff; box-shadow: 0 12px 32px rgba(71, 40, 73, .07); }
            .section-heading { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
            h2 { margin: 0; color: #744577; font-size: 1.12rem; }
            .member-list { display: grid; gap: 10px; }
            .member { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f3efec; }
            .member:last-child { border-bottom: 0; }
            .avatar { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 50%; color: #fff; background: #84c5b1; font-size: .9rem; font-weight: 800; }
            .member:nth-child(even) .avatar { background: #accfa3; color: #3d5635; }
            .member-name { flex: 1; font-weight: 650; }
            button, .button { border: 0; border-radius: 10px; padding: 10px 14px; font: inherit; font-weight: 700; cursor: pointer; text-decoration: none; }
            .button-primary { color: #fff; background: #744577; box-shadow: 0 5px 12px rgba(116, 69, 119, .18); }
            .button-primary:hover { background: #603563; }
            .button-quiet { color: #744577; background: #f8f3f8; }
            .button-remove { padding: 7px 10px; color: #9a4d57; background: #fff2f1; font-size: .83rem; }
            .empty { margin: 12px 0; color: #857987; }
            .progress-number { margin: 7px 0 13px; color: #744577; font-size: 2.4rem; font-weight: 800; letter-spacing: -.06em; }
            .progress-track { overflow: hidden; height: 11px; border-radius: 999px; background: #f0e9b6; }
            .progress-value { height: 100%; border-radius: inherit; background: #744577; transition: width .2s ease; }
            .progress-copy { margin: 12px 0 0; color: #706473; }
            .flash { margin-bottom: 20px; padding: 12px 15px; border-radius: 12px; color: #42673e; background: #edf7eb; }
            .modal[hidden] { display: none; }
            .modal { position: fixed; z-index: 10; inset: 0; display: grid; place-items: center; padding: 20px; background: rgba(46, 29, 48, .34); }
            .modal-panel { width: min(100%, 420px); padding: 25px; border-radius: 20px; background: #fff; box-shadow: 0 18px 55px rgba(46, 29, 48, .24); }
            .modal-panel h2 { margin-bottom: 19px; }
            label { display: grid; gap: 8px; color: #5a4c5c; font-size: .9rem; font-weight: 700; }
            select { width: 100%; padding: 11px; border: 1px solid #ded5df; border-radius: 10px; color: #3d3140; background: #fff; font: inherit; }
            .error { margin: 8px 0 0; color: #b23b4a; font-size: .86rem; }
            .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
            @media (max-width: 520px) { .jara-page { padding: 35px 0; } .card { padding: 21px; } .section-heading { align-items: flex-start; flex-direction: column; } }
        </style>
    </head>
    <body>
        <main class="jara-page">
            <div class="brand">JARA</div>
            <h1>{{ $project->name }}</h1>

            @if (session('success'))
                <div class="flash" role="status">{{ session('success') }}</div>
            @endif

            <section class="card" aria-labelledby="members-title">
                <div class="section-heading">
                    <h2 id="members-title">Members</h2>
                    <button class="button-primary" type="button" data-open-modal {{ $availableUsers->isEmpty() ? 'disabled' : '' }}>+ Add Member</button>
                </div>

                @if ($project->members->isEmpty())
                    <p class="empty">Belum ada member di project ini.</p>
                @else
                    <div class="member-list">
                        @foreach ($project->members as $member)
                            <div class="member">
                                <span class="avatar" aria-hidden="true">{{ str($member->name)->substr(0, 1)->upper() }}</span>
                                <span class="member-name">{{ $member->name }}</span>
                                <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}" onsubmit="return confirm('Hapus member ini dari project?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button-remove" type="submit">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="card" aria-labelledby="progress-title">
                <h2 id="progress-title">Progress</h2>
                <div class="progress-number">{{ $progress }}%</div>
                <div class="progress-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $progress }}" aria-label="Project progress">
                    <div class="progress-value" style="width: {{ $progress }}%"></div>
                </div>
                <p class="progress-copy">{{ $completedTasks }} of {{ $totalTasks }} tasks completed</p>
            </section>
        </main>

        <div class="modal" data-modal hidden>
            <form class="modal-panel" method="POST" action="{{ route('projects.members.store', $project) }}">
                @csrf
                <h2>Add Member</h2>
                <label for="user_id">
                    Select User
                    <select id="user_id" name="user_id" required>
                        <option value="">Pilih user</option>
                        @foreach ($availableUsers as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </label>
                @error('user_id') <p class="error">{{ $message }}</p> @enderror
                <div class="modal-actions">
                    <button class="button-quiet" type="button" data-close-modal>Cancel</button>
                    <button class="button-primary" type="submit">Add Member</button>
                </div>
            </form>
        </div>

        <script>
            const modal = document.querySelector('[data-modal]');
            const openModal = document.querySelector('[data-open-modal]');
            const closeModal = document.querySelector('[data-close-modal]');

            openModal?.addEventListener('click', () => modal.hidden = false);
            closeModal?.addEventListener('click', () => modal.hidden = true);
            modal?.addEventListener('click', (event) => {
                if (event.target === modal) modal.hidden = true;
            });

            @if ($errors->has('user_id'))
                modal.hidden = false;
            @endif
        </script>
    </body>
</html>
