# API REST Todo List - Laravel

## Informations Étudiant
* **Nom :** Bouhaya Wael
* **Établissement :** ENSAM Casablanca - Université Hassan II
* **Filière :** Département Génie Informatique et IA
* **Enseignant :** Dr. WARDI Ahmed

---

## Présentation du Projet
Ce projet consiste en la création d'une API REST complète pour la gestion de tâches (Todo List).  
L'objectif est de mettre en pratique :

- La gestion des ressources avec **Eloquent ORM**
- La validation rigoureuse des requêtes
- Le respect des standards REST
- L’utilisation correcte des codes de statut HTTP
- Une structure de réponse JSON normalisée

---

## Technologies Utilisées

- **Laravel**
- **PHP**
- **MySQL**
- **Postman** (tests API)

---

## Aperçu du Code Source

### 1️. Modèle & Migration (`Todo`)

La structure de la table respecte les contraintes du sujet (énumérations, valeurs par défaut et types de données).

```php
// database/migrations/xxxx_create_todos_table.php
public function up(): void {
    Schema::create('todos', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->boolean('completed')->default(false);
        $table->enum('priority', ['low', 'medium', 'high'])->default('low');
        $table->timestamps();
    });
}
```

```php
// app/Models/Todo.php
class Todo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'completed',
        'priority'
    ];

    protected $casts = [
        'completed' => 'boolean'
    ];
}
```

---

### 2️. Routes API (`api.php`)

Utilisation de `apiResource` pour respecter les standards REST et d'une route personnalisée pour l'action spécifique de complétion.

```php
use App\Http\Controllers\TodoController;

Route::apiResource('todos', TodoController::class);
Route::patch('/todos/{id}/complete', [TodoController::class, 'complete']);
```

---

### 3️. Contrôleur (`TodoController.php`)

Extraits montrant la validation des données et la gestion des réponses normalisées.

#### ➤ Création d'une tâche (POST)

```php
public function store(Request $request) {
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'sometimes|in:low,medium,high'
    ]);

    $todo = Todo::create($validated);

    return response()->json([
        'data' => $todo,
        'message' => 'Tâche créée avec succès'
    ], 201);
}
```

#### ➤ Suppression d'une tâche (DELETE)

```php
public function destroy(string $id) {
    $todo = Todo::find($id);

    if (!$todo) {
        return response()->json([
            'message' => 'Tâche non trouvée'
        ], 404);
    }

    $todo->delete();

    return response()->json(null, 204);
}
```

#### ➤ Marquer une tâche comme complétée (PATCH)

```php
public function complete(string $id) {
    $todo = Todo::find($id);

    if (!$todo) {
        return response()->json([
            'message' => 'Tâche non trouvée'
        ], 404);
    }

    $todo->update(['completed' => true]);

    return response()->json([
        'data' => $todo,
        'message' => 'Tâche marquée comme complétée'
    ], 200);
}
```

---

## Tests Postman (Endpoints)

### Cas de Succès

| Méthode | URI | Action | Status | Capture d'écran |
|----------|------|---------|----------|------------------|
| **GET** | `/api/todos` | Lister toutes les tâches | `200 OK` | `01_index_success.png` |
| **POST** | `/api/todos` | Créer une tâche | `201 Created` | `02_store_success.png` |
| **GET** | `/api/todos/{id}` | Afficher une tâche | `200 OK` | `03_show_success.png` |
| **PUT** | `/api/todos/{id}` | Modifier une tâche | `200 OK` | `04_update_success.png` |
| **PATCH** | `/api/todos/{id}/complete` | Marquer comme complétée | `200 OK` | `05_complete_success.png` |
| **DELETE** | `/api/todos/{id}` | Supprimer une tâche | `204 No Content` | `06_delete_success.png` |

---

### Gestion des Erreurs (404 Not Found)

L'API gère correctement les ressources inexistantes pour chaque méthode :

- **GET (show)** → `E01_show_notfound_404.png`
- **PUT (update)** → `E02_update_notfound_404.png`
- **PATCH (complete)** → `E03_complete_notfound_404.png`
- **DELETE (destroy)** → `E04_delete_notfound_404.png`

---

## 📂 Structure des Livrables

```
app/
 ├── Models/
 │   └── Todo.php
 ├── Http/
 │   └── Controllers/
 │       └── TodoController.php

database/
 └── migrations/

routes/
 └── api.php

screenshots/
 ├── 01_index_success.png
 ├── 02_store_success.png
 ├── ...
 └── E04_delete_notfound_404.png
```

---

## 🚀 Lancement du Projet

```bash
git clone <repository_url>
cd todo-list
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

L’API sera accessible via :

```
http://127.0.0.1:8000/api/todos
```
