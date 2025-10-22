# 📋 Modelo FuenteOrganismoFinanciero - Documentación Técnica

## 🎯 Descripción General
El modelo `FuenteOrganismoFinanciero` representa las fuentes de financiamiento disponibles para las solicitudes de combustible, incluyendo información sobre organismos financiadores, tipos de fuente, contrapartidas y vigencias.

## 📊 Estructura de Campos

### 🔑 Campos Principales
| Campo | Tipo | Descripción | Requerido |
|-------|------|-------------|-----------|
| `id_fuente_org_fin` | BIGINT UNSIGNED | Clave primaria | ✅ |
| `codigo` | VARCHAR(30) | Código único de la fuente | ✅ |
| `descripcion` | VARCHAR(200) | Descripción detallada | ✅ |
| `tipo_fuente` | ENUM | Tipo de fuente de financiamiento | ✅ |

### 🏦 Información Organizacional
| Campo | Tipo | Descripción | Valor por Defecto |
|-------|------|-------------|-------------------|
| `organismo_financiador` | VARCHAR(100) | Nombre del organismo | NULL |
| `requiere_contrapartida` | BOOLEAN | Si requiere contrapartida | FALSE |
| `porcentaje_contrapartida` | DECIMAL(5,2) | Porcentaje de contrapartida | 0 |

### 📅 Control de Vigencia
| Campo | Tipo | Descripción | Valor por Defecto |
|-------|------|-------------|-------------------|
| `activo` | BOOLEAN | Estado de la fuente | TRUE |
| `fecha_vigencia_inicio` | DATE | Fecha de inicio de vigencia | NULL |
| `fecha_vigencia_fin` | DATE | Fecha de fin de vigencia | NULL |

## 🏷️ Tipos de Fuente Disponibles

### 📋 Enumeración tipo_fuente
```php
- Nacional          // Fuentes del gobierno nacional
- Departamental     // Fuentes del gobierno departamental  
- Municipal         // Fuentes del gobierno municipal
- Internacional     // Fuentes de organismos internacionales
- Otros            // Otras fuentes no clasificadas
```

## 🔧 Funcionalidades Principales

### 📍 Scopes (Consultas Predefinidas)
```php
// Obtener solo fuentes activas
FuenteOrganismoFinanciero::activas()->get();

// Filtrar por tipo específico
FuenteOrganismoFinanciero::porTipo('Nacional')->get();

// Obtener fuentes vigentes (considerando fechas)
FuenteOrganismoFinanciero::vigentes()->get();
```

### 🔗 Relaciones con Otros Modelos
```php
// Solicitudes de combustible que usan esta fuente
$fuente->solicitudesCombustible;

// Presupuestos asignados a esta fuente
$fuente->presupuestos;
```

### ✅ Métodos de Validación
```php
// Verificar si está vigente en una fecha específica
$fuente->estaVigente('2024-12-31');

// Verificar si requiere contrapartida
$fuente->requiereContrapartida();

// Calcular monto de contrapartida
$contrapartida = $fuente->calcularContrapartida(100000);
```

## 🎨 Atributos Computados

### 📊 Información de Estado
```php
// Estado de vigencia como texto
$fuente->estado_vigencia; // 'Vigente', 'Vencida', 'Por iniciar', 'Inactiva'

// Nombre completo (código + descripción)
$fuente->nombre_completo; // "001 - Tesoro General de la Nación"
```

### 📋 Información Resumida
```php
$resumen = $fuente->getInformacionResumida();
/*
[
    'codigo' => '001',
    'descripcion' => 'Tesoro General de la Nación',
    'tipo_fuente' => 'Nacional',
    'organismo_financiador' => 'Ministerio de Economía',
    'estado_vigencia' => 'Vigente',
    'requiere_contrapartida' => false,
    'porcentaje_contrapartida' => 0,
    'activo' => true
]
*/
```

## 🛡️ Validaciones Automáticas

### ✅ Al Crear/Actualizar
- **Código obligatorio**: No puede estar vacío
- **Porcentaje válido**: Entre 0% y 100%
- **Fechas consistentes**: Inicio no puede ser mayor que fin

### 🚨 Excepciones Lanzadas
```php
InvalidArgumentException: 'El código de la fuente es obligatorio'
InvalidArgumentException: 'El porcentaje de contrapartida debe estar entre 0 y 100'
InvalidArgumentException: 'La fecha de inicio no puede ser mayor a la fecha de fin'
```

## 💡 Casos de Uso Comunes

### 1. Crear Nueva Fuente
```php
$fuente = FuenteOrganismoFinanciero::create([
    'codigo' => 'TGN-001',
    'descripcion' => 'Tesoro General de la Nación',
    'tipo_fuente' => 'Nacional',
    'organismo_financiador' => 'Ministerio de Economía',
    'requiere_contrapartida' => false,
    'activo' => true
]);
```

### 2. Consultar Fuentes Vigentes
```php
$fuentesVigentes = FuenteOrganismoFinanciero::vigentes()
    ->orderBy('codigo')
    ->get();
```

### 3. Validar Disponibilidad
```php
$fuente = FuenteOrganismoFinanciero::find(1);

if ($fuente->estaVigente() && $fuente->activo) {
    // La fuente está disponible para uso
    if ($fuente->requiereContrapartida()) {
        $contrapartida = $fuente->calcularContrapartida($montoSolicitud);
        // Procesar contrapartida...
    }
}
```

### 4. Filtrar por Tipo y Estado
```php
$fuentesNacionales = FuenteOrganismoFinanciero::activas()
    ->porTipo('Nacional')
    ->vigentes()
    ->select('id_fuente_org_fin', 'codigo', 'descripcion')
    ->get();
```

## 🔄 Integración con el Sistema

### 📋 En Formularios de Solicitud
El modelo se utiliza en el formulario de solicitud de combustible para:
- Mostrar fuentes disponibles en dropdown
- Validar vigencia antes de crear solicitud
- Calcular contrapartidas automáticamente
- Mostrar información detallada al seleccionar

### 💰 En Control Presupuestario
- Vinculación con presupuestos asignados
- Seguimiento de gastos por fuente
- Alertas de contrapartida requerida
- Reportes de uso por organismo financiador

### 📊 En Reportes y Análisis
- Clasificación de gastos por tipo de fuente
- Análisis de dependencia por organismo
- Seguimiento de vigencias y renovaciones
- Métricas de contrapartida aplicada

## 🚀 Optimizaciones Implementadas

### 📈 Performance
- Índices automáticos en `codigo` (UNIQUE)
- Scopes optimizados para consultas frecuentes
- Carga selectiva de campos en listados

### 🛡️ Seguridad
- Validaciones estrictas en eventos del modelo
- Sanitización automática de datos
- Prevención de estados inconsistentes

### 🎯 Usabilidad
- Atributos computados para información común
- Métodos helper para operaciones frecuentes
- Información resumida estructurada

## 📋 Checklist de Implementación

### ✅ Completado
- [x] Estructura de campos según diagrama
- [x] Tipos de fuente como constantes
- [x] Scopes para consultas frecuentes
- [x] Relaciones con otros modelos
- [x] Validaciones automáticas
- [x] Métodos de utilidad
- [x] Atributos computados
- [x] Documentación completa

### 🔄 Pendiente (Futuras Mejoras)
- [ ] Migración de base de datos
- [ ] Seeders con datos de ejemplo
- [ ] Tests unitarios
- [ ] Factory para testing
- [ ] Eventos personalizados
- [ ] Cache de consultas frecuentes