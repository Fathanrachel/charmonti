#!/bin/sh
# ─────────────────────────────────────────────────────────────────────────────
# CharmOnTi — Docker Entrypoint Script
# Dijalankan setiap kali container start (app & queue)
# ─────────────────────────────────────────────────────────────────────────────
set -e

echo ""
echo "╔══════════════════════════════════════╗"
echo "║   🌸 CharmOnTi — Starting Up...     ║"
echo "╚══════════════════════════════════════╝"
echo ""

# ── 1. Tunggu PostgreSQL siap ─────────────────────────────────────────────────
echo "⏳ Menunggu database PostgreSQL siap..."
until pg_isready \
    -h "${DB_HOST:-db}" \
    -p "${DB_PORT:-5432}" \
    -U "${DB_USERNAME:-postgres}" \
    -q; do
    echo "   Database belum siap, coba lagi dalam 2 detik..."
    sleep 2
done
echo "✅ Database PostgreSQL siap!"
echo ""

# ── 2. Jalankan Migrations ───────────────────────────────────────────────────
echo "📦 Menjalankan database migrations..."
php artisan migrate --force
echo "✅ Migrations selesai!"
echo ""

# ── 3. Buat Storage Symlink ──────────────────────────────────────────────────
echo "🔗 Membuat storage symlink..."
php artisan storage:link --force 2>/dev/null || true
echo "✅ Storage link selesai!"
echo ""

# ── 4. Cache untuk Performa ──────────────────────────────────────────────────
echo "⚡ Caching config, routes, dan views..."
php artisan config:cache  2>/dev/null || echo "   (config:cache dilewati)"
php artisan route:cache   2>/dev/null || echo "   (route:cache dilewati)"
php artisan view:cache    2>/dev/null || echo "   (view:cache dilewati)"
echo "✅ Cache selesai!"
echo ""

# ── 5. Start ─────────────────────────────────────────────────────────────────
echo "🚀 CharmOnTi siap diakses di http://localhost:8080"
echo "   Admin panel : http://localhost:8080/admin"
echo "   Owner panel : http://localhost:8080/owner"
echo ""

exec "$@"
