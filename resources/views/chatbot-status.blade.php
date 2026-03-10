<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Chatbot MMQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen py-10 px-4 font-sans">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-blue-400">Status Koneksi Chatbot</h1>
            <a href="/chatbot-test" class="text-sm text-gray-400 hover:text-white">&larr; Chatbot Test</a>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <h2 class="text-xl font-semibold mb-4">Hasil Tes Koneksi API</h2>
            <p class="text-sm text-gray-400 mb-6">Sistem mencoba menghubungi Google Gemini (Model: <code>{{ $model }}</code>) dengan setiap API Key yang terdaftar.</p>

            <div class="space-y-4">
                @foreach ($results as $res)
                <div class="flex items-center justify-between p-4 rounded-lg border {{ $res['status'] == 200 ? 'bg-green-900/30 border-green-700' : 'bg-red-900/30 border-red-700' }}">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-sm text-gray-300">Key #{{ $res['index'] }}</span>
                            @if($res['status'] == 200)
                                <span class="bg-green-600 text-white text-xs px-2 py-0.5 rounded">BERHASIL</span>
                            @else
                                <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded">GAGAL</span>
                            @endif
                        </div>
                        <div class="mt-2 text-sm text-gray-400">
                            Durasi: <span class="{{ $res['duration'] > 2 ? 'text-yellow-400' : 'text-green-400' }}">{{ number_format($res['duration'], 2) }}s</span>
                        </div>
                    </div>
                    
                    <div class="text-right max-w-xs">
                        @if($res['status'] == 200)
                            <p class="text-sm italic text-gray-300">"{{ Str::limit($res['message'], 30) }}"</p>
                        @else
                            <p class="text-sm text-red-400 font-bold">{{ Str::limit($res['error'], 40) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-gray-700 text-center">
                <a href="/chatbot-status" class="inline-block bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-full transition">
                    Refresh Status
                </a>
            </div>
        </div>
    </div>
</body>
</html>
