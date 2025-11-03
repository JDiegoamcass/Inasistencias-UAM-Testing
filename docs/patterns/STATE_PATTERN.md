# State Pattern - Documentation

## Patrón Original

### Definición
El patrón State (Estado) es un patrón de diseño comportamental que permite a un objeto alterar su comportamiento cuando su estado interno cambia. El objeto aparentará haber cambiado de clase.

### Problema que Resuelve
Cuando un objeto puede estar en diferentes estados y su comportamiento cambia según el estado actual, usar múltiples condicionales (`if/else` o `switch`) para manejar estos comportamientos hace que el código sea difícil de mantener y extender.

### Solución
El patrón State sugiere crear clases separadas para cada estado posible del objeto y extraer todos los comportamientos específicos del estado a estas clases. El objeto original, llamado contexto, almacena una referencia a uno de estos objetos de estado y delega todo el trabajo relacionado con el estado a ese objeto.

### Estructura Original

```
┌─────────────────────┐
│    Context          │
│  - state: State     │
│  + request()        │
└──────────┬──────────┘
           │
           │ uses
           │
┌──────────▼──────────┐
│      State          │
│  + handle(context)  │
└──────────┬──────────┘
           │
           │ implements
           │
    ┌──────┴──────┬────────────┐
    │             │            │
┌───▼───┐  ┌─────▼─────┐  ┌───▼─────┐
│StateA │  │  StateB   │  │ StateC  │
└───────┘  └───────────┘  └─────────┘
```

### Componentes Clásicos

1. **Context**: Mantiene una referencia al estado actual y delega las operaciones al objeto de estado.
2. **State Interface**: Define la interfaz común para todos los estados concretos.
3. **Concrete States**: Implementaciones específicas de cada estado posible.

### Ejemplo Clásico

```java
// Context
class Context {
    private State state;
    
    void setState(State state) {
        this.state = state;
    }
    
    void request() {
        state.handle(this);
    }
}

// State Interface
interface State {
    void handle(Context context);
}

// Concrete States
class ConcreteStateA implements State {
    void handle(Context context) {
        // Behavior for state A
        context.setState(new ConcreteStateB());
    }
}

class ConcreteStateB implements State {
    void handle(Context context) {
        // Behavior for state B
    }
}
```

## Implementación en el Proyecto

### Estructura Implementada

```
app/Domain/
├── Models/
│   └── Solicitud.php (Context)
├── States/
│   ├── RequestStateInterface.php (State Interface)
│   ├── PendingState.php (Concrete State)
│   ├── ApprovedState.php (Concrete State)
│   └── RejectedState.php (Concrete State)
```

### Clases que Interactúan

#### 1. Solicitud (Context)
```php
class Solicitud implements RequestObservableInterface
{
    private RequestStateInterface $state;
    
    public function aprobar(?string $resolucion = null): void
    {
        $this->state->approve($this, $resolucion);
    }
    
    public function transitionTo(RequestStateInterface $newState): void
    {
        $this->state = $newState;
    }
}
```

#### 2. RequestStateInterface (State Interface)
```php
interface RequestStateInterface
{
    public function getStatus(): string;
    public function approve(Solicitud $request, ?string $resolution = null): void;
    public function reject(Solicitud $request, ?string $resolution = null): void;
    public function canApprove(): bool;
    public function canReject(): bool;
    // ... otros métodos
}
```

#### 3. PendingState (Concrete State)
```php
class PendingState implements RequestStateInterface
{
    public function approve(Solicitud $request, ?string $resolution = null): void
    {
        $request->transitionTo(new ApprovedState());
        if ($resolution) {
            $request->setResolucion($resolution);
        }
    }
    
    public function canApprove(): bool
    {
        return true;
    }
}
```

### Flujo de Transiciones

```
PendingState (Estado Inicial)
    │
    ├── approve() → ApprovedState (Estado Final)
    │
    └── reject() → RejectedState (Estado Final)

ApprovedState / RejectedState → No permiten transiciones
```

### Características Especiales de la Implementación

1. **Validación de Transiciones**: Cada estado valida si puede realizar una transición antes de ejecutarla.
2. **Integración con Observer**: Las transiciones notifican automáticamente a los observadores.
3. **Persistencia**: Los estados se convierten a/desde strings para almacenamiento en BD.

## Dificultades Encontradas

### 1. Conversión Estado ↔ String
**Problema**: Los estados se almacenan como strings en la base de datos, pero se manejan como objetos en el dominio.

**Solución**: Implementamos el método `createStateFromString()` en la clase `Solicitud` que reconstruye el estado correcto desde su representación string.

```php
private function createStateFromString(?string $estado): RequestStateInterface
{
    return match($estado) {
        'aprobado' => new ApprovedState(),
        'rechazado' => new RejectedState(),
        default => new PendingState(),
    };
}
```

### 2. Notificación de Observadores
**Problema**: Necesitábamos notificar a los observadores cuando cambia el estado, pero el patrón State no incluye esta funcionalidad.

**Solución**: Integramos el patrón State con el patrón Observer. Cuando se realiza una transición, se notifica automáticamente a todos los observadores registrados.

```php
public function aprobar(string $resolucion = null): void
{
    $oldState = $this->state->getStatus();
    $this->state->approve($this, $resolucion);
    $newState = $this->state->getStatus();
    
    $this->notifyStateChanged($oldState, $newState, $resolucion);
}
```

### 3. Compatibilidad con Vistas
**Problema**: Las vistas Blade necesitan acceder al estado como string, pero internamente usamos objetos.

**Solución**: Implementamos `getEstado()` que retorna `null` para estado pendiente y el string del estado para otros casos, manteniendo compatibilidad con el código existente.

```php
public function getEstado(): ?string
{
    $status = $this->state->getStatus();
    return $status === 'pendiente' ? null : $status;
}
```

## Ventajas Obtenidas

1. **Mantenibilidad**: Cada estado encapsula su propia lógica
2. **Extensibilidad**: Agregar nuevos estados es simple (ej: `UnderReviewState`)
3. **Validación**: Las transiciones inválidas se previenen a nivel de código
4. **Testabilidad**: Cada estado puede probarse independientemente
5. **Claridad**: El código es más legible y expresivo

## Posibles Mejoras

1. **Máquina de Estados**: Implementar una clase que defina explícitamente todas las transiciones permitidas
2. **Estados Temporales**: Agregar estados como "En Revisión" o "Solicitando Aclaración"
3. **Historial de Estados**: Mantener un registro de todas las transiciones realizadas
