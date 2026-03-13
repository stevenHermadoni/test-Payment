<!DOCTYPE html>
<html>

<head>
    <title>Ruang Ujian PTN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<x-app-layout>
    <x-slot name="header">
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h2 class="text-2xl font-bold mb-4">Lembar Ujian Tryout PTN</h2>
                <p class="text-gray-600 mb-6">ID Pesanan: {{ $id }}</p>
                
                <div class="border-t pt-4">
                    <p class="font-medium text-lg">Soal 1: Manakah yang merupakan bahasa pemrograman web?</p>
                    <div class="mt-2">
                        <input type="radio" name="q1"> PHP <br>
                        <input type="radio" name="q1"> Karbit
                    </div>
                </div>
                
                <button class="mt-8 bg-blue-600 text-white px-4 py-2 rounded">Selesai Ujian</button>
            </div>
        </div>
    </div>
</x-app-layout>

</html>