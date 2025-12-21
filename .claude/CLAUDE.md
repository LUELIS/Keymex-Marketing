# Instructions Marketing Keymex

Application de suivi marketing KEYMEX : gestion des commandes de supports print, workflow de validation BAT, suivi des biens sous compromis/vendus et statistiques

**MCP** : `keymex-hub`

---

## AUTOMATISMES MCP - Actions executees automatiquement par Claude

### Au DEBUT de chaque session

**IMPORTANT : Executer AUTOMATIQUEMENT sans que l'utilisateur le demande :**

```javascript
mcp__keymex-hub__get_restore_context({
  path: "<chemin_du_projet_courant>",
  depth: "full"
})
```

Cela restaure : decisions, conventions, todos, bugs, charte graphique, contexte sessions precedentes.

### PENDANT le developpement

**Executer AUTOMATIQUEMENT quand pertinent :**

| Situation | Action |
|-----------|--------|
| Decision architecturale prise | `mcp__keymex-hub__add_decision` |
| Bug decouvert | `mcp__keymex-hub__add_bug` |
| Tache identifiee | `mcp__keymex-hub__add_todo` |
| Connaissance code apprise | `mcp__keymex-hub__save_code_knowledge` |

### A la FIN de chaque session

**Executer AUTOMATIQUEMENT avant de terminer :**

```javascript
mcp__keymex-hub__save_conversation_summary({
  path: "<chemin_du_projet>",
  summary: "Resume des travaux effectues...",
  unfinished_tasks: ["tache 1", "tache 2"],
  important_context: "Contexte important a retenir..."
})
```

---

## RÈGLES CRITIQUES - À SUIVRE IMPÉRATIVEMENT

### 1. Mise à jour MCP OBLIGATOIRE

**AVANT de commencer tout développement :**
```
1. Appeler `get_project_context` pour charger le contexte
2. Appeler `get_recommended_agent` pour obtenir l'agent optimal
3. Consulter les décisions, conventions et specs existantes
```

**APRÈS tout développement significatif :**
```
1. `log_development_activity` pour logger l'activité
2. `add_decision` si une nouvelle décision architecturale a été prise
3. `add_convention` si une nouvelle convention de code a été établie
4. `add_todo` pour les tâches restantes identifiées
5. `add_bug` pour tout bug découvert
6. `scan_project_structure` si des fichiers ont été ajoutés/supprimés
```

---

## 2. Agents OBLIGATOIRES par contexte

### Développement Laravel/Livewire
**Agent : `laravel-12-livewire-developer`**
- Patterns : laravel|livewire|eloquent|migration|artisan|blade
- Priorité : 100

### Frontend Tailwind/UI
**Agent : `laravel-livewire-frontend-dev`**
- Patterns : tailwind|css|style|dark mode|responsive|ui component
- Priorité : 90

### Exploration du codebase
**Agent : `Explore`**
- Patterns : find|search|explore|where is|how does|understand|architecture
- Priorité : 80

### Planification de fonctionnalités
**Agent : `Plan`**
- Patterns : plan|design|architect|implement|feature|roadmap
- Priorité : 70

### Documentation Claude Code
**Agent : `claude-code-guide`**
- Patterns : claude code|mcp|hook|slash command|sdk
- Priorité : 60

### Déploiement Dokploy
**Agent : `Dokploy`**
- Patterns : dokploy|deploy|docker|container
- Priorité : 50

---

## 5. Spécifications disponibles

Utilisez `get_spec` avec l'ID pour consulter le contenu complet.

| ID | Titre | Catégorie |
|----|-------|-----------|
| 35 | Cahier des charges | - |

---

## Workflow de développement

```
1. get_project_context          → Charger le contexte
2. get_recommended_agent        → Obtenir l'agent optimal
3. [Utiliser l'agent recommandé]
4. Implémenter la tâche
5. log_development_activity     → Logger l'activité
6. Mettre à jour MCP si besoin  → decisions, conventions, specs
```
