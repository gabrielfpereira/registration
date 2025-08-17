<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
   
</head>
<body >
    <div class="flex justify-center items-center w-full  p-4" id="conteudo">
        <div class="border-1 border-gray-300 px-4 py-2 min-h-24 max-h-24">
            <img src="{{ asset('assets/logo-prefeitura.jpg') }}" alt="Logo-prefeitura" class="w-20 h-20 mx-auto mb-2">
        </div>
        <div class="border-1 border-gray-300 px-4 py-2 text-center min-h-24">
            <p>MUNICÍPIO DE PALMAS</p>
            <p>SECRETARIA DA EDUCAÇÃO DE PALMAS</p>
            <p>ETI ALMIRANTE TAMANDARÉ</p>
        </div>
        <div class="border-1 border-gray-300 p-2 min-h-24 max-h-24 overflow-hidden">
            <img src="{{ asset('assets/logo-eti.jpg') }}" alt="Logo-eti" class="w-20 h-20 mx-auto mb-2">
        </div>
    </div>

    <div class="container mx-auto p-4">
        <p>Nº {{ $registration->id }}/{{ now()->year }}</p>
        <h1 class="text-2xl font-bold mb-4 text-center">Registro de {{ match ($registration->type) {
            'medida' => 'Medida Disciplinar',
            'infracao' => 'Infração Disciplinar',
            'suspensao' => 'Suspensão',
        } }}</h1>

        <p>Sr. Pai/Responsável,</p>
        <p>Informamos a vossa senhoria que o(a) aluno(a) supracitado, obteve no dia <strong>{{ $registration->created_at->format('d/m/Y') }}</strong> o seguinte registro de ocorrência
        descumpindo ao Regimento Escolar da ETI Almirante Tamandaré.</p>

        <div class="border-1 border-gray-300 p-4 mb-4 mt-4">
            <p><strong>Nome do Aluno(a):</strong> {{ $registration->student_name }}</p>
            <div class="flex justify-between items-center">
                <p><strong>Turma:</strong> {{ $registration->class_number }}</p>
                <p><strong>Tipo de Registro:</strong> {{ match ($registration->type) {
            'medida' => 'Medida Disciplinar',
            'infracao' => 'Infração Disciplinar',
            'suspensao' => 'Suspensão',
        } }}</p>
                <p><strong>Status:</strong> {{ $registration->status }}</p>
            </div>
            <p><strong>Data da Ocorrência:</strong> {{ $registration->created_at->format('d/m/Y') }}</p>
            @if ($registration->type == 'suspensao')
                <p><strong>Dias de Suspensão:</strong> {{ \Carbon\Carbon::parse($registration->registration_date_start)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($registration->registration_date_end)->format('d/m/Y') }}</p>
                <p>O aluno(a) deverá retornar às aulas no dia {{ \Carbon\Carbon::parse($registration->registration_date_end)->addDay()->format('d/m/Y') }}.</p>
            @endif
            <p><strong>Descumprimentos do Regimento Escolar:</strong></p>
            <ul class="list-disc pl-5">
                @foreach($registration->items as $item)
                    <li>{{ $item->description }}</li>
                @endforeach
            </ul>
        </div>

        @if ($registration->observation)
            <div class="border-1 border-gray-300 p-4 mb-2 mt-4">
            <p><strong>Observações:</strong></p>
            <p>{{ $registration->observation }}</p>
        </div>
        @endif

        <div class="border-1 border-gray-300 p-4 mb-4 mt-2">
            <p><strong>Campo para resposta:</strong></p>
            <p>_____________________________________________________________________________________
                ____________________________________________________________________________________
                ____________________________________________________________________________________
            </p>
        </div>

        <div class="{{ $registration->type == 'suspensao' ? 'grid grid-cols-3 gap-4' : 'flex justify-between' }} mt-8">
            @foreach ($registration->signatures as $signature)
                <div class="text-center mr-4">
                    <p>___________________________</p>
                    <p>{{ $signature->name }}</p>
                    <p>{{ $signature->role }}</p>
                </div>
            @endforeach
             <div class="text-center">
                <p>___________________________</p>
                <p>{{ $registration->user->name }}</p>
                <p>{{ $registration->user->type == 'instructor' ? 'Instrutor Disciplinar' : 'Supervisor' }}</p>
            </div>

            <div class="text-center">
                <p>___________________________</p>
                <p>Responsável</p>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.print();
        });
        
    </script>
</body>
</html>
