#!/usr/bin/env node

/**
 * Server startup script for Railway
 * Ensures environment variables are loaded and PHP server starts correctly
 */

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const PORT = process.env.PORT || 8080;

console.log('🌐 Starting Hexaglass Laravel on 0.0.0.0:' + PORT);
console.log('📂 Document root: ' + __dirname + '/public');
console.log('');

// Show environment variables (for debugging)
console.log('📋 Environment variables set:');
console.log('  APP_KEY=' + (process.env.APP_KEY ? process.env.APP_KEY.substring(0, 20) + '...' : '(empty)'));
console.log('  DB_HOST=' + (process.env.DB_HOST || '(empty)'));
console.log('  DB_PORT=' + (process.env.DB_PORT || '(empty)'));
console.log('  DB_DATABASE=' + (process.env.DB_DATABASE || '(empty)'));
console.log('  DB_USERNAME=' + (process.env.DB_USERNAME || '(empty)'));
console.log('  DB_PASSWORD=' + (process.env.DB_PASSWORD ? '***' : '(empty)'));
console.log('');

// Validate required environment variables
const required = ['APP_KEY', 'DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE'];
for (const envVar of required) {
  if (!process.env[envVar]) {
    console.error('❌ ERROR: ' + envVar + ' not set!');
    process.exit(1);
  }
}

console.log('✅ All required environment variables are set');
console.log('');

// Generate .env file if it doesn't exist
const envPath = path.join(__dirname, '.env');
if (!fs.existsSync(envPath)) {
  console.log('📝 Generating .env file...');
  
  const envContent = `APP_NAME=${process.env.APP_NAME || 'MigasQueue'}
APP_ENV=${process.env.APP_ENV || 'production'}
APP_KEY=${process.env.APP_KEY}
APP_DEBUG=${process.env.APP_DEBUG || 'true'}
APP_URL=${process.env.APP_URL || 'https://web-production-fc4fb.up.railway.app'}
APP_TIMEZONE=${process.env.APP_TIMEZONE || 'Asia/Jakarta'}

APP_LOCALE=${process.env.APP_LOCALE || 'id'}
APP_FALLBACK_LOCALE=${process.env.APP_FALLBACK_LOCALE || 'id'}
APP_FAKER_LOCALE=${process.env.APP_FAKER_LOCALE || 'id_ID'}
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=${process.env.LOG_LEVEL || 'warning'}

DB_CONNECTION=mysql
DB_HOST=${process.env.DB_HOST}
DB_PORT=${process.env.DB_PORT || '3306'}
DB_DATABASE=${process.env.DB_DATABASE}
DB_USERNAME=${process.env.DB_USERNAME}
DB_PASSWORD=${process.env.DB_PASSWORD}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.railway.app

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS=${process.env.MAIL_FROM_ADDRESS || 'noreply@hexaglass.com'}
MAIL_FROM_NAME=${process.env.MAIL_FROM_NAME || 'MigasQueue'}

VITE_APP_NAME=${process.env.VITE_APP_NAME || 'MigasQueue'}

SANCTUM_STATEFUL_DOMAINS=${process.env.SANCTUM_STATEFUL_DOMAINS || 'web-production-fc4fb.up.railway.app,localhost:8000,localhost:3000,127.0.0.1:8000'}
`;

  fs.writeFileSync(envPath, envContent);
  console.log('✅ .env created at runtime');
  console.log('   Database: ' + process.env.DB_USERNAME + '@' + process.env.DB_HOST + ':' + (process.env.DB_PORT || '3306') + '/' + process.env.DB_DATABASE);
} else {
  console.log('🔍 .env file already exists');
}

console.log('');
console.log('✅ Starting PHP server on port ' + PORT + '...');
console.log('');

// Start PHP server
try {
  execSync('php -S 0.0.0.0:' + PORT + ' -t public/', {
    cwd: __dirname,
    stdio: 'inherit',
  });
} catch (error) {
  console.error('❌ ERROR: Failed to start PHP server');
  process.exit(1);
}
