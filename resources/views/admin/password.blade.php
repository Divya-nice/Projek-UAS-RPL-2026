@extends('layouts.admin')

@section('title', 'Ubah Password')

@section('content')
<div class="mx-auto max-w-xl space-y-6">

    {{-- Judul --}}
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Ubah Password</h1>
        <p class="mt-1 text-slate-500">Pastikan password baru Anda aman dan mudah diingat</p>
    </div>

    <form action="{{ route('admin.password.update') }}" method="POST" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" x-data>
        @csrf

        <div class="space-y-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Password Saat Ini</label>
                <div class="relative">
                    <input type="password" name="current_password" placeholder="Masukkan password saat ini" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 pr-11 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
                    <button type="button" data-toggle-password class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 pr-11 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
                    <button type="button" data-toggle-password class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 pr-11 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
                    <button type="button" data-toggle-password class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E7BC8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1566AD]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                Simpan Password
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    (function () {
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-toggle-password]');
            if (!btn) return;
            const input = btn.parentElement.querySelector('input');
            if (input) input.type = input.type === 'password' ? 'text' : 'password';
        });
    })();
</script>
@endpush

@endsection
