@extends('layouts.app')
@section('title', 'Pilih — ' . $poll->title)

@section('content')
<div class="min-h-screen bg-black flex flex-col">
    <nav class="bg-black/80 backdrop-blur border-b border-white/5 px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-primary-600 rounded-lg flex items-center justify-center">
                <svg viewBox="0 0 24 24" fill="white" class="w-3.5 h-3.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="font-bold text-white">e-<span class="text-primary-500">Polling</span></span>
        </a>
        <span class="text-xs text-gray-500 bg-gray-900 px-3 py-1.5 rounded-full border border-white/10">
            <i class="fas fa-key text-primary-500 mr-1"></i>{{ $poll->primary_key_label }}: <strong class="text-white">{{ $voterKey }}</strong>
        </span>
    </nav>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-2xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-white mb-2">{{ $poll->title }}</h1>
                <p class="text-gray-400">Pilih salah satu kandidat di bawah ini</p>
            </div>

            <div id="options-grid" class="grid {{ $poll->options->count() <= 2 ? 'grid-cols-1 sm:grid-cols-2' : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' }} gap-4 mb-6">
                @foreach($poll->options as $option)
                <label class="option-card cursor-pointer block" data-id="{{ $option->id }}">
                    <input type="radio" name="poll_option" value="{{ $option->id }}" class="sr-only" required>
                    <div class="h-full p-0 overflow-hidden border-2 border-transparent hover:border-primary-600 transition-all duration-300 option-box" style="background:linear-gradient(145deg,#161b22,#0d1117);border-radius:1rem;box-shadow:0 8px 32px rgba(0,0,0,.5);">
                        @if($option->image)
                        <img src="{{ $option->image_url }}" alt="{{ $option->name }}" class="w-full h-44 object-cover">
                        @else
                        <div class="w-full h-28 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                            <i class="fas fa-user text-4xl text-gray-600"></i>
                        </div>
                        @endif
                        <div class="p-4 text-center">
                            <h3 class="font-bold text-white text-base">{{ $option->name }}</h3>
                            @if($option->description)
                            <p class="text-gray-500 text-xs mt-1">{{ $option->description }}</p>
                            @endif
                            <div class="mt-3 w-6 h-6 rounded-full border-2 border-gray-600 mx-auto option-check flex items-center justify-center transition-all">
                                <i class="fas fa-check text-xs text-white hidden check-icon"></i>
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="text-center">
                <p id="select-hint" class="text-gray-500 text-sm mb-4">Pilih salah satu kandidat untuk melanjutkan</p>
                <button id="vote-btn" onclick="submitVote()" disabled
                    class="btn-primary px-10 py-3.5 text-base opacity-40 cursor-not-allowed transition-all" id="vote-btn" style="transform:none;box-shadow:none;">
                    <i class="fas fa-vote-yea"></i> <span id="vote-btn-text">Konfirmasi Pilihan</span>
                </button>
                <p class="mt-4 text-xs text-gray-600">
                    <i class="fas fa-lock mr-1"></i> Pilihan Anda bersifat rahasia dan tidak dapat diubah setelah dikonfirmasi
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedOptionId = null;
let selectedOptionName = '';

document.querySelectorAll('.option-card').forEach(card => {
    card.addEventListener('click', function() {
        // Reset all
        document.querySelectorAll('.option-box').forEach(b => {
            b.classList.remove('border-primary-600', 'bg-primary-900/10');
            b.querySelector('.option-check').classList.remove('bg-primary-600', 'border-primary-600');
            b.querySelector('.check-icon').classList.add('hidden');
        });

        // Select this
        const box = this.querySelector('.option-box');
        box.classList.add('border-primary-600', 'bg-primary-900/10');
        box.querySelector('.option-check').classList.add('bg-primary-600', 'border-primary-600');
        box.querySelector('.check-icon').classList.remove('hidden');

        selectedOptionId = this.dataset.id;
        selectedOptionName = this.querySelector('h3').textContent;

        const btn = document.getElementById('vote-btn');
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        document.getElementById('select-hint').textContent = `Anda memilih: ${selectedOptionName}`;
        document.getElementById('vote-btn-text').textContent = `Pilih: ${selectedOptionName}`;
    });
});

function submitVote() {
    if (!selectedOptionId) return;

    Swal.fire({
        title: 'Konfirmasi Pilihan',
        html: `Anda akan memilih:<br><strong class="text-lg">${selectedOptionName}</strong><br><br><small class="text-gray-400">Pilihan tidak dapat diubah setelah dikonfirmasi.</small>`,
        icon: 'question',
        background: '#1f2937', color: '#fff', iconColor: '#dc2626',
        showCancelButton: true,
        confirmButtonColor: '#dc2626', cancelButtonColor: '#374151',
        confirmButtonText: '<i class="fas fa-vote-yea mr-1"></i> Ya, Konfirmasi!',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (!result.isConfirmed) return;

        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, background: '#1f2937', color: '#fff', didOpen: () => Swal.showLoading() });

        fetch('{{ route("poll.vote", $poll->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                poll_option_id: selectedOptionId,
                voter_key: '{{ $voterKey }}'
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success', title: 'Suara Berhasil Dicatat!',
                    text: data.message, background: '#1f2937', color: '#fff',
                    iconColor: '#22c55e', confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Lihat Hasil Polling'
                }).then(() => { window.location.href = '{{ route("poll.result", $poll->slug) }}'; });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message, background: '#1f2937', color: '#fff', confirmButtonColor: '#dc2626' });
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan jaringan.', background: '#1f2937', color: '#fff', confirmButtonColor: '#dc2626' });
        });
    });
}
</script>
@endpush
