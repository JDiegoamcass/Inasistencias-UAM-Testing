# Observer Pattern - Documentation

## Patrón Original

### Definición
El patrón Observer (Observador) es un patrón de diseño comportamental que define una dependencia uno-a-muchos entre objetos, de manera que cuando un objeto cambia su estado, todos sus dependientes son notificados y actualizados automáticamente.

### Problema que Resuelve
Cuando un objeto necesita notificar a múltiples objetos sobre cambios en su estado, acoplar directamente el objeto notificador con todos los objetos a notificar crea dependencias rígidas y hace el código difícil de mantener.

### Solución
El patrón Observer define una estructura donde:
- El **Subject** (Sujeto) mantiene una lista de **Observers** (Observadores)
- El Subject notifica automáticamente a todos los Observers cuando ocurre un cambio
- Los Observers pueden suscribirse o desuscribirse dinámicamente

### Estructura Original

```
┌─────────────────────┐
│      Subject        │
│  - observers[]      │
│  + attach(obs)      │
│  + detach(obs)      │
│  + notify()         │
└──────────┬──────────┘
           │
           │ notifies
           │
┌──────────▼──────────┐
│     Observer        │
│  + update()         │
└──────────┬──────────┘
           │
           │ implements
           │
    ┌──────┴──────┬────────────┐
    │             │            │
┌───▼───┐  ┌─────▼─────┐  ┌───▼─────┐
│Obs A  │  │  Obs B    │  │ Obs C   │
└───────┘  └───────────┘  └─────────┘
```

### Componentes Clásicos

1. **Subject/Observable**: Mantiene la lista de observadores y notifica cambios
2. **Observer Interface**: Define la interfaz para los observadores
3. **Concrete Observers**: Implementaciones específicas que reaccionan a las notificaciones

### Ejemplo Clásico

```java
// Subject
class Subject {
    private List<Observer> observers = new ArrayList<>();
    
    void attach(Observer observer) {
        observers.add(observer);
    }
    
    void notifyObservers() {
        for (Observer obs : observers) {
            obs.update();
        }
    }
}

// Observer Interface
interface Observer {
    void update();
}

// Concrete Observer
class ConcreteObserver implements Observer {
    void update() {
        // React to notification
    }
}
```

## Implementación en el Proyecto

### Estructura Implementada

```
app/Domain/
├── Models/
│   └── Solicitud.php (Observable/Subject)
├── Observers/
│   ├── RequestObserverInterface.php (Observer Interface)
│   ├── RequestObservableInterface.php (Observable Interface)
│   ├── EmailNotificationObserver.php (Concrete Observer)
│   ├── RequestLogObserver.php (Concrete Observer)
│   ├── DatabaseAuditObserver.php (Concrete Observer)
│   └── CompositeNotificationObserver.php (Concrete Observer usando Composite)
└── Services/
    └── RequestObserverService.php (Servicio de gestión)
```

### Clases que Interactúan

#### 1. Solicitud (Observable/Subject)
```php
class Solicitud implements RequestObservableInterface
{
    private array $observers = [];
    
    public function attach(RequestObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }
    
    public function aprobar(?string $resolucion = null): void
    {
        // Change state
        $this->state->approve($this, $resolucion);
        
        // Notify observers
        foreach ($this->observers as $observer) {
            $observer->onRequestApproved($this, $resolucion);
        }
    }
}
```

#### 2. RequestObserverInterface (Observer Interface)
```php
interface RequestObserverInterface
{
    public function onRequestApproved(Solicitud $request, ?string $resolution = null): void;
    public function onRequestRejected(Solicitud $request, ?string $resolution = null): void;
    public function onRequestStateChanged(Solicitud $request, string $oldState, string $newState): void;
}
```

#### 3. EmailNotificationObserver (Concrete Observer)
```php
class EmailNotificationObserver implements RequestObserverInterface
{
    public function onRequestApproved(Solicitud $request, ?string $resolution = null): void
    {
        // Send email notification
        $user = User::find($request->getUserId());
        // Mail::to($user->email)->send(...);
    }
}
```

### Flujo de Notificación

```
Estado Cambia (State Pattern)
    │
    ▼
Solicitud.aprobar() / rechazar()
    │
    ▼
notifyStateChanged() + notifyObservers()
    │
    ├──→ EmailNotificationObserver.onRequestApproved()
    ├──→ RequestLogObserver.onRequestApproved()
    └──→ DatabaseAuditObserver.onRequestApproved()
```

### Integración con State Pattern

El Observer se integra perfectamente con el State Pattern:

```php
public function aprobar(string $resolucion = null): void
{
    $oldState = $this->state->getStatus();
    $this->state->approve($this, $resolucion);  // State Pattern
    $newState = $this->state->getStatus();
    
    $this->notifyStateChanged($oldState, $newState, $resolucion);  // Observer Pattern
    foreach ($this->observers as $observer) {
        $observer->onRequestApproved($this, $resolucion);
    }
}
```

## Dificultades Encontradas

### 1. Gestión de Observadores
**Problema**: Cada solicitud necesita tener sus observadores asociados, pero cuando se cargan desde la BD, los observadores no persisten.

**Solución**: Implementamos `RequestObserverService` que automáticamente asocia los observadores por defecto cuando se carga una solicitud desde la base de datos.

```php
// En RequestDataSource
private function toDomain(SolicitudEloquent $eloquent): Solicitud
{
    $solicitud = new Solicitud(...);
    $this->observerService->attachDefaultObservers($solicitud);
    return $solicitud;
}
```

### 2. Manejo de Errores
**Problema**: Si un observador falla, no debería interrumpir el flujo principal ni evitar que otros observadores sean notificados.

**Solución**: Implementamos manejo de excepciones en el método `notifyStateChanged()`:

```php
public function notifyStateChanged(...): void
{
    foreach ($this->observers as $observer) {
        try {
            $observer->onRequestStateChanged(...);
        } catch (\Exception $e) {
            error_log("Observer notification failed: " . $e->getMessage());
        }
    }
}
```

### 3. Integración con Composite Pattern
**Problema**: Queríamos usar el patrón Composite para las notificaciones, pero los observadores existentes no lo soportaban.

**Solución**: Creamos `CompositeNotificationObserver` que envuelve un `CompositeNotification`, permitiendo que un solo observador ejecute múltiples notificaciones estructuradas.

### 4. Persistencia de Observadores
**Problema**: Los observadores son objetos en memoria que no pueden persistirse en la BD.

**Solución**: Los observadores se recrean cada vez que se carga una solicitud desde la BD, usando el servicio de observadores para asociar los por defecto.

## Ventajas Obtenidas

1. **Desacoplamiento**: Las solicitudes no conocen los detalles de cómo se notifican los cambios
2. **Extensibilidad**: Agregar nuevos observadores es simple y no requiere modificar código existente
3. **Flexibilidad**: Los observadores pueden agregarse o removerse dinámicamente
4. **Separación de Responsabilidades**: Cada observador tiene una función específica
5. **Testabilidad**: Los observadores pueden probarse independientemente usando mocks

## Uso en el Proyecto

### Observadores por Defecto

1. **EmailNotificationObserver**: Envía emails cuando cambia el estado
2. **RequestLogObserver**: Registra eventos en los logs
3. **DatabaseAuditObserver**: Mantiene un registro de auditoría en BD

### Ejemplo de Uso

```php
// Los observadores se asocian automáticamente
$request = $requestRepository->findById(1);

// Al aprobar, todos los observadores son notificados
$request->aprobar('Approved by admin');
// → Email sent
// → Log written  
// → Audit record created

// Agregar observador personalizado
$customObserver = new MyCustomObserver();
$request->attach($customObserver);
```

## Posibles Mejoras

1. **Observer Priorities**: Permitir ordenar los observadores por prioridad
2. **Selective Notifications**: Permitir que los observadores se suscriban solo a eventos específicos
3. **Async Notifications**: Usar colas para notificaciones asíncronas
4. **Observer Lifecycle**: Permitir que los observadores se inicialicen y limpien adecuadamente
