# Arquitectura y Flujo del Sistema - UAM Absence Management

## Visión General

Este documento describe la arquitectura limpia implementada en el sistema de gestión de inasistencias UAM, incluyendo el flujo completo de datos desde la petición HTTP hasta la base de datos y las notificaciones resultantes.

## Arquitectura de Capas

```
┌─────────────────────────────────────────────────────────┐
│                    Presentation Layer                    │
│  Routes, Views                                           │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                   Application Layer                      │
│  (Controllers, Use Cases - Business Logic Orchestration) │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                     Domain Layer                         │
│  (Models, States, Observers, Notifications)              │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                Infrastructure Layer                      │
│  (DataSources, Repository Implementations)               │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                  Persistence Layer                       │
│  (Eloquent Models, Database)                             │
└─────────────────────────────────────────────────────────┘
```

## Flujo Completo de una Solicitud

### Escenario: Aprobar una Solicitud de Inasistencia

```
1. HTTP Request
   │
   POST /solicitudes/123
   Body: { estado: "aprobado", resolucion: "Approved by admin" }
   │
   ▼
2. Route Handler (web.php / web_secretaria_routes.php)
   │
   Route::put('/solicitudes/{solicitud}', [SolicitudController::class, 'update'])
   │
   ▼
3. SolicitudController::update()
   │
   ├─ Validación HTTP (Request::validate)
   ├─ Llamada a UseCase
   │
   ▼
4. UpdateRequestStatusUseCase::execute()
   │
   ├─ Buscar solicitud en repositorio
   │  └─ RequestRepositoryInterface::findById()
   │
   ├─ Validar transición permitida
   │  └─ Solicitud::canApprove()
   │
   ├─ Ejecutar cambio de estado
   │  └─ Solicitud::aprobar()
   │
   ▼
5. Solicitud::aprobar()
   │
   ├─ Obtener estado actual
   │  └─ $oldState = $this->state->getStatus()
   │
   ├─ Delegar al State Pattern
   │  └─ $this->state->approve($this, $resolucion)
   │
   ▼
6. PendingState::approve()
   │
   ├─ Transicionar a nuevo estado
   │  └─ $request->transitionTo(new ApprovedState())
   │
   ├─ Establecer resolución
   │  └─ $request->setResolucion($resolucion)
   │
   ▼
7. Solicitud::transitionTo()
   │
   ├─ Cambiar estado interno
   │  └─ $this->state = $newState
   │
   ├─ Notificar cambio de estado (Observer Pattern)
   │  └─ $this->notifyStateChanged($oldState, $newState)
   │
   ▼
8. Solicitud::notifyStateChanged()
   │
   ├─ Iterar sobre observadores
   │  └─ foreach ($this->observers as $observer)
   │
   ├─ Notificar cambio genérico
   │  └─ $observer->onRequestStateChanged(...)
   │
   ├─ Notificar aprobación específica
   │  └─ $observer->onRequestApproved(...)
   │
   ▼
9. CompositeNotificationObserver::onRequestApproved()
   │
   ├─ Delegar al Composite
   │  └─ $this->notificationComponent->send(...)
   │
   ▼
10. CompositeNotification::send()
    │
    ├─ Iterar sobre notificaciones (Composite Pattern)
    │  └─ foreach ($this->notifications as $notification)
    │
    ├─ Ejecutar cada notificación
    │  ├─ EmailNotification::send() → Envía email
    │  ├─ LogNotification::send() → Escribe log
    │  └─ DatabaseNotification::send() → Guarda auditoría
    │
    ▼
11. Vuelta a UpdateRequestStatusUseCase
    │
    ├─ Persistir cambios
    │  └─ RequestRepositoryInterface::update($request)
    │
    ▼
12. RequestDataSource::update()
    │
    ├─ Convertir dominio a Eloquent
    │  └─ $this->toEloquent($solicitud)
    │
    ├─ Actualizar en BD
    │  └─ $eloquent->update(...)
    │
    ▼
13. Base de Datos
    │
    └─ UPDATE solicituds SET estado = 'aprobado', resolucion = '...' WHERE id = 123
    │
    ▼
14. Response HTTP
    │
    └─ Redirect con mensaje de éxito
```

## Diagrama de Secuencia Detallado

```
Controller           UseCase            Solicitud         State            Observer         Composite      Database
   │                   │                  │                │                 │                 │              │
   │──update()────────▶│                  │                │                 │                 │              │
   │                   │──findById()─────▶│                │                 │                 │              │
   │                   │                  │                │                 │                 │              │
   │                   │                  │──aprobar()────▶│                 │                 │              │
   │                   │                  │                │──approve()─────▶│                 │              │
   │                   │                  │                │                 │                 │              │
   │                   │                  │◀transitionTo()─│                 │                 │              │
   │                   │                  │                │                 │                 │              │
   │                   │                  │──notify()─────▶│                 │                 │              │
   │                   │                  │                │                 │──onApproved()──▶│              │
   │                   │                  │                │                 │                 │──send()─────▶│
   │                   │                  │                │                 │                 │              │
   │                   │                  │                │                 │                 │◀─Email───────│
   │                   │                  │                │                 │                 │◀─Log─────────│
   │                   │                  │                │                 │                 │◀─DB──────────│
   │                   │                  │                │                 │                 │              │
   │                   │──update()───────▶│                │                 │                 │              │
   │                   │                  │                │                 │                 │              │
   │                   │                  │                │                 │                 │              │
   │◀──success─────────│                  │                │                 │                 │              │
   │                   │                  │                │                 │                 │              │
```

## Patrones de Diseño Implementados

### 1. Clean Architecture
**Ubicación**: Estructura completa del proyecto

**Componentes**:
- **Domain**: Lógica de negocio pura, sin dependencias
- **Application**: Orquestación de casos de uso
- **Infrastructure**: Implementaciones técnicas
- **Presentation**: Interfaz HTTP

**Flujo**:
```
Presentation → Application → Domain ← Infrastructure
```

### 2. State Pattern
**Ubicación**: `Domain/States/`

**Implementación**:
- `RequestStateInterface`: Interfaz común
- `PendingState`, `ApprovedState`, `RejectedState`: Estados concretos
- `Solicitud`: Context que delega al estado

**Flujo**:
```
Solicitud.aprobar() → State.approve() → State.transitionTo()
```

### 3. Observer Pattern
**Ubicación**: `Domain/Observers/`

**Implementación**:
- `RequestObserverInterface`: Interfaz del observador
- `RequestObservableInterface`: Interfaz del observable
- `Solicitud`: Implementa Observable
- Observadores concretos: `EmailNotificationObserver`, etc.

**Flujo**:
```
State Change → notifyObservers() → Observer.onRequestApproved()
```

### 4. Composite Pattern
**Ubicación**: `Domain/Notifications/`

**Implementación**:
- `NotificationComponentInterface`: Component común
- `CompositeNotification`: Composite
- `EmailNotification`, `LogNotification`, `DatabaseNotification`: Leaves

**Flujo**:
```
CompositeNotification.send() → [Email, Log, DB].send()
```

### 5. Repository Pattern
**Ubicación**: `Domain/Repositories/` y `Infrastructure/DataSources/`

**Implementación**:
- Interfaces en Domain
- Implementaciones en Infrastructure
- Aislamiento de lógica de acceso a datos

**Flujo**:
```
UseCase → RepositoryInterface → DataSource → Eloquent → DB
```

## Flujos Específicos por Operación

### Crear una Solicitud

```
1. Controller::store()
2. CreateRequestUseCase::execute()
3. RequestRepository::save()
4. RequestDataSource::save()
   └─ Asocia observadores automáticamente
5. Solicitud (nuevo) → Estado: PendingState
6. Persiste en BD
7. Retorna Solicitud con observadores asociados
```

### Consultar Solicitudes Pendientes

```
1. Controller::index()
2. GetPendingRequestsUseCase::execute()
3. RequestRepository::findPendientes()
4. RequestDataSource::findPendientes()
   └─ Convierte Eloquent → Domain
   └─ Asocia observadores a cada solicitud
5. Retorna array de Solicitud
6. Vista renderiza con datos
```

### Actualizar Estado de Solicitud

```
1. Controller::update()
2. UpdateRequestStatusUseCase::execute()
3. Solicitud::aprobar() / rechazar()
   ├─ State Pattern: Transición de estado
   ├─ Observer Pattern: Notificación a observadores
   └─ Composite Pattern: Ejecución de notificaciones
4. RequestRepository::update()
5. Persiste cambio en BD
6. Response HTTP
```

## Gestión de Observadores

### Carga desde Base de Datos

```php
// RequestDataSource::toDomain()
$solicitud = new Solicitud(...);  // Estado se crea desde string
$this->observerService->attachDefaultObservers($solicitud);
return $solicitud;  // Ahora tiene observadores asociados
```

### Observadores por Defecto

1. **CompositeNotificationObserver**: Usa Composite Pattern
   - EmailNotification
   - LogNotification
   - DatabaseNotification

2. **RequestLogObserver**: Logging adicional (legacy)

### Agregar Observadores Personalizados

```php
$customObserver = new MyCustomObserver();
$request->attach($customObserver);
// Ahora también recibirá notificaciones
```

## Conversión de Datos

### Domain → Infrastructure (toEloquent)

```php
private function toEloquent(Solicitud $solicitud): array
{
    return [
        'estado' => $solicitud->getEstado(),  // String desde State
        // ... otros campos
    ];
}
```

### Infrastructure → Domain (toDomain)

```php
private function toDomain(SolicitudEloquent $eloquent): Solicitud
{
    $solicitud = new Solicitud(
        estado: $eloquent->estado,  // String se convierte a State
        // ...
    );
    $this->observerService->attachDefaultObservers($solicitud);
    return $solicitud;
}
```

## Inyección de Dependencias

### Service Provider Registration

```php
// AppServiceProvider::register()

// Repositories
$this->app->bind(RequestRepositoryInterface::class, RequestDataSource::class);

// Use Cases
$this->app->bind(UpdateRequestStatusUseCase::class, function ($app) {
    return new UpdateRequestStatusUseCase(
        $app->make(RequestRepositoryInterface::class)
    );
});

// Services
$this->app->singleton(RequestObserverService::class);
```

### Resolución Automática

Laravel resuelve automáticamente las dependencias en constructores:

```php
class RequestDataSource
{
    public function __construct(
        private RequestObserverService $observerService  // Auto-resolved
    ) {}
}
```

## Manejo de Errores

### En Observadores

```php
foreach ($this->observers as $observer) {
    try {
        $observer->onRequestApproved(...);
    } catch (\Exception $e) {
        error_log(...);  // No rompe el flujo principal
    }
}
```

### En Composites

```php
foreach ($this->notifications as $notification) {
    try {
        $notification->send(...);
    } catch (\Exception $e) {
        error_log(...);  // Continúa con otras notificaciones
    }
}
```

## Testing Strategy

### Unidad
- **Domain Models**: Testear lógica de negocio pura
- **States**: Testear transiciones y validaciones
- **Observers**: Testear con mocks de Solicitud

### Integración
- **Use Cases**: Testear con repositorios mock
- **DataSources**: Testear conversión Domain ↔ Eloquent

### End-to-End
- **Controllers**: Testear flujo completo HTTP

## Ventajas de esta Arquitectura

1. **Testabilidad**: Cada capa puede probarse independientemente
2. **Mantenibilidad**: Cambios aislados por capa
3. **Escalabilidad**: Fácil agregar nuevas funcionalidades
4. **Flexibilidad**: Patrones permiten extensión sin modificación
5. **Claridad**: Separación clara de responsabilidades
6. **Reutilización**: Use Cases pueden usarse desde diferentes puntos de entrada

## Puntos de Extensión

1. **Nuevos Estados**: Agregar clase en `Domain/States/`
2. **Nuevos Observadores**: Implementar `RequestObserverInterface`
3. **Nuevas Notificaciones**: Implementar `NotificationComponentInterface`
4. **Nuevos Use Cases**: Crear en `Domain/UseCases/`
5. **Nuevas Fuentes de Datos**: Implementar interfaces de repositorio
