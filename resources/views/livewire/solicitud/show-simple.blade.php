<div class="p-6">
    <h1>Debug: Solicitud Show</h1>
    
    @if($solicitud)
        <div class="mb-4">
            <strong>Número:</strong> {{ $solicitud->numero_solicitud }}
        </div>
        <div class="mb-4">
            <strong>Estado:</strong> {{ $solicitud->estado_solicitud }}
        </div>
        <div class="mb-4">
            <strong>Solicitante:</strong> {{ $solicitud->solicitante?->name ?? 'No disponible' }}
        </div>
        <div class="mb-4">
            <strong>Fecha:</strong> {{ $solicitud->created_at->format('d/m/Y H:i') }}
        </div>
        
        <a href="{{ route('solicitudes.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            Volver al listado
        </a>
    @else
        <p>❌ No se pudo cargar la solicitud</p>
    @endif
</div>