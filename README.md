como rodar o projeto.

git clone https://github.com/CyberAPOKA/chat

abra o projeto

rode os seguintes comandos:

composer install
npm install

após isso abra o servidor:
php artisan serve
npm run dev

deixe rodando os comandos do websocket:
php artisan queue:listen
php artisan queue:work

necessário uma versão do php 8.2+ e uma 17+ do node

configure a .env com o banco de dados e pusher:

altere o BROADCAST_CONNECTION=pusher

https://pusher.com/

pegue as chaves no pusher e preencha:

PUSHER_APP_ID={...}
PUSHER_APP_KEY={...}
PUSHER_APP_SECRET={...}
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=sa1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

use as seeds para rodar o banco de dados:
php artisan migrate --seed