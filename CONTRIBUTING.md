# Contributing to Hexaglass

Terima kasih telah tertarik berkontribusi pada proyek Hexaglass! Panduan ini akan membantu Anda memahami proses kontribusi kami.

## Code of Conduct

Kami berkomitmen pada lingkungan yang ramah dan inklusif. Mohon membaca dan menghormati [Code of Conduct](CODE_OF_CONDUCT.md) kami.

## Bagaimana Cara Berkontribusi?

### Reporting Bugs
1. Gunakan GitHub Issues untuk melaporkan bug
2. Jelaskan bug dengan jelas dan berikan steps untuk reproduce
3. Lampirkan error messages dan screenshots jika ada
4. Tentukan environment (local/production) dan browser

Contoh:
```
Title: Login halaman crash saat submit empty email
Description: Ketika saya submit login form dengan email kosong, halaman menjadi blank.
Steps:
1. Buka halaman login
2. Kosongkan field email
3. Klik submit
Browser: Chrome 120
Environment: localhost:8000
```

### Suggesting Enhancements
1. Gunakan GitHub Issues untuk feature requests
2. Jelaskan use case dan benefit dari fitur baru
3. Berikan contoh atau mockup jika memungkinkan

### Pull Requests
1. Fork repository
2. Buat branch baru: `git checkout -b feature/your-feature`
3. Commit changes: `git commit -m "Add: your feature description"`
4. Push ke branch: `git push origin feature/your-feature`
5. Buat Pull Request dengan deskripsi yang jelas

## Development Setup

```bash
# Clone repository
git clone <repository-url>
cd hexaglass-backend

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Start development
php artisan serve
npm run dev
```

## Coding Standards

### PHP
- Follow PSR-12 coding standard
- Use meaningful variable names
- Add docblocks untuk methods
- Use type hints

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController
{
    /**
     * Display example resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        // Implementation
    }
}
```

### JavaScript
- Use ES6+ syntax
- Add comments untuk complex logic
- Follow Airbnb style guide

```javascript
// Good
const processData = (items) => {
  return items
    .filter(item => item.active)
    .map(item => item.name);
};

// Avoid
var data=items.filter(function(item){return item.active});
```

### CSS/Tailwind
- Use Tailwind utility classes
- Avoid custom CSS unless necessary
- Follow BEM naming convention untuk custom classes

```html
<!-- Good -->
<div class="bg-white p-4 rounded-lg shadow-md">
  <h2 class="text-lg font-bold text-gray-800">Title</h2>
</div>

<!-- Avoid -->
<div style="background: white; padding: 1rem;">
  <h2>Title</h2>
</div>
```

## Commit Messages

Use descriptive commit messages:

```bash
# Feature
git commit -m "Add: queue filtering functionality"

# Bug fix
git commit -m "Fix: database connection timeout error"

# Refactor
git commit -m "Refactor: simplify authentication logic"

# Docs
git commit -m "Docs: update API documentation"

# Test
git commit -m "Test: add unit tests for User model"
```

## Testing

```bash
# Run all tests
composer run test

# Run specific test file
php artisan test tests/Feature/QueueTest.php

# Run with coverage
php artisan test --coverage
```

## Documentation

Saat menambahkan fitur baru:
1. Update README.md jika relevan
2. Add comments di code yang complex
3. Update API documentation
4. Add examples jika perlu

## Performance Considerations

- Optimize database queries (N+1 problem)
- Use eager loading: `with()`
- Cache frequently accessed data
- Minimize API response time
- Optimize asset loading

## Security Best Practices

- Validate all user input
- Escape output to prevent XSS
- Use parameterized queries (Eloquent handles this)
- Never commit secrets atau API keys
- Review OWASP guidelines

## Git Workflow

```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes and commit
git add .
git commit -m "Add: new feature"

# Keep branch updated
git fetch origin
git rebase origin/main

# Push changes
git push origin feature/new-feature

# Create Pull Request on GitHub
```

## Code Review Process

1. Maintainer akan review PR Anda
2. Mungkin ada requests untuk changes
3. After approval, PR akan di-merge
4. Branch akan di-delete

## Release Process

1. Update version di composer.json
2. Update CHANGELOG.md
3. Create release tag: `git tag v1.0.1`
4. Push tag: `git push origin v1.0.1`
5. Deploy ke production

## Questions?

- Slack: #hexaglass-development
- Email: dev@hexaglass.com
- Documentation: [RAILWAY_DEPLOYMENT.md](RAILWAY_DEPLOYMENT.md)

---

**Thank you untuk berkontribusi! 🎉**
