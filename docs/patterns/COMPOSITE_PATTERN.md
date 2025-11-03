# Composite Pattern - Documentation

## Patrón Original

### Definición
El patrón Composite (Compuesto) es un patrón de diseño estructural que permite componer objetos en estructuras de árbol para representar jerarquías parte-todo. Permite tratar objetos individuales y composiciones de objetos de manera uniforme.

### Problema que Resuelve
Cuando necesitas tratar objetos individuales y grupos de objetos de la misma manera. Por ejemplo, cuando quieres ejecutar una operación sobre un objeto individual o sobre un grupo completo de objetos de manera transparente.

### Solución
El patrón Composite sugiere que trabajes con objetos individuales y colecciones de objetos a través de una interfaz común. Crea una jerarquía donde los objetos compuestos pueden contener tanto objetos simples (hojas) como otros compuestos.

### Estructura Original

```
┌─────────────────────────┐
│    Component            │
│  + operation()          │
└──────────┬──────────────┘
           │
     ┌─────┴─────┐
     │           │
┌────▼────┐ ┌───▼──────────┐
│  Leaf   │ │  Composite   │
│         │ │  - children  │
│         │ │  + add()     │
│         │ │  + remove()  │
│         │ │  + operation()│
└─────────┘ └──────────────┘
```

### Componentes Clásicos

1. **Component**: Interfaz común para objetos simples y compuestos
2. **Leaf**: Objeto individual que no tiene hijos
3. **Composite**: Objeto que puede contener otros componentes (hojas o compuestos)

### Ejemplo Clásico

```java
// Component Interface
interface Component {
    void operation();
}

// Leaf
class Leaf implements Component {
    void operation() {
        // Perform operation
    }
}

// Composite
class Composite implements Component {
    private List<Component> children = new ArrayList<>();
    
    void add(Component component) {
        children.add(component);
    }
    
    void operation() {
        for (Component child : children) {
            child.operation();
        }
    }
}
```

## Implementación en el Proyecto

### Estructura Implementada

```
app/Domain/
├── Notifications/
│   ├── NotificationComponentInterface.php (Component Interface)
│   ├── CompositeNotification.php (Composite)
│   ├── EmailNotification.php (Leaf)
│   ├── LogNotification.php (Leaf)
│   └── DatabaseNotification.php (Leaf)
└── Observers/
    └── CompositeNotificationObserver.php (Observer usando Composite)
```

### Clases que Interactúan

#### 1. NotificationComponentInterface (Component)
```php
interface NotificationComponentInterface
{
    public function send(Solicitud $request, string $event, ?string $resolution = null): void;
    public function getType(): string;
    public function getName(): string;
}
```

#### 2. CompositeNotification (Composite)
```php
class CompositeNotification implements NotificationComponentInterface
{
    private array $notifications = [];
    
    public function add(NotificationComponentInterface $notification): void
    {
        $this->notifications[] = $notification;
    }
    
    public function send(Solicitud $request, string $event, ?string $resolution = null): void
    {
        foreach ($this->notifications as $notification) {
            $notification->send($request, $event, $resolution);
        }
    }
}
```

#### 3. EmailNotification (Leaf)
```php
class EmailNotification implements NotificationComponentInterface
{
    public function send(Solicitud $request, string $event, ?string $resolution = null): void
    {
        // Send email notification
        $user = User::find($request->getUserId());
        // Mail::to($user->email)->send(...);
    }
    
    public function getType(): string
    {
        return 'email';
    }
}
```

### Jerarquía de Composición

```
CompositeNotification (Default Notifications)
    │
    ├── EmailNotification (Leaf)
    ├── LogNotification (Leaf)
    └── DatabaseNotification (Leaf)

O puede haber composiciones anidadas:

CompositeNotification (All Notifications)
    │
    ├── CompositeNotification (Critical Notifications)
    │   ├── EmailNotification
    │   └── DatabaseNotification
    └── CompositeNotification (Logging Notifications)
        ├── LogNotification
        └── FileNotification
```

### Integración con Observer Pattern

El Composite se usa dentro de un Observer:

```php
class CompositeNotificationObserver implements RequestObserverInterface
{
    private NotificationComponentInterface $notificationComponent;
    
    public function onRequestApproved(Solicitud $request, ?string $resolution = null): void
    {
        // El composite ejecuta todas las notificaciones hijas
        $this->notificationComponent->send($request, 'approved', $resolution);
    }
}
```

### Uso en RequestObserverService

```php
private function createDefaultCompositeNotification(): CompositeNotification
{
    $composite = new CompositeNotification('Default Notifications');
    
    // Agregar hojas individuales
    $composite->add(new EmailNotification('Request Email Notification'));
    $composite->add(new LogNotification('Request Log Notification'));
    $composite->add(new DatabaseNotification('Request Database Notification'));
    
    return $composite;
}
```

## Dificultades Encontradas

### 1. Integración con Observer Pattern
**Problema**: Necesitábamos integrar el Composite Pattern con el Observer Pattern existente sin romper la funcionalidad actual.

**Solución**: Creamos `CompositeNotificationObserver` que actúa como un adaptador, permitiendo que un objeto Composite se comporte como un Observer.

```php
class CompositeNotificationObserver implements RequestObserverInterface
{
    private NotificationComponentInterface $notificationComponent;
    
    public function onRequestApproved(...): void
    {
        // Delega al composite, que a su vez delega a sus hijos
        $this->notificationComponent->send(...);
    }
}
```

### 2. Manejo de Errores en Composición
**Problema**: Si una notificación falla en el composite, no debería impedir que las demás se ejecuten.

**Solución**: Implementamos manejo de excepciones en el método `send()` del Composite:

```php
public function send(...): void
{
    foreach ($this->notifications as $notification) {
        try {
            $notification->send(...);
        } catch (\Exception $e) {
            error_log("Notification failed in composite: " . $e->getMessage());
        }
    }
}
```

### 3. Composición Anidada
**Problema**: Queríamos permitir composiciones anidadas (composite dentro de composite) para crear jerarquías más complejas.

**Solución**: El Composite implementa la misma interfaz que las hojas, permitiendo que un Composite contenga otros Composites:

```php
$criticalNotifications = new CompositeNotification('Critical');
$criticalNotifications->add(new EmailNotification());
$criticalNotifications->add(new DatabaseNotification());

$allNotifications = new CompositeNotification('All');
$allNotifications->add($criticalNotifications);  // Composite anidado
$allNotifications->add(new LogNotification());   // Leaf directo
```

### 4. Contar Notificaciones Totales
**Problema**: Necesitábamos contar todas las notificaciones incluyendo las anidadas.

**Solución**: Implementamos `getTotalCount()` que recursivamente cuenta todas las notificaciones:

```php
public function getTotalCount(): int
{
    $count = 0;
    foreach ($this->notifications as $notification) {
        if ($notification instanceof CompositeNotification) {
            $count += $notification->getTotalCount();  // Recursión
        } else {
            $count++;
        }
    }
    return $count;
}
```

## Ventajas Obtenidas

1. **Uniformidad**: Se tratan objetos individuales y grupos de la misma manera
2. **Flexibilidad**: Puedes componer notificaciones de manera dinámica
3. **Extensibilidad**: Agregar nuevos tipos de notificación es simple
4. **Composición Anidada**: Permite crear estructuras jerárquicas complejas
5. **Cliente Simplificado**: El cliente no necesita saber si está trabajando con una hoja o un composite

## Uso en el Proyecto

### Casos de Uso

#### 1. Notificaciones por Defecto
```php
$composite = new CompositeNotification('Default');
$composite->add(new EmailNotification());
$composite->add(new LogNotification());
$composite->add(new DatabaseNotification());

$observer = new CompositeNotificationObserver($composite);
$request->attach($observer);
```

#### 2. Notificaciones Críticas Separadas
```php
$criticalComposite = new CompositeNotification('Critical Only');
$criticalComposite->add(new EmailNotification());
$criticalComposite->add(new DatabaseNotification());

$loggingComposite = new CompositeNotification('Logging Only');
$loggingComposite->add(new LogNotification());

// Ambos composites pueden usarse independientemente
```

#### 3. Composición Anidada
```php
$emailGroup = new CompositeNotification('Email Group');
$emailGroup->add(new EmailNotification('Admin Email'));
$emailGroup->add(new EmailNotification('User Email'));

$allNotifications = new CompositeNotification('All');
$allNotifications->add($emailGroup);
$allNotifications->add(new LogNotification());
```

## Comparación con Implementación Clásica

### Similaridades
- Misma estructura básica (Component, Leaf, Composite)
- Mismo principio de transparencia (tratar hojas y compuestos igual)
- Misma capacidad de composición anidada

### Diferencias
- **Propósito**: Usamos Composite para notificaciones, no para estructuras de UI o archivos
- **Integración**: Está integrado con Observer Pattern
- **Métodos Adicionales**: Agregamos `getTotalCount()` para utilidad
- **Dominio Específico**: Cada Leaf tiene lógica específica del dominio (email, log, DB)

## Posibles Mejoras

1. **Orden de Ejecución**: Permitir definir el orden en que se ejecutan las notificaciones
2. **Condiciones**: Permitir que algunas notificaciones solo se ejecuten bajo ciertas condiciones
3. **Prioridades**: Implementar sistema de prioridades para las notificaciones
4. **Filtros**: Permitir filtrar qué notificaciones se ejecutan según el evento
