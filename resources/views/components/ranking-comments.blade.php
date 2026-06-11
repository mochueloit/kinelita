@props(['comments'])

<aside id="comentarios" {{ $attributes->merge(['class' => 'comments-wall scroll-mt-4']) }}>
    <div class="comments-wall-header">
        <div>
            <p class="text-white/80 text-xs font-medium uppercase tracking-wider"></p>
            <h2 class="text-lg font-bold text-white">Quejate por aqui</h2>
        </div>
        <span class="comments-count">{{ $comments->count() }}</span>
    </div>

    <div class="comments-wall-feed ranking-comments-list">
        @if ($comments->isEmpty())
            <div class="comments-empty">
                <p class="text-slate-500 text-sm font-medium">Nadie ha dicho nada aún</p>
                <p class="text-slate-400 text-xs mt-1">Sé el primero en tirar data del ranking</p>
            </div>
        @else
            @foreach ($comments as $index => $comment)
                @php
                    $displayName = $comment->name ?: 'Anónimo';
                    $initial = mb_strtoupper(mb_substr($displayName, 0, 1));
                    $colorClass = ['comments-avatar-a', 'comments-avatar-b', 'comments-avatar-c'][$index % 3];
                @endphp
                <article class="comments-bubble-row">
                    <div class="comments-avatar {{ $colorClass }}">{{ $initial }}</div>
                    <div class="comments-bubble">
                        <div class="comments-bubble-meta">
                            <span class="comments-bubble-name">{{ $displayName }}</span>
                            <time>{{ $comment->created_at->diffForHumans() }}</time>
                        </div>
                        <p class="comments-bubble-text">{{ $comment->comment }}</p>
                    </div>
                </article>
            @endforeach
        @endif
    </div>

    <div class="comments-compose">
        <p class="comments-compose-title">Escribe tu comentario</p>

        <form method="POST" action="{{ route('ranking.comments.store') }}" class="space-y-3 relative">
            @csrf

            <div class="hp-trap" aria-hidden="true">
                <label for="website">No llenar</label>
                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
            </div>

            <input
                type="text"
                name="name"
                id="comment_name"
                value="{{ old('name') }}"
                placeholder="Tu nombre (opcional)"
                maxlength="100"
                class="comments-input"
            >

            <input
                type="email"
                name="email"
                id="comment_email"
                value="{{ old('email') }}"
                placeholder="Correo *"
                required
                class="comments-input"
            >

            <textarea
                name="comment"
                id="comment_body"
                rows="2"
                required
                maxlength="500"
                placeholder="¿Quién va primero? ¿Quién se cae?..."
                class="comments-input comments-textarea resize-none"
            >{{ old('comment') }}</textarea>

            <details class="comments-honeypot">
                <summary>Verificación anti-robot</summary>
                <label for="robot_check" class="block text-xs text-slate-400 mt-2">
                    Si no eres robot, deja esto vacío
                </label>
                <input type="text" name="robot_check" id="robot_check" value="{{ old('robot_check') }}" class="comments-input mt-1" autocomplete="off">
                @error('robot_check')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </details>

            <button type="submit" class="comments-submit">Publicar</button>
        </form>
    </div>
</aside>
